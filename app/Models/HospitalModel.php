<?php

namespace App\Models;
use CodeIgniter\Model;

class HospitalModel extends Model
{
    protected $table = 'HOSP_INFO';
    protected $primaryKey = 'YKIHO';
    protected $allowedFields = ['RCNT_CHART_NO', 'LAST_MOD_ID', 'LAST_MOD_DT'];

    public function getHospitalInfo($ykiho) {
        $builder = $this->db->table($this->table);
        $builder->select('YKIHO, OFC_NM');

        $builder->where('YKIHO', $ykiho);

        $data = $builder->get()->getRowArray(0);

        return $data;
    }
    // 최대 RCNT_CHART_NO 값 얻기
    public function getMaxChartNo($ykiho)
    {
        $builder = $this->db->table($this->table);
        $builder->select('LPAD(IFNULL(MAX(RCNT_CHART_NO) + 1, 1), 10, 0) as RCNT_CHART_NO');
        
        $builder->where('YKIHO', $ykiho);

        $data = $builder->get()->getResult('array');

        return $data[0]['RCNT_CHART_NO'];
    }

    // 캘린더에서 사용하는 타임당예약개수, 타임간격, 예약마감 조회
    public function getSettingTimeInfo($ykiho)
    {
        $builder = $this->db->table($this->table);
        $builder->select('TIME_RSVT, TIME_INTRVL, RSVT_DL');
        
        $builder->where('YKIHO', $ykiho);

        $data = $builder->get()->getRowArray(0);

        return $data;
    }

    // 등록된 요양기관번호인지 확인
    public function checkYkiho($ykiho)
    {
        $builder = $this->db->table($this->table);
        $builder->selectCount('ykiho', 'cnt');
        
        $builder->where('YKIHO', $ykiho);

        $data = $builder->get()->getResult('array');
        return $data[0]['cnt'];
    }

    // 개인정보 처리방침
    public function getPrivacyInfo($ykiho)
    {
        $builder = $this->db->table($this->table);
        $builder->select('PRIVACY_STRING');
        
        $builder->where('YKIHO', $ykiho);

        $data = $builder->get()->getRowArray(0);

        return $data;
    }


    /**
     * 지점코드로 요양기관번호 얻기
     * @param $corpCode
     * @return mixed
     */
    public function getYkihoByCorpCode($corpCode)
    {
        $builder = $this->db->table($this->table);
        $builder->select('ykiho');

        $builder->where('HOP_INTRANET_ID', $corpCode);

        $data = $builder->get()->getRowArray(0);

        return $data['ykiho'];
    }
}