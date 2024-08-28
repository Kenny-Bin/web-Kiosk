<?php

namespace App\Models;
use CodeIgniter\Model;

class PatientSickModel extends Model
{
    protected $table = 'PTNT_SICK';

    // 상병 이력조회
    public function getPtntSickList($ykiho, $patNo, $vistSnArr)
    {
        $builder = $this->db->table($this->table);
		$builder->select("'SICK' AS DIV_CD, VIST_SN, CONCAT('ㆍ', SICK_KOR_NM, '(', SICK_SYM, ')') AS PRGS_NM, SICK_SYM, SICK_KOR_NM, SICK_ENG_NM, SICK_TP, PRCL_SYM, SUSPECT_YN, TP_CD_L, TP_CD_R, TP_CD_BOTH");
        
		$builder->where('YKIHO', $ykiho);
		$builder->where('PAT_NO', $patNo);
		$builder->whereIn('VIST_SN', $vistSnArr);

        $builder->orderBy('SICK_TP, SICK_SYM');

		$data = $builder->get()->getResult('array');
		// echo $builder->getCompiledSelect(); exit;

		return $data;
    }

    /**
     * 상담/진료/수납내역 - 상병리스트
     * @param $ykiho
     * @param $patNo
     * @param $vistSn
     * @return array|array[]
     */
    public function getPtntSickListV2($ykiho, $patNo, $vistSn)
    {
        $sql = " SELECT CASE SICK_TP WHEN '1' THEN '주상병' WHEN '2' THEN '부상병' ELSE '배재' END AS SICK_TP, -- 주,부,배재
                         SICK_SYM, -- 상병기호
                         SICK_KOR_NM, -- 상병 한글명
                         SICK_ENG_NM, -- 상병 영문명
                         PRCL_SYM, -- 특정기호
                         SUSPECT_YN, -- 의증
                         TP_CD_L, -- 좌
                         TP_CD_R, -- 우
                         TP_CD_BOTH -- 양쪽
                FROM PTNT_SICK
                WHERE YKIHO = ? AND PAT_NO = ? AND VIST_SN = ? ";

        $query = $this->db->query($sql, [$ykiho, $patNo, $vistSn]);
        $data = $query->getResultArray();
        // echo $this->db->getLastQuery(); exit;

        return $data;
    }
}