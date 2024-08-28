<?php

namespace App\Models;
use CodeIgniter\Model;

class PatientPRSCModel extends Model
{
    protected $table = 'PTNT_PRSC';

    // 처방 이력조회
	public function getRsvnPtntPrscList($ykiho, $patNo, $vistSnArr)
	{
		$sql = " SELECT 'PRSC' AS DIV_CD
                , CONCAT('ㆍ', PRSC.PRSC_NM, ' [', PRSC.FQ1_MDCT_QTY, ' / ', PRSC.DY1_INJC_FQ, ' / ', PRSC.TOT_MDCT_DDCNT, ']') AS PRGS_NM
                , PRSC.VIST_SN, PRSC.PRSC_CD, PRSC.PRSC_NM, PRSC.PRSC_NO, PRSC.TP_CD, PRSC.UNI_DIV_CD, PRSC.CZ_CD, PRSC.ITEM_CD
                , PRSC.PAY_TP, PRSC.PAY_SLF_BRDN, PRSC.IHSP_YN, PRSC.EXP_TP_CD, PRSC.FQ1_MDCT_QTY, PRSC.DY1_INJC_FQ
                , PRSC.TOT_MDCT_DDCNT, PRSC.USAG_DSG_CD, PRSC.USAG_DSG_TXT, PRSC.AMT, PRSC.PRCL_DESC_YN, PRSC.DEL_YN
                , PRSC.ADDC_YN, PRSC.RCPT_YN, PRSC.DUR_SND_YN, PRSC.RMK_TXT, PRSC.RQST_HOSP_CD, PRSC.RQST_SPCM_NM
                , PRSC.DR_LCS_NO, PRSC.CHNG_DD, GET_CFRCDINFO(PRSC.YKIHO, PRSC.PAT_NO, PRSC.VIST_SN, PRSC.PRSC_CD) AS CFR_CD_INFO
                , IFNULL(REG.UNPRC, 0 ) AS UNPRC, REG.CVAL_PNT, REG.USE_GRANT, REG.MAIN_CMPN_CD, REG.OPT_CPMD_IMPL_CZ, REG.DIV_NO
                FROM PTNT_PRSC PRSC
                    LEFT OUTER JOIN REG_PRSC_ALL REG ON REG.YKIHO = PRSC.YKIHO AND REG.TP_CD = PRSC.TP_CD AND REG.PRSC_CD = PRSC.PRSC_CD
                WHERE PRSC.YKIHO = ?
                AND PRSC.PAT_NO = ?
                AND PRSC.VIST_SN IN ?
                AND PRSC.DEL_YN  = FALSE
                ORDER BY VIST_SN DESC, PRSC_NO ";
			
        $query = $this->db->query($sql, [$ykiho, $patNo, $vistSnArr]);
		$data = $query->getResultArray();
        // echo $this->db->getLastQuery(); exit;

		return $data;
	}

    /**
     * 상담/진료/수납내역 - 처방리스트
     * @param $ykiho
     * @param $patNo
     * @param $vistSn
     * @return array|array[]
     */
    public function getPtntPrscListV2($ykiho, $patNo, $vistSn)
    {
        $sql = " SELECT PRSC_CD, -- 코드
                         PRSC_NM, -- 이름
                         CASE PAY_TP WHEN 'N' THEN '비급여' WHEN 'P' THEN '급여' ELSE '-' END AS PAY_TP , -- 보험 
                         IHSP_YN, -- 원내, 원외 구분
                         EXP_TP_CD, -- 예외코드
                         FQ1_MDCT_QTY, -- 1회투약량
                         DY1_INJC_FQ, -- 1회투여회수
                         TOT_MDCT_DDCNT, -- 총투약일수
                         AMT -- 금액   
                FROM PTNT_PRSC
                WHERE YKIHO = ? AND PAT_NO = ? AND VIST_SN = ? AND DEL_YN = 0 ";

        $query = $this->db->query($sql, [$ykiho, $patNo, $vistSn]);
        $data = $query->getResultArray();
        // echo $this->db->getLastQuery(); exit;

        return $data;
    }
}