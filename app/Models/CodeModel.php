<?php

namespace App\Models;
use CodeIgniter\Model;

class CodeModel extends Model
{
    protected $table = 'CODE_MASTER';

    // 공통/기본코드 조회
	public function getCodeInfo($ykiho, $comCD)
	{
		$builder = $this->db->table($this->table.' MSTR');
        $builder->join('CODE_DETAIL DETL', 'MSTR.COM_CD = DETL.COM_CD AND MSTR.YKIHO = DETL.YKIHO');
		$builder->select('DETL.COM_CD, DETL.BSE_CD, DETL.BSE_CD_NM, CONCAT("[", DETL.BSE_CD, "]", DETL.BSE_CD_NM) AS NEW_CD');

		$builder->where('MSTR.YKIHO', $ykiho);
        $builder->where('MSTR.USE_YN', true);
        $builder->where('DETL.USE_YN', true);

        if ( is_array($comCD) ) {
            $builder->whereIn('MSTR.COM_CD', $comCD);
        } else {
            $builder->where('MSTR.COM_CD', $comCD);
        }

		$builder->orderBy('COM_CD, SEQ=0, SEQ, BSE_CD', '', false);

		$data = $builder->get()->getResult('array');
		// echo $builder->getCompiledSelect(); exit;

		return $data;
	}

    public function getWebCodeInfo($ykiho, $comCD, $pageNo, $recordPerPage)
    {
        $builder = $this->db->table($this->table.' MSTR');
        $builder->join('CODE_DETAIL DETL', 'MSTR.COM_CD = DETL.COM_CD AND MSTR.YKIHO = DETL.YKIHO');
        $builder->select('DETL.COM_CD, DETL.BSE_CD, DETL.BSE_CD_NM, CONCAT("[", DETL.BSE_CD, "]", DETL.BSE_CD_NM) AS NEW_CD');

        $builder->where('MSTR.YKIHO', $ykiho);
        $builder->where('MSTR.USE_YN', true);
        $builder->where('DETL.USE_YN', true);

        if ( is_array($comCD) ) {
            $builder->whereIn('MSTR.COM_CD', $comCD);
        } else {
            $builder->where('MSTR.COM_CD', $comCD);
        }

        $builder->orderBy('COM_CD, SEQ=0, SEQ, BSE_CD', '', false);
        $builder->limit($recordPerPage, $pageNo);

        $data = $builder->get()->getResult('array');
        // echo $builder->getCompiledSelect(); exit;

        return $data;
    }

    public function getWebCodeInfoCount($ykiho, $comCD)
    {
        $builder = $this->db->table($this->table.' MSTR');
        $builder->join('CODE_DETAIL DETL', 'MSTR.COM_CD = DETL.COM_CD AND MSTR.YKIHO = DETL.YKIHO');
        $builder->select('DETL.COM_CD, DETL.BSE_CD, DETL.BSE_CD_NM, CONCAT("[", DETL.BSE_CD, "]", DETL.BSE_CD_NM) AS NEW_CD');

        $builder->where('MSTR.YKIHO', $ykiho);
        $builder->where('MSTR.USE_YN', true);
        $builder->where('DETL.USE_YN', true);

        if ( is_array($comCD) ) {
            $builder->whereIn('MSTR.COM_CD', $comCD);
        } else {
            $builder->where('MSTR.COM_CD', $comCD);
        }

        $builder->orderBy('COM_CD, SEQ=0, SEQ, BSE_CD', '', false);

        $data = $builder->get()->getResult('array');
        // echo $builder->getCompiledSelect(); exit;

        return $data;
    }


}