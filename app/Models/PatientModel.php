<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table = 'PTNT_INFO';
    protected $allowedFields = ['YKIHO', 'PAT_NO', 'CHART_NO', 'PAT_NM', 'PAT_JNO', 'PAT_JNO2', 'PAT_BTH', 'DIAG_FLD_CD', 'MOBILE_NO', 'ADDR', 'VST_PTH_CD', 'SMS_AGR_YN', 'AD_SMS_AGR_YN', 'PRSN_INFO_AGR_YN', 'FRST_REG_ID', 'FRST_REG_DT', 'LAST_MOD_ID', 'LAST_MOD_DT'];

    // 연락처로 기등록된 환자인지 확인
    public function getPntnInfoByPhone($ykiho, $patNm, $mobile)
    {
        $builder = $this->db->table($this->table);
        // $builder->select('PAT_NO, PAT_NM, MOBILE_NO, PAT_JNO2');
        $builder->select('PAT_NO, PAT_NM, MOBILE_NO, PAT_JNO, ADDR, VST_PTH_CD');

        $builder->where('YKIHO', $ykiho);
        $builder->where('PAT_NM', $patNm);
        $builder->like('REPLACE(MOBILE_NO, "-", "")', str_replace('-', '', $mobile));

        $data = $builder->get()->getRowArray(0);
//         echo $this->db->getLastQuery(); exit;

        return $data;
    }

    // 연락처로 기등록된 환자인지 확인 - 이름없이
    public function getPntnInfoByPhone2($ykiho, $mobile)
    {
        $builder = $this->db->table($this->table);
        $builder->select('PAT_NO, PAT_NM, PAT_BTH, MOBILE_NO');

        $builder->where('YKIHO', $ykiho);
        $builder->like('REPLACE(MOBILE_NO, "-", "")', str_replace('-', '', $mobile));

        $data = $builder->get()->getResult('array');
        // echo $builder->getCompiledSelect(); exit;

        return $data;
    }

    public function getPntnInfoByIntranet($ykiho, $idx)
    {
        $builder = $this->db->table($this->table . ' INFO');
        $builder->join('PTNT_DETL DETL', 'INFO.YKIHO = DETL.YKIHO AND INFO.PAT_NO = DETL.PAT_NO');
        $builder->select('INFO.PAT_NO, INFO.PAT_NM, INFO.MOBILE_NO, INFO.PAT_JNO, INFO.ADDR, INFO.VST_PTH_CD');

        $builder->where('INFO.YKIHO', $ykiho);
        $builder->where('DETL.INTRANET_ID', $idx);

        $data = $builder->get()->getRowArray(0);
//         echo $this->db->getLastQuery(); exit;

        return $data;
    }

    // 주민번호로 기등록된 정보인지 확인
    public function getPntnInfoByJno($ykiho, $patNm, $patJno)
    {
        $builder = $this->db->table($this->table);
        // $builder->select('PAT_NO, PAT_NM, MOBILE_NO, PAT_JNO2');
        $builder->select('PAT_NO, PAT_NM, MOBILE_NO');

        $builder->where('YKIHO', $ykiho);
        $builder->where('PAT_NM', $patNm);
        // $builder->where('PAT_JNO', $patJno);
        $builder->where('PAT_JNO', $patJno);

        $data = $builder->get()->getResult('array');
        // echo $builder->getCompiledSelect(); exit;

        return $data;
    }

    // 주민번호 가져오기
    public function getPntnJno($ykiho, $patNo)
    {
        $builder = $this->db->table($this->table);
        $builder->select('PAT_JNO');

        $builder->where('YKIHO', $ykiho);
        $builder->where('PAT_NO', $patNo);

        $data = $builder->get()->getRowArray(0);
//         echo $this->db->getLastQuery(); exit;

        return $data;
    }


    // 생년월일 및 핸드폰번호로 기등록된 정보인지 확인
    public function searchPntn($ykiho, $patNm, $search)
    {
        $builder = $this->db->table($this->table);
        // $builder->select('PAT_NO, PAT_NM, MOBILE_NO, PAT_JNO2');
        $builder->select('PAT_NO, PAT_NM, PAT_BTH, MOBILE_NO');

        $builder->where('YKIHO', $ykiho);

        // $search = $this->db->escape($search);
        $builder->where('PAT_NM', $patNm);
        $builder->where(' ( REPLACE(MOBILE_NO, "-", "") like \'%' . str_replace('-', '', $search) . '%\' OR REPLACE(PAT_JNO2, "-", "") LIKE \'' . str_replace('-', '', $search) . '%\' ) ');

        $data = $builder->get()->getResult('array');
        // echo $builder->getCompiledSelect(); exit;

        return $data;
    }


    // 환자 정보 조회
    public function getPntnInfoByNo($ykiho, $patNo)
    {
        $builder = $this->db->table($this->table);
        $builder->select('PAT_NO, CHART_NO, PAT_NM, PAT_JNO, MOBILE_NO, ADDR, DTL_ADDR, VST_PTH_CD, SMS_AGR_YN');

        $builder->where('YKIHO', $ykiho);
        $builder->where('PAT_NO', $patNo);

        $data = $builder->get()->getRowArray(0);
        // echo $builder->getCompiledSelect(); exit;

        return $data;
    }

    // 최대 RCNT_CHART_NO 값 얻기
    public function getMaxPatNo($ykiho)
    {
        $builder = $this->db->table($this->table);
        $builder->select('LPAD(IFNULL(MAX(PAT_NO) + 1, 1), 10, 0) as PAT_NO');

        $builder->where('YKIHO', $ykiho);

        $data = $builder->get()->getResult('array');

        return $data[0]['PAT_NO'];
    }

    // 접수한내용 바탕으로 PTNT_INFO 업데이트
    public function updateReceipt($set, $ykiho, $patNo)
    {
        $builder = $this->db->table($this->table);

        return $builder->update($set, ['YKIHO' => $ykiho, 'PAT_NO' => $patNo]);
    }

    // 대시보드 - 환자리스트 조회
    public function getPntnList($ykiho, $date, $prgrStatCd, $searchWord = '')
    {

        $whereStr = '';

        $searchData = [];
        $searchData[] = $ykiho;
        $searchData[] = $ykiho;
        $searchData[] = $ykiho;
        $searchData[] = $date;
        $searchData[] = $date;
        $searchData[] = $date;
        $searchData[] = $date;


        if ($searchWord) {

            /*            $whereStr .= "AND ( PTNT_INFO.CHART_NO LIKE ? OR PTNT_INFO.PAT_NM LIKE ? OR PTNT_INFO.MOBILE_NO LIKE ? ) ";
                        $searchData[] = '%'.$searchWord.'%';
                        $searchData[] = '%'.$searchWord.'%';
                        $searchData[] = '%'.$searchWord.'%';*/
            $array = explode(",", $searchWord);
            $where = "";
            foreach ($array as $index => $value) {
                /*     if ($index !== 0) {
                         $whereStr .= " AND ";
                     }*/
                $value = str_replace('-', '', $value);
                $whereStr .= "AND (PTNT_INFO.PAT_NM LIKE '%$value%' OR REPLACE(PTNT_INFO.MOBILE_NO, '-', '') LIKE '%$value%' OR PTNT_INFO.CHART_NO LIKE '%$value%') ";

            }

        }
        $searchData[] = $date;

        $sql = "select * from (

 SELECT 
                    PTNT_INFO.PAT_NO,
                    PTNT_INFO.PAT_NM,
                    PTNT_INFO.CHART_NO,
                    PTNT_INFO.PAT_JNO,
                    PTNT_INFO.ADDR,
                    PTNT_INFO.MOBILE_NO,
                    PTNT_INFO.RMK_TXT,
                    PTNT_INFO.RCNT_VST_DD,
                    CONCAT(PAT_NM, '(', CASE PAT_SEX_TP_CD WHEN 'X' THEN '여' WHEN 'Y' THEN '남' ELSE '-' END, '/', IF(GET_USERAGE(PAT_JNO2) IS NULL, '-', GET_USERAGE(PAT_JNO2)), ')') AS STR_BSE_INFO,
                    PTNT_INFO.PAT_SEX_TP_CD,
                    GET_CDNAME(PTNT_INFO.YKIHO,'CD0014' ,PTNT_INFO.PAT_GRD_CD)  AS PATGRDCD, 
                    IF(GET_CDVALUE(PTNT_INFO.YKIHO,'CD0109',PTNT_INFO.PAT_KND_CD) = '', '미지정', GET_CDVALUE(PTNT_INFO.YKIHO,'CD0109',PTNT_INFO.PAT_KND_CD))  AS PATCOL,  
                    PTNT_DETL.VIST_SN, 
                    PTNT_DETL.RSRV_DD, 
                    PTNT_DETL.RSRV_TM, 
                    PTNT_DETL.RSRV_ONO, 
                    PTNT_DETL.RSRV_MEMO, 
                    PTNT_DETL.RSRV_CFR_ID , 
                    PTNT_DETL.RSRV_CNCL_YN , 
                    PTNT_DETL.RSRV_NOTE_CD, 
                    GET_CDNAME(PTNT_INFO.YKIHO,'CD0100',RSRV_TP_CD) AS RSRV_TP_CD,
                    PTNT_DETL.ACPT_DD, PTNT_DETL.ACPT_TM, 
                    CONCAT(SUBSTRING(PTNT_DETL.ACPT_TM, 1, 2), ':', SUBSTRING(PTNT_DETL.ACPT_TM, 3, 2)) AS ACPTTM, 
                    PTNT_DETL.ACPT_CNCL_YN, 
                    PTNT_DETL.ACPT_MEMO, 
                    PTNT_DETL.DIAG_DD, 
                    PTNT_DETL.DIAG_TM, 
                    PTNT_DETL.DIAG_TP_CD, 
                    GET_CDNAME(PTNT_INFO.YKIHO,'CD0019',PTNT_DETL.DIAG_TP_CD) AS CDDIAG, 
                    PTNT_DETL.ONLY_MOPR_YN, IFNULL(PTNT_DETL.MOPR_FNSH_YN,0) MOPR_FNSH_YN  , 
                    IFNULL(IF(PTNT_DETL.MOPR_STA_TM = '', '0000', PTNT_DETL.MOPR_STA_TM), '0000') AS MOPR_STA_TM  , 
                    IFNULL(IF(PTNT_DETL.MOPR_END_TM = '', '0000', PTNT_DETL.MOPR_END_TM), '0000') AS MOPR_END_TM  ,
                    
                    PTNT_DETL.MOPR_TP_CD,
                    GET_CDNAME(PTNT_INFO.YKIHO, 'CD0020',PTNT_DETL.MOPR_TP_CD) AS MOPR_TP_NM,
                    PTNT_DETL.DIAG_FLD_CD, 
                    GET_CDNAME(PTNT_INFO.YKIHO, 'CD0017',PTNT_DETL.DIAG_FLD_CD) AS DIAG_FLD_NM,

                    
                    GET_CDVALUE(PTNT_INFO.YKIHO, 'CD0112', PTNT_DETL.CUR_MOPR_ROOM_CD) AS MOPR_CL ,
                    GET_USERNAME(PTNT_DETL.YKIHO, PTNT_DETL.CNST_ID, 'H') as CNST_ID, IFNULL(DATE_FORMAT(PTNT_DETL.FST_CNST_DT, '%H%i'), '0000') AS FST_CNST_TM, 
                    DATE_FORMAT(PTNT_DETL.CNST_DT, '%Y%m%d') AS CNSTDD, DATE_FORMAT(PTNT_DETL.CNST_DT, '%H:%i') AS CNSTTM, 
                    PTNT_DETL.NEED_CNST_YN, 
                    IFNULL(DATE_FORMAT(IFNULL(PTNT_DETL.CNST_DT, PTNT_DETL.DIAG_TM), '%H%i'), '0000') AS RCPT_WAIT_TM, 
                    DATE_FORMAT(PTNT_DETL.RCPT_DT, '%Y%m%d') AS RCPTDD,
                    IFNULL(DATE_FORMAT(PTNT_DETL.RCPT_DT, '%H%i'), '0000') AS RCPT_TM,
                    PTNT_DETL.CHRG_DR_ID ,
                    PTNT_DETL.CHRG_STAFF_ID ,
                    GET_USERNAME(PTNT_INFO.YKIHO, PTNT_DETL.CHRG_DR_ID,'D') AS CDDR,
                    GET_USERNAME(PTNT_INFO.YKIHO, PTNT_DETL.CHRG_STAFF_ID,'G') AS STAFFNAME, 
                    PTNT_DETL.STAFF_MEMO, 
                    PTNT_DETL.WITH_YN, 
                    PTNT_DETL.MSG_INST_YN ,
                    PTNT_DETL.MSG_VIST_BF_YN , 
                    PTNT_DETL.MSG_VIST_DD_YN ,
                    PTNT_DETL.KKO_INST_YN ,
                    PTNT_DETL.KKO_VIST_BF_YN , 
                    PTNT_DETL.KKO_VIST_DD_YN , 
                    PTNT_DETL.PRGR_STAT_CD, 
                    PTNT_DETL.INSU_KND_CD , 
                    DATE_FORMAT(PTNT_DETL.FRST_REG_DT ,'%Y%m%d') AS LBLTODAY,
                    GET_CDNAME(PTNT_INFO.YKIHO,'CD0025',PTNT_DETL.INSU_KND_CD) AS CDINSUP, 
                    PTNT_RCPT.RCPT_AMT, 
                    PTNT_RCPT.TOT_SLF_BRDN_AMT, 
                    PTNT_RCPT.UNPAY_AMT,
						  PTNT_RCPT.TOT_SLF_BRDN_AMT - PTNT_RCPT.RFND_AMT AS TODAY ,                    
                    IFNULL((SELECT SUM((A.TOT_SLF_BRDN_AMT - A.RFND_AMT) - A.RCPT_AMT - A.EXPN_UNPAY_AMT)
                             FROM PTNT_RCPT A
                             WHERE YKIHO = PTNT_DETL.YKIHO
                               AND PAT_NO = PTNT_DETL.PAT_NO
                               AND VIST_SN = PTNT_DETL.VIST_SN), 0) AS RCVB,
                     PTNT_DETL.STATUS_BOARD_CD, -- 대기현황판 코드
                    (SELECT  BSE_CD_NM FROM CODE_DETAIL WHERE YKIHO = ? AND COM_CD = 'CD0117' AND BSE_CD = PTNT_DETL.STATUS_BOARD_CD) AS STATUS_BOARD_NM, -- 이름
                   CONCAT('#4D',REPLACE((SELECT  RMK_TXT   FROM CODE_DETAIL WHERE YKIHO = ? AND COM_CD = 'CD0117' AND BSE_CD = PTNT_DETL.STATUS_BOARD_CD), '#', ''))
                     AS STATUS_BOARD_COLOR, -- 색상값
                    PTNT_DETL.STATUS_TIME -- 업데이트 시간
                    FROM PTNT_INFO RIGHT JOIN PTNT_DETL ON PTNT_INFO.YKIHO=PTNT_DETL.YKIHO  AND PTNT_INFO.PAT_NO=PTNT_DETL.PAT_NO  
                    LEFT JOIN PTNT_RCPT ON  PTNT_DETL.YKIHO=PTNT_RCPT.YKIHO AND PTNT_DETL.PAT_NO = PTNT_RCPT.PAT_NO AND PTNT_DETL.VIST_SN = PTNT_RCPT.VIST_SN  
                    WHERE PTNT_INFO.YKIHO = ?  AND ( RSRV_DD = ? OR ACPT_DD = ? OR DIAG_DD = ? ) AND PTNT_DETL.DEL_YN='0'
                    $whereStr ";

        if ($prgrStatCd == 'A') {
            // 예약
            $sql .= " AND PRGR_STAT_CD = 'A' ";
            $orderStr = ' ORDER BY RSRV_TM ASC ';
        } else if ($prgrStatCd == 'B') {
            // 접수
            $sql .= " AND PRGR_STAT_CD = 'B' ";
            $orderStr = ' ORDER BY ACPT_TM ASC ';
        } else if ($prgrStatCd == 'CD') {
            // 수납대기+완료
            $sql .= " AND PRGR_STAT_CD IN ('C', 'D') AND MOPR_FNSH_YN <> 2 ";
            $orderStr = ' ORDER BY MOPR_FNSH_YN ASC, DIAG_TM ASC ';
        } else if ($prgrStatCd == 'C') {
            // 수납
            $sql .= " AND ( (PRGR_STAT_CD = 'C' AND MOPR_FNSH_YN = 0) OR (PRGR_STAT_CD = 'C' AND MOPR_FNSH_YN = 1) OR (PRGR_STAT_CD = 'D' AND MOPR_FNSH_YN = 0) ) ";
            $orderStr = ' ORDER BY MOPR_FNSH_YN ASC, DIAG_TM ASC ';
        } else if ($prgrStatCd == 'D') {
            // 수납완료
            $sql .= " AND PRGR_STAT_CD = 'D' AND MOPR_FNSH_YN = 1 ";
            $orderStr = ' ORDER BY MOPR_FNSH_YN ASC, DIAG_TM ASC ';
        } else if ($prgrStatCd == 'F') {
            // 부도(노쇼)
            $sql .= " AND PRGR_STAT_CD = 'F' AND RSRV_CNCL_YN = FALSE ";
            $orderStr = ' ORDER BY RSRV_TM ASC ';
        } else {
            // 시술
            $sql .= " AND PRGR_STAT_CD IN ('C','D') AND MOPR_FNSH_YN = 2 ";
            $orderStr = ' ORDER BY RSRV_TM ASC ';
        }

        $sql .= $orderStr;
        $sql .= ") a
        WHERE CASE WHEN PRGR_STAT_CD = 'A' OR PRGR_STAT_CD = 'F' THEN RSRV_DD = ?
				  ELSE ACPT_DD = ?
					END";


        $query = $this->db->query($sql, array_merge($searchData));
        $data = $query->getResultArray();
        return $data;
    }

    // 환자 시술 목록
    public function getPntnMoprList($ykiho, $patNo, $vistSn)
    {
        $sql = " SELECT MOPR_CD, MOPR_NM, MOPR_NO, RCPT_YN, MOPR_ROOM_CD,
        GET_USERNAME(?, DR_ID,'D') AS DR_ID,
        GET_USERNAME(?, ASST_ID,'I') AS ASST_ID,
        GET_USERNAME(?, ESTC_ID,'J') AS ESTC_ID,
        CHECK_YN
                        , GET_CDVALUE(?, 'CD0112', MOPR_ROOM_CD) AS MOPR_ROOM_CL,GET_USERNAME(?, DR_ID,'D') AS CDDR
                FROM PTNT_MOPR 
                WHERE YKIHO = ?
                AND PAT_NO = ?
                AND VIST_SN = ?
                AND USE_QTY != 0 
                AND NOT (TICKET_YN = '1' AND (BF_TICK_MOPR_NO IS NULL OR BF_TICK_MOPR_NO = ''))  AND DEL_YN = FALSE
                ORDER BY MOPR_NO ";

        $query = $this->db->query($sql, [$ykiho, $ykiho, $ykiho, $ykiho, $ykiho, $ykiho, $patNo, $vistSn]);
        $data = $query->getResultArray();

        return $data;
    }

    // 환자 정보 상세
    public function getPntnDetailInfoByNo($ykiho, $patNo)
    {
        $sql = " SELECT PAT_NO
                , CHART_NO
                , PAT_NM
                , PAT_JNO
                , PAT_JNO2
                , GET_USERAGE(PAT_JNO2) AS PAT_AGE
                , PAT_BTH
                , PAT_SEX_TP_CD
                , PTNT_INFO.MOBILE_NO
                , TEL_NO
                , ADDR
                , DTL_ADDR
                , INSU_KND_CD
                , INSUP_NO
                , JOB
                , DATE_FORMAT(STR_TO_DATE(FST_VST_DD, '%Y%m%d'),'%Y-%m-%d') AS FST_VST_DD
                , DATE_FORMAT(STR_TO_DATE(RCNT_VST_DD, '%Y%m%d'),'%Y-%m-%d') AS RCNT_VST_DD
                , REFEREE
                , PSPR_NO
                ,( CASE AD_SMS_AGR_YN WHEN '1' THEN 'Y' WHEN '0' THEN 'N' ELSE '' END) AS AD_SMS_AGR_YN
                ,( CASE PRSN_INFO_AGR_YN WHEN '1' THEN 'Y' WHEN '0' THEN 'N' ELSE '' END) AS PRSN_INFO_AGR_YN
                ,( CASE SMS_AGR_YN WHEN '1' THEN 'Y' WHEN '0' THEN 'N' ELSE '' END) AS SMS_AGR_YN
                ,( CASE FRGN_YN WHEN '1' THEN 'Y' WHEN '0' THEN 'N' ELSE '' END) AS FRGN_YN
                , PTNT_INFO.RMK_TXT
                , GET_CDNAME(YKIHO,'CD0015',PAT_KND_CD) AS PAT_KND_CD
                , GET_CDNAME(YKIHO,'CD0014',PAT_GRD_CD) AS PAT_GRD_CD
                , GET_CDNAME(YKIHO,'CD0016',VST_PTH_CD) AS VST_PTH_CD
                , (SELECT COUNT(DIAG_DD IS NOT NULL OR DIAG_DD <> '') FROM PTNT_DETL WHERE PTNT_DETL.YKIHO = ? AND PTNT_DETL.PAT_NO=PTNT_INFO.PAT_NO) AS VISTCNT
                , (SELECT SUM(RCPT_AMT) FROM PTNT_RCPT WHERE YKIHO = ? AND PAT_NO=PTNT_INFO.PAT_NO GROUP BY PAT_NO) AS RCPT_AMT
                , PAT_GRD_CD as PAT_GRD_CODE
                , VST_PTH_CD as VST_PTH_CODE
                , (SELECT IMAGE_URL FROM PTNT_PENCT WHERE YKIHO = ? AND PAT_NO = PTNT_INFO.PAT_NO  ORDER BY FRST_REG_DT DESC LIMIT 1) AS IMAGE_URL
            FROM PTNT_INFO
            WHERE YKIHO = ?
            AND DEL_YN = '0'
            AND PAT_NO = ?
                ";

        $query = $this->db->query($sql, [$ykiho, $ykiho, $ykiho, $ykiho, $patNo]);
        $data = $query->getRowArray(0);
        // echo $this->db->getLastQuery(); exit;

        return $data;
    }

    // 환자 정보 수정 전 데이터 출력
    public function getRsvnPntnDetailInfo($ykiho, $patNo)
    {
        $sql = " SELECT 
                    PAT_NM,																-- 고객명
                    GET_CDVALUE(YKIHO, 'CD0109', PAT_KND_CD)  AS PATCOL,  -- 고객 종류(상세)
                    CHART_NO,															-- 차트번호
                    PAT_JNO2,															-- 주민번호
                    PAT_BTH,																-- 고객 생일
                    CASE PAT_SEX_TP_CD WHEN 'X' THEN '여' WHEN 'Y' THEN '남' ELSE '-' END AS PAT_SEX_TP_NM, -- 성별
                    IF(PAT_JNO2 IS NULL, NULL, GET_USERAGE(PAT_JNO2))  AS PAT_AGE, -- 나이
                    GET_USERNAME(YKIHO, CHRG_DR_ID, 'D') AS CHRG_DR_NM, -- 의사명
                    GET_CDNAME(YKIHO,'CD0025',INSU_KND_CD) AS CDINSUP, -- 보험 종류
                    MOBILE_NO, -- 모바일번호
                    TEL_NO, -- 전화번호
                    ADDR, -- 주소
                    CONCAT(DATE_FORMAT(STR_TO_DATE(FST_VST_DD, '%Y%m%d'), '%Y-%m-%d'), '(', SUBSTR(_UTF8mb4'일월화수목금토', DAYOFWEEK(FST_VST_DD), 1), ')') AS FST_VST_DD, -- 최초내원일자
                    CONCAT(DATE_FORMAT(STR_TO_DATE(RCNT_VST_DD, '%Y%m%d'), '%Y-%m-%d'), '(', SUBSTR(_UTF8mb4'일월화수목금토', DAYOFWEEK(RCNT_VST_DD), 1), ')') AS RCNT_VST_DD, -- 최근내원일자
                    (SELECT COUNT(DIAG_DD IS NOT NULL OR DIAG_DD <> '') FROM PTNT_DETL WHERE PTNT_DETL.YKIHO=PTNT_INFO.YKIHO AND PTNT_DETL.PAT_NO=PTNT_INFO.PAT_NO) AS VISTCNT, -- 내원횟수
                    GET_CDNAME(YKIHO,'CD0109',PAT_KND_CD) AS PAT_KND_CD, -- 고객 종류표시
                    GET_CDNAME(YKIHO,'CD0014',PAT_GRD_CD) AS PAT_GRD_CD, -- 고객 등급표시
                    GET_CDNAME(YKIHO,'CD0016',VST_PTH_CD) AS VST_PTH_CD, -- 고객 내원경로
                    (SELECT SUM(RCPT_AMT) FROM PTNT_RCPT WHERE YKIHO = ? AND PAT_NO=PTNT_INFO.PAT_NO GROUP BY PAT_NO) AS RCPT_AMT, -- 총수납액
                    IF(SMS_AGR_YN,'동의','거부') AS SMS_AGR_YN, -- 정보성문자수신 여부
                    IF(AD_SMS_AGR_YN,'동의','거부') AS AD_SMS_AGR_YN, -- 광고문자수신 여부
                    IF(PRSN_INFO_AGR_YN,'동의','거부') AS PRSN_INFO_AGR_YN, -- 개인정보활용동의 여부
                    (CASE FRGN_YN WHEN '1' THEN '외국인' WHEN '0' THEN '내국인' ELSE '' END) AS FRGN_YN, -- 외국인 여부
                    PSPR_NO,  -- 여권번호
                    REFEREE, -- 추천인 차트번호 ,  
                    (SELECT IFNULL(INFO.PAT_NM, '') FROM PTNT_INFO AS INFO WHERE INFO.YKIHO = ? AND INFO.PAT_NO= PTNT_INFO.REFEREE) AS REFEREE_NM, -- 추천인 
                    
                    RMK_TXT -- 고객 비고 메모
                    FROM PTNT_INFO
                    WHERE YKIHO  = ?
                      AND DEL_YN = '0'
                      AND PAT_NO = ? ";

        $query = $this->db->query($sql, [$ykiho, $ykiho, $ykiho, $patNo]);
        $data = $query->getRowArray(0);

        return $data;
    }

    // 진료일자별 이력조회
    public function getRsvnPtntHistory($ykiho, $patNo)
    {
        $sql = " SELECT CONCAT(DATE_FORMAT(STR_TO_DATE(DETL.DIAG_DD, '%Y%m%d'), '%Y-%m-%d'), 
                    '(', SUBSTR(_UTF8mb4'일월화수목금토', DAYOFWEEK(DETL.DIAG_DD), 1), ')') AS DIAG_DD
                    , DETL.VIST_SN
                    , 'DATE' AS DIV_CD
                    , GET_USERNAME(PTNT.YKIHO, DETL.CHRG_DR_ID, 'D') AS CHRG_DR_NM
                    , DETL.SYMT_PRGS 
                    , DETL.DIAG_MEMO 
                FROM PTNT_INFO PTNT
                    LEFT JOIN PTNT_DETL DETL ON DETL.YKIHO = PTNT.YKIHO AND DETL.PAT_NO = PTNT.PAT_NO
                WHERE PTNT.YKIHO = ?
                AND PTNT.PAT_NO = ?
                AND DETL.DIAG_DD != ''
                ORDER BY DETL.DIAG_DD DESC
            ";

        $query = $this->db->query($sql, [$ykiho, $patNo]);
        $data = $query->getResultArray();

        return $data;
    }

    // 진료메모 조회
    public function getRsvnPtntMemoList($ykiho, $patNo, $vistSn)
    {
        $sql = " SELECT PTNT.MEDICALRECORD , DETL.DIAG_DD
                FROM PTNT_INFO PTNT
                    LEFT JOIN PTNT_DETL DETL ON DETL.YKIHO = PTNT.YKIHO AND DETL.PAT_NO = PTNT.PAT_NO    
                WHERE PTNT.YKIHO = ?
                AND PTNT.PAT_NO = ?
                AND DETL.VIST_SN = ?";

        $query = $this->db->query($sql, [$ykiho, $patNo, $vistSn]);
        $data = $query->getResultArray();

        return $data;
    }

    /**
     * 상담/진료/수납내역 리뉴얼 (23.01.09)
     * @param $ykiho
     * @param $patNo
     * @return array|array[]
     */
    public function getPtntListV2($ykiho, $patNo)
    {
        $sql = " SELECT 
                    del.VIST_SN, -- 방문                    
                    DATE_FORMAT(STR_TO_DATE(ACPT_DD, '%Y%m%d'), '%Y-%m-%d') AS STR_CNST_DD,
                    DATE_FORMAT(STR_TO_DATE(ACPT_TM, '%H%i%s'), '%H:%i:%s') AS STR_CNST_DT,
                    GET_USERNAME(del.YKIHO, del.CHRG_DR_ID, 'D') AS CHRG_DR_NM, -- 의사명
                    GET_CDNAME(del.YKIHO,'CD0025',del.INSU_KND_CD) AS CD_INSUP, -- 보험 종류,
                    GET_CDNAME(del.YKIHO, 'CD0020', del.MOPR_TP_CD)  AS MOPR_TP_CD_NM, -- 진료분류
                    GET_CDNAME(del.YKIHO, 'CD0019', del.DIAG_TP_CD)  AS DIAG_TP_CD_NM, -- 신환/구환
                    
                    
                    del.CNST_DT, -- 상담일자
                    GET_USERNAME(del.YKIHO, del.CNST_ID, '') AS CNST_NM, -- 상담담당자
                    FUNC_GET_CDNAME(del.YKIHO, 'CD0090', del.CNST_RSLT, '')      AS CNST_RSLT_NM, -- 상담결과
                    del.CNST_MEMO, -- 상담메모
                    del.ASST_MEMO, -- 어시메모 
                    info.CATAG_CD, -- 시술 카테고리 저장  
                    
                    GET_CDVALUE(del.YKIHO, 'CD0043', del.DGRSLT_TP_CD)  AS DGRSLT_TP_NM, -- 진료결과(상세)
                    del.SYMT_PRGS, -- 증상경과(상세)
                    GET_CDVALUE(del.YKIHO, 'CD0017', del.DIAG_FLD_CD)  AS DIAG_FLD_NM,   -- 진료분야코드
                    (SELECT GROUP_CONCAT(SICK_KOR_NM SEPARATOR ' / ') AS SICK_KOR_NM
                    FROM PTNT_SICK
                    WHERE YKIHO = del.YKIHO 
                    AND PAT_NO = del.PAT_NO 
                    AND VIST_SN = del.VIST_SN) AS SICK_KOR_NM,    -- 상병
--                    del.DIAG_MEMO, -- 진료메모
                    info.MEDICALRECORD AS DIAG_MEMO,-- 진료기록(진료메모)
                    
                    del.RCPT_DT, -- 수납일자                    
                    (SELECT STAT_MEMO FROM PTNT_RCPT_HIST  WHERE YKIHO = del.YKIHO AND PAT_NO = del.PAT_NO AND VIST_SN = del.VIST_SN ORDER BY STAT_DT DESC LIMIT 1) AS RCPT_MEMO, -- 수납메모
                    CASE del.PRGR_STAT_CD WHEN 'C' THEN '수납대기' WHEN 'D' THEN '수납완료' WHEN 'E' THEN '수납취소' ELSE '-' END AS PRGR_STAT_CD_NM, -- 수납상태 
                    IFNULL(rcpt.TOT_SLF_BRDN_AMT - rcpt.RCPT_AMT - rcpt.RFND_AMT - rcpt.EXPN_UNPAY_AMT, 0) AS UNPAY_AMT,  -- 미수금액
                    IFNULL(rcpt.RFND_AMT, 0) AS RFND_AMT, -- 환불금액
                    IFNULL(rcpt.TOT_AMT, 0) AS TOT_AMT -- 총금액
                     
                    FROM PTNT_DETL AS del
                      INNER JOIN  PTNT_INFO AS info 
                        ON del.YKIHO = info.YKIHO AND del.PAt_No = info.PAT_NO 
                      LEFT JOIN PTNT_RCPT AS rcpt 
                         ON del.YKIHO = rcpt.YKIHO AND del.PAT_NO = rcpt.Pat_NO AND del.VIST_SN = rcpt.VIST_SN
                    WHERE del.YKIHO = ? AND del.PAT_NO = ?
                      AND del.PRGR_STAT_CD NOT IN('A', 'F')
                    ORDER BY del.ACPT_DD DESC, del.VIST_SN DESC
                    LIMIT 0, 20   
        ";

        $query = $this->db->query($sql, [$ykiho, $patNo]);
        $data = $query->getResultArray();
        // echo $this->db->getLastQuery(); exit;

        return $data;
    }

    public function getReceiptHistory($ykiho, $patNm, $mobile)
    {
        $today = date('Ymd');

        $mobile = str_replace("-", "", $mobile);
        $mobile = "%" . $mobile . "%";

        $sql = "SELECT detl.ykiho, detl.PAT_NO , detl.vist_sn, detl.RSRV_DD, detl.PRGR_STAT_CD
  FROM PTNT_DETL AS detl
 WHERE detl.YKIHO = ?
   AND detl.PAT_NO IN (SELECT  info.PAT_NO
                			 FROM PTNT_INFO info 
                		   WHERE info.YKIHO = detl.YKIHO 
								  AND info.PAT_NM  = ?
								  AND REPLACE(info.MOBILE_NO, '-','') LIKE ? 
								  AND RSRV_DD  <= ?
								  AND (detl.PRGR_STAT_CD !='A' AND (detl.PRGR_STAT_CD != 'F' AND detl.ACPT_CNCL_YN != '1')));";

        $query = $this->db->query($sql, [$ykiho, $patNm, $mobile, $today]);
        $data = $query->getResultArray();

        $cnt = 0;

        if ($data) {
            $cnt = 1;
        } else {
            $cnt = -1;
        }
        return $cnt;
    }

    // 고객 검색
    public function searchUser($ykiho, $srchVal)
    {
        $array = explode(",", $srchVal);
        $where = "";
        foreach ($array as $index => $value) {
            if ($index !== 0) {
                $where .= " AND ";
            }
            $value = str_replace('-', '', $value);
            $where .= " ( PAT_NM LIKE '%$value%' OR REPLACE(MOBILE_NO, '-', '') LIKE '%$value%' OR CHART_NO LIKE '%$value%') ";

        }

        $sql = "SELECT YKIHO
     , PAT_NO
     , PAT_NM
     , CHART_NO
     , ADDR
     , MOBILE_NO
     , CONCAT(SUBSTRING(PAT_BTH,1,2),'/', SUBSTRING(PAT_BTH,3,2)) AS PAT_BTH
     , CONCAT('',SUBSTRING(RCNT_VST_DD,1,4),'-',SUBSTRING(RCNT_VST_DD,5,2),'-', SUBSTRING(RCNT_VST_DD,7,2)) AS VST
     , CONCAT(PAT_NM, '(', CASE PAT_SEX_TP_CD WHEN 'X' THEN '여' WHEN 'Y' THEN '남' ELSE '-' END, '/', IF(GET_USERAGE(PAT_JNO2) IS NULL, '-', GET_USERAGE(PAT_JNO2)), ')') AS STR_BSE_INFO
     , ADDR
     , SMS_AGR_YN
     , AD_SMS_AGR_YN
     , PRSN_INFO_AGR_YN
 FROM PTNT_INFO
 WHERE YKIHO = ? AND DEL_YN = '0' 
AND $where
 ORDER BY RCNT_VST_DD DESC, PAT_NO LIMIT 100 OFFSET 0";

        $query = $this->db->query($sql, [$ykiho]);
        $data = $query->getResultArray();

        return $data;
    }
}