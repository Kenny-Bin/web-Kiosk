<?php

namespace App\Models;
use CodeIgniter\Model;

class PatientRCPTModel extends Model
{
    protected $table = 'PTNT_RCPT';

    // 수납내역 조회
	public function getPtntRcptList($ykiho, $patNo)
	{
		$sql = " SELECT DETL.VIST_SN
                , DATE_FORMAT(DETL.RCPT_DT, '%Y-%m-%d') AS RCPT_DT
                , DETL.PRGR_STAT_CD
                , GET_CDNAME(DETL.YKIHO, 'CD0026', DETL.PRGR_STAT_CD) AS PRGR_STAT_CD_NM
                , DIAG_DD
                , RCPT.TOT_PAY_AMT
                , RCPT.TOT_NPAY_AMT
                , RCPT.TOT_P100_AMT
                , RCPT.TOT_P100LT_AMT
                , RCPT.TOT_AMT
                , RCPT.PAY_SLF_BRDN_AMT
                , RCPT.NPAY_SLF_BRDN_AMT
                , RCPT.P100_SLF_BRDN_AMT
                , RCPT.P100LT_SLF_BRDN_AMT
                , RCPT.TOT_SLF_BRDN_AMT
                , RCPT.PAY_DMD_AMT
                , RCPT.P100_DMD_AMT
                , RCPT.P100LT_DMD_AMT
                , RCPT.TOT_DMD_AMT
                , RCPT.PAY_ADDC_AMT
                , RCPT.NPAY_ADDC_AMT
                , RCPT.P100_ADDC_AMT
                , RCPT.P100LT_ADDC_AMT
                , RCPT.TOT_ADDC_AMT
                , RCPT.RCPT_AMT
                , RCPT.UNPAY_AMT
                , RCPT.SPAMT
                , RCPT.HNDP_FND
                , RCPT.CARD_PAY_TP_CD
                , RCPT.CARD_PAY_AMT
                , RCPT.CARD_PAY_TP_CD1
                , RCPT.CARD_PAY_AMT1
                , RCPT.CARD_PAY_TP_CD2
                , RCPT.CARD_PAY_AMT2
                , RCPT.CARD_PAY_TP_CD3
                , RCPT.CARD_PAY_AMT3
                , RCPT.CARD_PAY_TP_CD4
                , RCPT.CARD_PAY_AMT4
                , RCPT.CASH_RCPT_YN
                , RCPT.CASH_RCPT_TP_CD
                , RCPT.CASH_PAY_AMT
                , RCPT.DPSTOR_NM
                , RCPT.DPST_ACC_AMT
                , RCPT.CFHC_REM_PAY_AMT
                , RCPT.ETC_PAY_TP_CD
                , RCPT.ETC_PAY_AMT
                , RCPT.ETC_PAY_TP_CD1
                , RCPT.ETC_PAY_AMT1
                , RCPT.ETC_PAY_TP_CD2
                , RCPT.ETC_PAY_AMT2
                , RCPT.ETC_PAY_TP_CD3
                , RCPT.ETC_PAY_AMT3
                , RCPT.ETC_PAY_TP_CD4
                , RCPT.ETC_PAY_AMT4
                , RCPT.RCPT_MEMO
                , RCPT.RCPT_STAFF_ID
                , GET_USERNAME(RCPT.YKIHO, RCPT.RCPT_STAFF_ID, '') AS RCPT_STAFF_NM
                , RCPT.RFND_YN
                , RCPT.RFND_DD
                , RCPT.RFND_MEMO
                , RCPT.STFC_EVLT_SND_CD
                FROM PTNT_RCPT RCPT
                LEFT JOIN PTNT_DETL DETL ON DETL.YKIHO = RCPT.YKIHO AND DETL.PAT_NO = RCPT.PAT_NO AND DETL.VIST_SN = RCPT.VIST_SN AND DETL.DEL_YN = FALSE
                WHERE RCPT.YKIHO = ?
                AND RCPT.PAT_NO = ?
        ";
			
        $query = $this->db->query($sql, [$ykiho, $patNo]);
		$data = $query->getResultArray();
        // echo $this->db->getLastQuery(); exit;

		return $data;
	}

    /**
     * 상담/진료/수납내역 - 상품액,추가금,할인금,부가세
     * @param $ykiho
     * @param $patNo
     * @param $vistSn
     * @return array|mixed|null
     */
    public function getPtntRcptAmt($ykiho, $patNo, $vistSn)
    {
        $sql = " SELECT 
                     SUM(PRICE) AS PRICE, -- 상품액
                     SUM(ADD_AMT) AS ALL_ADD_AMT, -- 추가금
                     SUM(DC_AMT) AS ALL_DC_AMT, -- 할인금
                     SUM(VAT) AS ALL_VAT -- 부가세
                  FROM
                      ( 
                          -- 진료
                          SELECT 
                          1 AS QTY,

                          a.AMT AS PRICE,
                          0 AS ADD_AMT, 
                          0 AS DC_AMT, 
                          0 AS VAT  
                           FROM PTNT_PRSC a
                          WHERE a.YKIHO = ?
                            AND a.PAT_NO = ?
                            AND a.VIST_SN = ?
                            AND a.RCPT_YN = TRUE
                          UNION ALL 
                           SELECT 
                           -- 시술 
                             a.PRCHS_QTY, 

                             a.AMT - a.VAT - a.ADD_AMT + a.DC_AMT AS PRICE,
                             a.ADD_AMT, 
                             a.DC_AMT, 
                             a.VAT  
                           FROM PTNT_MOPR a
                          WHERE a.YKIHO = ?
                            AND a.PAT_NO = ?
                            AND a.VIST_SN = ?
                           --  AND a.RCPT_YN = TRUE
                            AND a.BF_TICK_VIST_SN = '' 
                        ) a
        ";

        $query = $this->db->query($sql, [$ykiho, $patNo, $vistSn, $ykiho, $patNo, $vistSn]);
        $data = $query->getRowArray(0);
//        echo $this->db->getLastQuery(); exit;

        return $data;
    }

    /**
     * 상담/진료/수납내역 - 수납정보
     * @param $ykiho
     * @param $patNo
     * @param $vistSn
     * @return array|mixed|null
     */
    public function getPtntRcptDetailV2($ykiho, $patNo, $vistSn)
    {
        $sql = "SELECT  
                     IFNULL(DATE_FORMAT(d.ACPT_DD, '%Y-%m-%d'), (DATE_FORMAT(d.CNST_DT, '%Y-%m-%d'))) AS ACPT_DD -- 접수일자
                     , GET_USERNAME(d.YKIHO, d.CHRG_DR_ID, '')  AS CHRG_DR_NM -- 수납직원
                     , IFNULL(r.TOT_AMT, 0) AS TOT_AMT -- 총금액
                     , IFNULL(r.CARD_PAY_AMT, 0) + IFNULL(r.CARD_PAY_AMT1, 0) + IFNULL(r.CARD_PAY_AMT2, 0) + IFNULL(r.CARD_PAY_AMT3, 0) + IFNULL(r.CARD_PAY_AMT4, 0) AS CARD_PAY_AMT_TOT  -- 카드 결제금액
                     , IFNULL(r.CASH_PAY_AMT, 0) AS CASH_PAY_AMT_TOT -- 현금 결제금액
                     , IFNULL(r.DPST_ACC_AMT, 0) AS DPST_ACC_AMT_TOT  -- 통장입금금액
                     , IFNULL(r.ETC_PAY_AMT + r.ETC_PAY_AMT1 + r.ETC_PAY_AMT2 + r.ETC_PAY_AMT3 + r.ETC_PAY_AMT4, 0) AS ETC_PAY_AMT_TOT  -- 기타 결제금액
                     , IFNULL(r.RCPT_AMT, 0) AS RCPT_AMT -- 수납금액
                     , IFNULL(r.TOT_SLF_BRDN_AMT - r.RCPT_AMT - r.RFND_AMT - r.EXPN_UNPAY_AMT, 0) AS UNPAY_AMT -- 미수금액
                     , IFNULL(r.RFND_AMT, 0) AS RFND_AMT -- 환불금  
                 FROM PTNT_DETL d
                      LEFT JOIN PTNT_RCPT r ON r.YKIHO = d.YKIHO AND r.PAT_NO = d.PAT_NO AND r.VIST_SN = d.VIST_SN
                WHERE d.YKIHO = ?
                  AND d.PAT_NO = ? 
                  AND d.VIST_SN = ?
                ORDER BY ACPT_DD DESC
        ";

        $query = $this->db->query($sql, [$ykiho, $patNo, $vistSn]);
        $data = $query->getRowArray(0);
        // echo $this->db->getLastQuery(); exit;

        return $data;
    }

    /**
     * 상담/진료/수납내역 - 수납리스트
     * @param $ykiho
     * @param $patNo
     * @param $vistSn
     * @return array|array[]
     */
    public function getPtntRcptListV2($ykiho, $patNo, $vistSn)
    {
        $sql = " SELECT 
                    RCPT_DT,
                    MP_NM,
                    PRCHS_QTY,
                    MP_AMT,
                    PRICE,
                    ADD_AMT,
                    DC_AMT,
                    VAT,
                    RFND_AMT,
                    RFND_YN,
                    RFND_DT
                FROM
                    (SELECT 
                        a.YKIHO,
                            a.PAT_NO,
                            a.VIST_SN,
                            DATE_FORMAT(a.RCPT_DT, '%Y-%m-%d %H:%i') AS RCPT_DT,
                            a.PRSC_NM AS MP_NM,
                            1 AS PRCHS_QTY,
                            (1 * a.UNPRC) AS MP_AMT,
                            (1 * a.UNPRC) - 0 AS PRICE,
                            0 AS ADD_AMT,
                            0 AS DC_AMT,
                            0 AS VAT,
                            a.AMT,
                            a.RFND_AMT,
                            'P' AS MP_GB,
                            a.PRSC_NO AS MP_NO,
                            a.PRSC_CD AS MP_CD,
                            a.RCPT_NO,
                            a.RFND_YN,
                            GET_USERNAME(a.YKIHO, GET_DRID(a.YKIHO, DR_LCS_NO), 'D') AS DR_ID,
                            a.RFND_DT
                    FROM
                        PTNT_PRSC a
                    WHERE
                        a.YKIHO = ?
                            AND a.PAT_NO = ?
                            AND a.VIST_SN = ?
                            AND a.RCPT_YN = TRUE 
                    UNION 
                    SELECT 
                        a.YKIHO,
                            a.PAT_NO,
                            a.VIST_SN,
                            DATE_FORMAT(a.RCPT_DT, '%Y-%m-%d %H:%i') AS RCPT_DT,
                            a.MOPR_NM AS MP_NM,
                            a.PRCHS_QTY,
                            (a.PRCHS_QTY * a.AMT) AS MP_AMT,
                            (a.PRCHS_QTY * a.AMT) - a.VAT AS PRICE,
                            a.ADD_AMT,
                            a.DC_AMT,
                            a.VAT,
                            a.AMT,
                            a.RFND_AMT,
                            'M' AS MP_GB,
                            a.MOPR_NO AS MP_NO,
                            a.MOPR_CD AS MP_CD,
                            a.RCPT_NO,
                            a.RFND_YN,
                            GET_USERNAME(a.YKIHO, DR_ID, 'D') AS DR_ID,
                            a.RFND_DT
                    FROM
                        PTNT_MOPR a
                    WHERE
                        a.YKIHO = ?
                            AND a.PAT_NO = ?
                            AND a.VIST_SN = ?
                            AND a.RCPT_YN = TRUE
                            AND a.BF_TICK_VIST_SN = '') a
                ORDER BY a.RCPT_DT DESC
        ";

        $query = $this->db->query($sql, [$ykiho, $patNo, $vistSn, $ykiho, $patNo, $vistSn]);
        $data = $query->getResultArray();
        // echo $this->db->getLastQuery(); exit;

        return $data;
    }
}