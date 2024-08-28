<?php

namespace App\Models;
use CodeIgniter\Model;

class PatientDetailModel extends Model
{
    protected $table = 'PTNT_DETL';
	protected $allowedFields = ['YKIHO', 'PAT_NO', 'VIST_SN', 'PRGR_STAT_CD', 'RSRV_DD', 'RSRV_TM',
        'RSRV_MEMO', 'ACPT_DD', 'ACPT_TM', 'DIAG_FLD_CD', 'RSRV_TP_CD', 'INTRANET_ID', 'FRST_REG_ID', 'FRST_REG_DT', 'LAST_MOD_ID', 'LAST_MOD_DT',
        'DIAG_TP_CD', 'MOPR_TP_CD', 'STATUS_BOARD_CD', 'STATUS_TIME'];

    // 최대 VIST_SN값 얻기
	public function getMaxVistSn($ykiho, $patNo)
	{
		$builder = $this->db->table($this->table);
		$builder->select('LPAD(IFNULL(MAX(VIST_SN) + 1, 1), 5, 0) as VIST_SN');
        
		$builder->where('YKIHO', $ykiho);
		$builder->where('PAT_NO', $patNo);

		$data = $builder->get()->getResult('array');

		return $data[0]['VIST_SN'];
	}

    public function getPtntAcptList($ykiho, $patNo)
    {
/*        $builder = $this->db->table($this->table);
        $builder->select("YKIHO, PAT_NO ,PRGR_STAT_CD");
        $builder->where('YKIHO', $ykiho);
        $builder->where('PAT_NO', $patNo);

        $data = $builder->get()->getResult('array');*/
        $sql = " SELECT ACPT_DD AS MAX_ACPT_DD
                    FROM PTNT_DETL 
                    WHERE YKIHO = ?
                        AND PAT_NO = ? 
                        AND PRGR_STAT_CD NOT IN ('A', 'F')
                        AND RSRV_CNCL_YN = FALSE
                        AND DEL_YN = FALSE
                         ";

        $query = $this->db->query($sql, [ $ykiho, $patNo]);
        $data = $query->getRowArray();

        return $data;
    }

	// 메모 업데이트, 상담 업데이트
	public function updatePntnDetail($set, $ykiho, $patNo, $vistSn)
	{
		$builder = $this->db->table($this->table);
		
		return $builder->update($set, ['YKIHO' => $ykiho, 'PAT_NO' => $patNo, 'VIST_SN' => $vistSn]);
	}

	// 상담 내역 조회
	public function getPntnCnstList($ykiho, $patNo)
	{
		$builder = $this->db->table($this->table.' DETL');
		$builder->select("DETL.VIST_SN, DETL.CNST_DT, DATE_FORMAT(STR_TO_DATE(DETL.CNST_DT, '%Y-%m-%d'), '%Y-%m-%d') AS STR_CNST_DD, DETL.CNST_RSLT, DETL.CNST_MEMO, DETL.ASST_MEMO, DETL.CNST_ID, DETL.ACPT_CFR_ID, GET_USERNAME(DETL.YKIHO, DETL.CNST_ID, '') AS CNST_NM, GET_USERNAME(DETL.YKIHO, DETL.ACPT_CFR_ID, '') AS ACPT_CFR_NM, FUNC_GET_CDNAME(DETL.YKIHO, 'CD0090', DETL.CNST_RSLT, '') AS CNST_RSLT_NM, DETL.CNST_CTGR_DIV");
        
		$builder->where('DETL.YKIHO', $ykiho);
		$builder->where('DETL.PAT_NO', $patNo);
		$builder->where('DETL.DEL_YN', FALSE);
		$builder->where('DETL.CNST_DT IS NOT NULL');

		$builder->orderBy('CNST_DT DESC, VIST_SN DESC');

		$data = $builder->get()->getResult('array');
		// echo $builder->getCompiledSelect(); exit;

		return $data;
	}

	// 최근 VIST_SN값 얻기
	public function getRecentVistSn($ykiho, $patNo)
	{
		$builder = $this->db->table($this->table);
		$builder->select('MAX(VIST_SN) as VIST_SN');
        
		$builder->where('YKIHO', $ykiho);
		$builder->where('PAT_NO', $patNo);

		$data = $builder->get()->getResult('array');

		return $data[0]['VIST_SN'];
	}

	// 진료일자 별 예약 내역
	public function getPntnRsvnList($ykiho, $patNo)
	{
		$builder = $this->db->table($this->table.' DETL');

		$builder->select("DETL.VIST_SN,DETL.ACPT_MEMO, DETL.RSRV_DD, DETL.RSRV_TM, DATE_FORMAT(STR_TO_DATE(DETL.RSRV_DD, '%Y%m%d'), '%Y-%m-%d') AS STR_RSRV_DD, 
		DATE_FORMAT(STR_TO_DATE(DETL.RSRV_TM, '%H%i'), '%H시%i분') AS STR_RSRV_TM, DETL.RSRV_ONO, DETL.RSRV_MEMO, DETL.RSRV_CNCL_YN, DETL.RSRV_CFR_ID, 
		GET_USERNAME(DETL.YKIHO, DETL.RSRV_CFR_ID, '') AS RSRC_CFR_NM, DETL.CHRG_DR_ID , GET_USERNAME(DETL.YKIHO, DETL.CHRG_DR_ID, 'D') AS CHRG_DR_NM, 
		DETL.MOPR_TP_CD, GET_CDNAME(DETL.YKIHO, 'CD0020', DETL.MOPR_TP_CD) AS MOPR_TP_CD_NM, DETL.DIAG_FLD_CD, GET_CDNAME(DETL.YKIHO, 'CD0017', DETL.DIAG_FLD_CD) AS DIAG_FLD_CD_NM, 
		DETL.KKO_INST_YN, DETL.KKO_VIST_BF_YN, DETL.KKO_VIST_DD_YN, DETL.PRDC_TM, 
		FUNC_GET_CDNAME(DETL.YKIHO, 'CD0081', DETL.PRDC_TM, '') AS STR_PRDC_TM, 		
		FUNC_GET_CDNAME(DETL.YKIHO, 'CD0026', DETL.PRGR_STAT_CD, '') AS PRGR_STAT_CD_NM,
		CASE WHEN PRGR_STAT_CD = 'A' THEN '예약'
         WHEN PRGR_STAT_CD = 'B' THEN '접수'
         WHEN PRGR_STAT_CD = 'C' THEN '수납대기'
         WHEN PRGR_STAT_CD = 'D' THEN '수납완료'
         WHEN PRGR_STAT_CD = 'F' AND RSRV_CNCL_YN THEN '예약취소'
         WHEN PRGR_STAT_CD = 'F' AND RSRV_CNCL_YN = false THEN '부도(노쇼)'
         ELSE '' END AS PRGR_STAT_CD_NM");
        
		$builder->where('DETL.YKIHO', $ykiho);
		$builder->where('DETL.PAT_NO', $patNo);
		$builder->where('DETL.DEL_YN', FALSE);
		$builder->where('DETL.RSRV_DD != "" ');

		$builder->orderBy('RSRV_DD DESC, RSRV_TM DESC, VIST_SN DESC');
		//$builder->limit(20, 0);

		$data = $builder->get()->getResult('array');
		// echo $builder->getCompiledSelect(); exit;

		return $data;
	}

	// 내원객 최근 정보 가져오기
	public function getRecentlyPtntInfo($ykiho, $patNo, $vistSn)
	{
		$builder = $this->db->table($this->table);
		$builder->select("RSRV_DD, RSRV_TM");
        
		$builder->where('YKIHO', $ykiho);
		$builder->where('PAT_NO', $patNo);
		$builder->where('VIST_SN', $vistSn);
		$builder->where('DEL_YN', FALSE);

		$data = $builder->get()->getRowArray(0);

		return $data;
	}

    /**
     * 같은 날짜에 이미 예약/부도로 등록 되어있는지 체크
     * @param $rsrvDd
     * @param $patNo
     * @return array|array[]
     */
    public function getAlreadyRsvnCount($rsrvDd, $ykiho, $patNo)
    {
        $sql = " SELECT count(*) as cnt
                    FROM PTNT_INFO AS info
                          LEFT JOIN PTNT_DETL AS detl ON detl.YKIHO = info.YKIHO AND detl.PAT_NO = info.PAT_NO AND ( detl.PRGR_STAT_CD ='A')
                    WHERE info.YKIHO = ?
                        AND info.PAT_NO = ? 
                        AND detl.RSRV_DD = ? ";

        $query = $this->db->query($sql, [ $ykiho, $patNo,$rsrvDd]);
        $data = $query->getRowArray(0);

        return $data['cnt'];
    }

    /**
     * 예약/부도를 접수 상태로 전체 업데이트
     * @param $ykiho
     * @param $patNo
     * @param $rsrvDd
     * @return void
     */
    public function updatePntnStat($ykiho, $patNo, $rsrvDd,$rsrvTt,$diagCd)
    {
        $sql = " UPDATE PTNT_DETL 
                    SET FRST_REG_ID='APP', 
                        PRGR_STAT_CD = 'B',
                        STATUS_BOARD_CD = 'A',
                        STATUS_TIME = NOW(),
                        ACPT_TM = ?,
                        ACPT_DD = ?,
                        DIAG_TP_CD = ?
                WHERE YKIHO = ? 
                  AND PAT_NO = ? 
                  AND RSRV_DD = ? 
                  AND ( PRGR_STAT_CD ='A') ";

        $this->db->query($sql, [$rsrvTt,$rsrvDd,$diagCd,$ykiho, $patNo, $rsrvDd]);
    }

    public function getIntranetIdxRsvnInfo($ykiho, $patNo, $rsvnIdx)
    {
        $builder = $this->db->table($this->table);
        $builder->select('YKIHO, PAT_NO, VIST_SN, PRGR_STAT_CD, INTRANET_ID, FRST_REG_ID, FRST_REG_DT, LAST_MOD_ID, LAST_MOD_DT, RSRV_MEMO');

        $builder->where('YKIHO', $ykiho);
        $builder->where('PAT_NO', $patNo);
        $builder->where('INTRANET_ID', $rsvnIdx);

        $data = $builder->get()->getRowArray(0);

        return $data;

    }
}