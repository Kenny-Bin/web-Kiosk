<?php

namespace App\Controllers;

use App\Models\HospitalModel;
use App\Models\PatientDetailModel;
use App\Models\PatientPRSCModel;
use App\Models\PatientRCPTModel;
use App\Models\PatientSickModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\PatientModel;
use App\Models\CodeModel;
use Firebase\JWT\JWT;

class WebReceipt extends BaseResourceController
{
    private $ptntM;
    private $ptntDM;
    private $codeM;
    private $userM;

    private $recordPerPage = 21; // 한 페이지당 최대 게시글 개수.
    private $pnoPerPage = 5; // 한 페이지당 최대 페이지번호 개수.

    public function __construct()
    {
//        $this->session = \Config\Services::session();

        $ykiho = $_REQUEST['ykiho'];

        $db = \Config\Database::connect('main');
        $builder = $db->table('DB_INFO');
        $builder->select('*');
        $builder->where('ITEM', '0'); // 0 은 고정
        $builder->where('YKIHO', $ykiho);
        $data = $builder->get()->getRowArray(0);

        $config = $config ?? config('Database');

        $config->default['hostname'] = $data['IP'];
        $config->default['username'] = $data['ID'];
        $config->default['password'] = $data['PASSWORD'];
        $config->default['database'] = $data['NAME'];

        $config->production['hostname'] = $data['IP'];
        $config->production['username'] = $data['ID'];
        $config->production['password'] = $data['PASSWORD'];
        $config->production['database'] = $data['NAME'];

        $this->ptntM = new PatientModel();
        $this->ptntSM = new PatientSickModel();
        $this->ptntPM = new PatientPRSCModel();
        $this->ptntDM = new PatientDetailModel();
        $this->ptntRCPTM = new PatientRCPTModel();
        $this->hospitalM = new HospitalModel();
        $this->codeM = new CodeModel();
        $this->userM = new UserModel();
    }

    public function visitChk(){
        helper(['user']);

        $ykiho = $this->request->getGet('ykiho');
        $userId = 'ADMIN';

//        $this->session->set('ykiho',$ykiho);
//        $this->session->set('userId',$userId);

        $data = [
            'ykiho' => $ykiho,
        ];

        echo view('webReceipt/visitChk', $data);
    }

    public function firstReceipt() {
        helper(['common']);

        $visitChk = allTags($this->request->getPostGet('visitChk'));
        echo view('webReceipt/visitChk');
    }

    public function nameWrite(){
        helper(['common']);

        $ykiho = $this->request->getGet('ykiho');
        $visitChk = allTags($this->request->getPostGet('visitChk'));
        $patNm = allTags($this->request->getPostGet('patNm'));

        $data = [
          'ykiho' => $ykiho,
          'visitChk' => $visitChk,
          'patNm' => $patNm,
        ];

        echo view('webReceipt/nameWrite', $data);
    }

    public function phoneWrite() {
        helper(['common']);

        $ykiho = $this->request->getGet('ykiho');
        $visitChk = allTags($this->request->getPostGet('visitChk'));
        $patNm = allTags($this->request->getPostGet('patNm'));

        $data = [
            'ykiho' => $ykiho,
            'visitChk' => $visitChk,
            'patNm' => $patNm,
        ];
        echo view('webReceipt/phoneWrite', $data);
    }

    public function birthPhoneWrite(){
        helper(['common']);

        $ykiho = $this->request->getGet('ykiho');
        $visitChk = getValidValue($_POST, 'visitChk', '');
        $patNm = getValidValue($_POST, 'patNm', '');
        $search = getValidValue($_POST, 'search', '');

        $hospital = $this->hospitalM->getHospitalInfo($ykiho);
        $hosNm = $hospital['OFC_NM'];

        $data = [
            'ykiho' => $ykiho,
            'visitChk' => $visitChk,
            'patNm' => $patNm,
            'search' => $search,
            'hosNm' => $hosNm,
        ];

        echo view('webReceipt/birthPhoneWrite', $data);
    }

    public function jnoWrite(){
        helper(['common']);

        $ykiho = $this->request->getGet('ykiho');
        $visitChk = getValidValue($_POST, 'visitChk', '');
        $patNm = getValidValue($_POST, 'patNm', '');
        $search = getValidValue($_POST, 'search', '');
        $jno = getValidValue($_POST, 'jno', '');
        $mkJno = getValidValue($_POST, 'mkJno', '');

        $data = [
            'ykiho' => $ykiho,
            'visitChk' => $visitChk,
            'patNm' => $patNm,
            'search' => $search,
            'jno' => $jno,
            'mkJno' => $mkJno,
        ];

        echo view('webReceipt/jnoWrite', $data);
    }

    public function addressWrite(){
        helper(['common']);

        $ykiho = $this->request->getGet('ykiho');
        $visitChk = getValidValue($_POST, 'visitChk', '');
        $patNm = getValidValue($_POST, 'patNm', '');
        $search = getValidValue($_POST, 'search', '');
        $jno = getValidValue($_POST, 'jno', '');
        $mkJno = getValidValue($_POST, 'mkJno', '');
        $addr = getValidValue($_POST, 'addr', '');
        $dtl_addr = getValidValue($_POST, 'dtl_addr', '');

        $data = [
            'ykiho' => $ykiho,
            'visitChk' => $visitChk,
            'patNm' => $patNm,
            'search' => $search,
            'jno' => $jno,
            'mkJno' => $mkJno,
            'addr' => $addr,
            'dtl_addr' => $dtl_addr,
        ];

        echo view('webReceipt/addressWrite', $data);
    }

    public function visitInfoWrite(){
        helper(['common']);
        helper(['user']);

        $ykiho = $this->request->getGet('ykiho');
        $visitChk = getValidValue($_POST, 'visitChk', '');
        $patNm = getValidValue($_POST, 'patNm', '');
        $search = getValidValue($_POST, 'search', '');
        $jno = getValidValue($_POST, 'jno', '');
        $mkJno = getValidValue($_POST, 'mkJno', '');
        $addr = getValidValue($_POST, 'addr', '');
        $dtl_addr = getValidValue($_POST, 'dtl_addr', '');
        $diagFldCd = getValidValue($_POST, 'diagFldCd', '');
        $diagFldCdName = getValidValue($_POST, 'diagFldCdName', '');
        $vstPthCd = getValidValue($_POST, 'vstPthCd', '');
        $vstPthCdName = getValidValue($_POST, 'vstPthCdName', '');

        $recordPerPage = $this->recordPerPage;
        $pnoPerPage = $this->pnoPerPage;
        $pno = getValidValue($_POST, 'pno', 1);
        $pageNo = getPageNo($pno, $recordPerPage);

        $treatmentList = $this->codeM->getWebCodeInfo($ykiho, 'CD0020', $pageNo, $recordPerPage);
        $visitList = $this->codeM->getWebCodeInfo($ykiho, 'CD0016', $pageNo, $recordPerPage);

        $treatmentListCount = $this->codeM->getWebCodeInfoCount($ykiho, 'CD0020');
        $visitListCount = $this->codeM->getWebCodeInfoCount($ykiho, 'CD0016');

        $treatmentPage = getPageInfo($recordPerPage, $pnoPerPage, $pno, count($treatmentListCount));
        $visitPage = getPageInfo($recordPerPage, $pnoPerPage, $pno, count($visitListCount));

        $data = [
            'ykiho' => $ykiho,
            'visitChk' => $visitChk,
            'patNm' => $patNm,
            'search' => $search,
            'jno' => $jno,
            'mkJno' => $mkJno,
            'addr' => $addr,
            'dtl_addr' => $dtl_addr,
            'treatmentList' => $treatmentList,
            'visitList' => $visitList,
            'diagFldCd' => $diagFldCd,
            'diagFldCdName' => $diagFldCdName,
            'vstPthCd' => $vstPthCd,
            'vstPthCdName' => $vstPthCdName,
            'treatmentPage' => $treatmentPage,
            'visitPage' => $visitPage,
        ];

        echo view('webReceipt/visitInfoWrite', $data);
    }

    public function rectInfo(){
        helper(['common']);
        $ykiho = $this->request->getGet('ykiho');
        $visitChk = getValidValue($_POST, 'visitChk', '');
        $patNm = getValidValue($_POST, 'patNm', '');
        $search = getValidValue($_POST, 'search', '');
        $jno = getValidValue($_POST, 'jno', '');
        $mkJno = getValidValue($_POST, 'mkJno', '');
        $addr = getValidValue($_POST, 'addr', '');
        $dtl_addr = getValidValue($_POST, 'dtl_addr', '');
        $fullAddress = $addr . " " . $dtl_addr;
        $diagFldCd = getValidValue($_POST, 'diagFldCd', '');
        $diagFldCdName = getValidValue($_POST, 'diagFldCdName', '');
        $vstPthCd = getValidValue($_POST, 'vstPthCd', '');
        $vstPthCdName = getValidValue($_POST, 'vstPthCdName', '');

        $data = [
            'ykiho' => $ykiho,
            'visitChk' => $visitChk,
            'patNm' => $patNm,
            'search' => $search,
            'jno' => $jno,
            'mkJno' => $mkJno,
            'addr' => $addr,
            'dtl_addr' => $dtl_addr,
            'fullAddress' => $fullAddress,
            'diagFldCd' => $diagFldCd,
            'diagFldCdName' => $diagFldCdName,
            'vstPthCd' => $vstPthCd,
            'vstPthCdName' => $vstPthCdName,
        ];

        echo view('webReceipt/rectInfo', $data);
    }

    public function treatment() {
        helper(['common']);

        $ykiho = $this->request->getGet('ykiho');
        $diagFldCd = getValidValue($_POST, 'diagFldCd', '');
        $diagFldCdName = getValidValue($_POST, 'diagFldCdName', '');

        $recordPerPage = $this->recordPerPage;
        $pnoPerPage = $this->pnoPerPage;
        $pno = getValidValue($_POST, 'pno', 1);
        $pageNo = getPageNo($pno, $recordPerPage);

        $treatmentList = $this->codeM->getWebCodeInfo($ykiho, 'CD0020', $pageNo, $recordPerPage);

        $data = [
            'treatmentList' => $treatmentList,
            'diagFldCd' => $diagFldCd,
            'diagFldCdName' => $diagFldCdName,
            'recordPerPage' => $recordPerPage
        ];
        echo view('webReceipt/treatmentList', $data);
    }

    public function visitPath() {
        helper(['common']);

        $ykiho = $this->request->getGet('ykiho');
        $vstPthCd = getValidValue($_POST, 'diagFldCd', '');
        $vstPthCdName = getValidValue($_POST, 'diagFldCdName', '');

        $recordPerPage = $this->recordPerPage;
        $pnoPerPage = $this->pnoPerPage;
        $pno = getValidValue($_POST, 'pno', 1);
        $pageNo = getPageNo($pno, $recordPerPage);

        $visitList = $this->codeM->getWebCodeInfo($ykiho, 'CD0016', $pageNo, $recordPerPage);

        $data = [
            'visitList' => $visitList,
            'vstPthCd' => $vstPthCd,
            'vstPthCdName' => $vstPthCdName,
            'recordPerPage' => $recordPerPage
        ];
        echo view('webReceipt/visitPathList', $data);
    }
    public function checkPtnt(){
        helper(['user']);

        $ykiho = $this->request->getGet('ykiho');

        $visitChk = $this->request->getPost('visitChk');
        $patNm = $this->request->getPost('patNm');
        $search = $this->request->getPost('search');

        $info = $this->ptntM->searchPntn($ykiho, $patNm, $search);

        $data = [
            'visitChk' => $visitChk,
            'patNm' => $patNm,
            'search' => $search,
            'patInfo' => $info,
        ];

        if ($visitChk == 'first') {
            if ( count($info) > 0 ) {   // 환자정보 존재
                $getAcptInfo = $this->ptntDM->getPtntAcptList($ykiho, $info[0]['PAT_NO']);

                if (count($getAcptInfo) > 0) {  // 접수 이력 존재

                    $result = ['result' => 'Y', 'data' => view('webReceipt/popUp_1', $data)];
                    echo json_encode($result);
                } else {
                    $result = ['result' => 'N'];
                    echo json_encode($result);
                }
            } else {    // 환자정보 미존재
                $result = ['result' => 'N'];
                echo json_encode($result);
            }
        } else if ($visitChk == 'after') {
            if ($info) {
                if (count($info) > 1) {
                    $result = ['result' => 'Y', 'data' => view('webReceipt/popUp_2', $data)];
                    echo json_encode($result);
                } else {
                    $result = ['result' => 'Y', 'data' => view('webReceipt/popUp_1', $data)];
                    echo json_encode($result);
                }
            } else {
                $result = ['result' => 'N'];
                echo json_encode($result);
            }
        }
    }

    public function receipt()
    {
        helper(['user', 'auth']);

        // 임시 변수
            $ykiho = $this->request->getGet('ykiho');
            $userId = 'ADMIN';

            $patNo = $this->request->getPost('patNo');
            $patNm = $this->request->getPost('patNm');
            $patJno = $this->request->getPost('jno');
            $diagFldCd = $this->request->getPost('diagFldCd');
            $mobile = $this->request->getPost('search');
            $addr = $this->request->getPost('addr');
            $dtlAddr = $this->request->getPost('dtl_addr');
            $vstPthCd = $this->request->getPost('vstPthCd');
            $smsYn = $this->request->getPost('smsYn') ?? 0;
            $adSmsYn = $this->request->getPost('adSmsYn') ?? 0;
            $prsnYn = $this->request->getPost('prsnYn') ?? 0;

            $mobile = replaceDash($mobile);
            $mobile = addDashMobile($mobile);

            if(!$diagFldCd) $diagFldCd = 'X00';

            // 차트 기본 입력 정보
            $param = [
                'YKIHO' => $ykiho,
                'PRGR_STAT_CD' => 'B',
                'ACPT_DD' => date('Ymd'),
                'ACPT_TM' => date('Hi'),
                'MOPR_TP_CD' => $diagFldCd,
                'STATUS_BOARD_CD' => 'A',
                'STATUS_TIME' => date('Y-m-d H:i:s'),
                'FRST_REG_ID' => 'APP',
                'FRST_REG_DT' => date('Y-m-d H:i:s'),
                'LAST_MOD_ID' => $userId,
                'LAST_MOD_DT' => date('Y-m-d H:i:s'),
            ];

            if (!$patNo) {
                // 연락처로 기등록된 정보인지 확인
                $info = $this->ptntM->getPntnInfoByPhone($ykiho, $patNm, $mobile);

                if (empty($info)){
                    // 주민번호로 기등록된 정보인지 확인
                    $info = $this->ptntM->getPntnInfoByJno($ykiho, $patNm, jNoEncrypt($patJno));
                    if ( count($info) > 0 ) {
                        $patNo = $info[0]['PAT_NO'];

                        // 기존 환자
                        $today = date('Ymd');
                        $totime=date('Hi');
                        // 오늘 날짜로 예약 or 부도처리 된 건이 존재하는지 확인
                        $alreadyCount = $this->ptntDM->getAlreadyRsvnCount($today, $ykiho, $patNo);

                        if ($alreadyCount > 0) {
                            // 있으면 모든 예약 or 부도 정보를 접수처리
                            $this->ptntDM->updatePntnStat($ykiho, $patNo, $today,$totime);
                        } else {
                            // 차트생성
                            $vistSn = $this->ptntDM->getMaxVistSN($ykiho, $patNo);

                            // 신/구환 여부 확인
                            $diagTp = $this->ptntDM->getPtntAcptList($ykiho, $patNo);

                            if (count($diagTp) > 0) {
                                $diagCd = 'C02';
                            } else {
                                $diagCd = 'C01';
                            }
                            $param['PAT_NO'] = $patNo;
                            $param['DIAG_TP_CD'] = $diagCd;
                            $param['VIST_SN'] = $vistSn;
                            $param['DIAG_FLD_CD'] = 'C000';
//                            $param['STATUS_BOARD_CD'] = 'A';
//                            $param['STATUS_TIME'] = date('Y-m-d H:i:s');

                            $this->ptntDM->save($param);
                        }
                    }else{
                        // 신규 환자
                        $chartNo = $this->hospitalM->getMaxChartNo($ykiho);
                        $patNo = $this->ptntM->getMaxPatNo($ykiho);

                        $this->ptntM->save([
                            'YKIHO' => $ykiho,
                            'PAT_NO' => $patNo,
                            'CHART_NO' => $chartNo,
                            'PAT_NM' => $patNm,
                            'PAT_JNO' => jNoEncrypt($patJno),
                            'PAT_JNO2' => getJno2($patJno),
                            'PAT_BTH' => getBirth($patJno),
                            'MOBILE_NO' => $mobile,
                            'ADDR' => $addr,
                            'VST_PTH_CD' => $vstPthCd,
                            'SMS_AGR_YN' => $smsYn,
                            'AD_SMS_AGR_YN' => $adSmsYn,
                            'PRSN_INFO_AGR_YN' => $prsnYn,
                            'FRST_REG_ID' => $userId,
                            'FRST_REG_DT' => date('Y-m-d H:i:s'),
                            'LAST_MOD_ID' => $userId,
                            'LAST_MOD_DT' => date('Y-m-d H:i:s'),
                        ]);

                        // 차트생성
                        $vistSn = $this->ptntDM->getMaxVistSN($ykiho, $patNo);

                        // 신/구환 여부 확인
                        $diagTp = $this->ptntDM->getPtntAcptList($ykiho, $patNo);

                        if (count($diagTp) > 0) {
                            $diagCd = 'C02';
                        } else {
                            $diagCd = 'C01';
                        }
                        $param['PAT_NO'] = $patNo;
                        $param['DIAG_TP_CD'] = $diagCd;
                        $param['VIST_SN'] = $vistSn;
                        $param['DIAG_FLD_CD'] = 'C000';

//                        $param['STATUS_BOARD_CD'] = 'A';
//                        $param['STATUS_TIME'] = date('Y-m-d H:i:s');

                        $this->ptntDM->save($param);

                        $this->hospitalM->save([
                            'YKIHO' => $ykiho,
                            'RCNT_CHART_NO' => $chartNo,
                            'LAST_MOD_ID' => $userId,
                            'LAST_MOD_DT' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }elseif ( count($info) > 0 ) {
                    $patNo = $info['PAT_NO'];
                    $patJno = jNoDecrypt($info['PAT_JNO']);
                    // 기존 환자
                    $today = date('Ymd');
                    $totime=date('Hi');
                    // 오늘 날짜로 예약 or 부도처리 된 건이 존재하는지 확인
                    $alreadyCount = $this->ptntDM->getAlreadyRsvnCount($today, $ykiho, $patNo);

                    if ($alreadyCount > 0) {
                        // 있으면 모든 예약 or 부도 정보를 접수처리
                        $this->ptntDM->updatePntnStat($ykiho, $patNo, $today,$totime);
                    } else {
                        // 차트생성
                        $vistSn = $this->ptntDM->getMaxVistSN($ykiho, $patNo);

                        // 신/구환 여부 확인
                        $diagTp = $this->ptntDM->getPtntAcptList($ykiho, $patNo);

                        if (count($diagTp) > 0) {
                            $diagCd = 'C02';
                        } else {
                            $diagCd = 'C01';
                        }
                        $param['PAT_NO'] = $patNo;
                        $param['DIAG_TP_CD'] = $diagCd;
                        $param['VIST_SN'] = $vistSn;
                        $param['DIAG_FLD_CD'] = 'C000';
//                        $param['STATUS_BOARD_CD'] = 'A';
//                        $param['STATUS_TIME'] = date('Y-m-d H:i:s');

                        $this->ptntDM->save($param);
                    }
                }
            }elseif($patNo) {
                // 기존 환자
                $today = date('Ymd');
                $totime=date('Hi');
                // 오늘 날짜로 예약 or 부도처리 된 건이 존재하는지 확인
                $alreadyCount = $this->ptntDM->getAlreadyRsvnCount($today, $ykiho, $patNo);

                if ($alreadyCount > 0) {
                    // 있으면 모든 예약 or 부도 정보를 접수처리
                    $this->ptntDM->updatePntnStat($ykiho, $patNo, $today,$totime);
                } else {
                    // 차트생성
                    $vistSn = $this->ptntDM->getMaxVistSN($ykiho, $patNo);

                    // 신/구환 여부 확인
                    $diagTp = $this->ptntDM->getPtntAcptList($ykiho, $patNo);

                    if (count($diagTp) > 0) {
                        $diagCd = 'C02';
                    } else {
                        $diagCd = 'C01';
                    }
                    $param['PAT_NO'] = $patNo;
                    $param['DIAG_TP_CD'] = $diagCd;
                    $param['VIST_SN'] = $vistSn;
                    $param['DIAG_FLD_CD'] = 'C000';
//                    $param['STATUS_BOARD_CD'] = 'A';
//                    $param['STATUS_TIME'] = date('Y-m-d H:i:s');
                    $this->ptntDM->save($param);
                }
            }

            if (!$patJno) {
                $info = $this->ptntM->getPntnJno($ykiho, $patNo);
                if ( isset($info['PAT_JNO']) ) {
                    $patJno = jNoDecrypt($info['PAT_JNO']);
                } else {
                    $output = ['status' => API_ERROR, 'result' => '에러 발생1'];
                    return $this->respond($output);
                }
            } else {
                if (!preg_match('/^(?:[0-9]{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[1,2][0-9]|3[0,1]))-[1-9][0-9]{6}$/', $patJno)){
                    $patJno = jNoDecrypt($patJno);
                }
            }

            // 접수한내용 바탕으로 PTNT_INFO 업데이트
            $set = [
                'PAT_NM' => $patNm,
                'MOBILE_NO'  => $mobile,
                'PAT_JNO' => jNoEncrypt($patJno),
                'PAT_JNO2' => getJno2($patJno),
                'PAT_BTH' => getBirth($patJno),
                'PAT_SEX_TP_CD' => getGenderCode($patJno),
                'ADDR'  => $addr,
                'DTL_ADDR'  => $dtlAddr,
                'VST_PTH_CD'  => $vstPthCd,
                'SMS_AGR_YN'  => $smsYn,
                'AD_SMS_AGR_YN'  => $adSmsYn,
                'PRSN_INFO_AGR_YN'  => $prsnYn,
                'RCNT_VST_DD'  => date('Ymd'),
                'LAST_MOD_ID'  => $userId,
                'LAST_MOD_DT'  => date('Y-m-d H:i:s'),
            ];

            $result = $this->ptntM->updateReceipt($set, $ykiho, $patNo);

            $data = [
                'patNm' => $patNm,
                'mobile' => $mobile,
            ];
            if ($result) {
                $rtnResult = ['result' => 'Y', 'data' => view('webReceipt/alertPop', $data)];
                echo json_encode($rtnResult);
            } else {
                $rtnResult = ['result' => 'N'];
                echo json_encode($rtnResult);
            }
    }


}