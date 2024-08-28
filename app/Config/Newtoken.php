<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use App\Models\UserModel;
use App\Models\HospitalModel;

class Newtoken extends BaseResourceController
{
    use ResponseTrait;

    private $userM;
    private $hosM;

    public function __construct()
    {
        parent::__construct();
        $this->userM = new UserModel();
        $this->hosM = new HospitalModel();
    }
    public function Newtoken()
    {
        helper(['user', 'auth']);

        $output = ['status' => ResponseInterface::HTTP_BAD_REQUEST, 'result' => '필수 데이터 누락'];

        if ($this->request->getMethod() === 'post' && $this->validate([
                'ykiho' => 'required',
                'userId' => 'required',

            ])) {
            $ykiho = $this->request->getPost('ykiho');
            $userId = $this->request->getPost('userId');
            $userPw = $this->request->getPost('userPw');

            $userInfo = $this->userM->getUserInfo($ykiho, $userId);

/*            // 초기화된 비밀번호인지 확인
            if ($userInfo['PW_INIT_YN'] == '1' && getPwdHashing('1234') == $userInfo['USER_PW']) {
                $output = ['status' => API_NEED_RESET_PASSWORD, 'result' => '초기화된 비밀번호입니다. 비밀번호를 변경해주세요.'];

                return $this->respond($output, ResponseInterface::HTTP_UNAUTHORIZED);
            }

            if ( getPwdHashing($userPw) != $userInfo['USER_PW'] ) {
                // 비밀번호 다름
                $output = ['status' => API_PASSWORD_FAIL, 'result' => '비밀번호가 일치하지 않습니다.'];

                if ($userInfo['PW_ERR_CNT'] >= MAX_LOGIN_COUNT) {
                    $output = ['status' => API_RESET_PASSWORD_ADMIN, 'result' => '비밀번호를 5회 잘못입력하였습니다. 관리자에게 문의하세요.'];

                    return $this->respond($output, ResponseInterface::HTTP_UNAUTHORIZED);
                }

                // 비밀번호 오류 체크
                $this->userM->failLogin($ykiho, $userId);

                return $this->respond($output, ResponseInterface::HTTP_UNAUTHORIZED);
            }*/

            $secretKey = getPrivateKey();

            $payload = [
                'iss' => AESEncrypt($userInfo['YKIHO']),
                'sub' => AESEncrypt($userInfo['USER_ID']),
            ];

            $token = JWT::encode($payload, $secretKey, JWT_ALG);

            $output = ['status' => API_SUCCESS, 'result' => '성공', 'access_token' => $token];
            return $this->respond($output);
        }

        return $this->respond($output);
    }
}
