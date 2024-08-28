<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'USER_INFO';
	protected $allowedFields = ['PW_ERR_CNT', 'LAST_MOD_ID', 'LAST_MOD_DT'];

    // 회원 정보
	public function getUserInfo($ykiho, $userId)
	{
		$builder = $this->db->table($this->table);
		$builder->select('YKIHO, USER_ID, USER_NM, USER_PW, PW_ERR_CNT, PW_INIT_YN, ADMIN_YN, PW_CHG_DT, USER_JNO2, LOGIN_STAT_CD, LAST_LOGIN_IP');
        
		$builder->where('YKIHO', $ykiho);
		$builder->where('USER_ID', $userId);
		$builder->where('USE_YN', true);

		$data = $builder->get()->getRowArray(0);
		// echo $builder->getCompiledSelect(); exit;

		return $data;
	}

	// 로그인 실패 시 횟수 증가
	public function failLogin($ykiho, $userId)
	{
		$builder = $this->db->table($this->table);
		$builder->set('PW_ERR_CNT', 'PW_ERR_CNT + 1', false);
		$builder->set('LAST_MOD_DT', 'now()', false);
		$builder->where('YKIHO', $ykiho);
		$builder->where('USER_ID', $userId);
		$builder->update();
	}

	// 의사조회
	public function getDoctor($ykiho)
	{
		$builder = $this->db->table($this->table);
		// $builder->select('YKIHO, USER_ID, USER_NM, JOB_TYPE_CD, DR_LCS_NO, DGSBJT_CD');
		$builder->select('USER_ID, USER_NM');
        
		$builder->where('YKIHO', $ykiho);
		$builder->where('USE_YN', true);
		$builder->where('JOB_TYPE_CD', 'D');

		$builder->orderBy('USER_NM', 'ASC');

		$data = $builder->get()->getResult('array');
		// echo $builder->getCompiledSelect(); exit;

		return $data;
	}

	// 담당직원 조회
	public function getStaff($ykiho)
	{
		$builder = $this->db->table($this->table);
		// $builder->select('YKIHO, USER_ID, USER_NM, JOB_TYPE_CD, DR_LCS_NO, DGSBJT_CD');
		$builder->select('USER_ID, USER_NM');
        
		$builder->where('YKIHO', $ykiho);
		$builder->where('USE_YN', true);
		$builder->where('JOB_TYPE_CD !=', 'D');

		$builder->orderBy('USER_NM', 'ASC');

		$data = $builder->get()->getResult('array');
		// echo $builder->getCompiledSelect(); exit;

		return $data;
	}
}
