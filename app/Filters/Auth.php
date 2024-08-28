<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Config\Services;

class Auth implements FilterInterface
{

    public function __construct()
    {

        helper(['user']);
        $userInfo = getLoginUserInfo();
        $ykiho = $userInfo['ykiho'];
        $db = \Config\Database::connect('main');
        $builder = $db->table('DB_INFO');
        $builder->select('*');
        $builder->where('ITEM','0'); // 0 은 고정
        $builder->where('YKIHO',$ykiho); // 요양기관 번호
        $data = $builder->get()->getRowArray(0);


        $config = $config ?? config('Database');

        $config->default['hostname'] = $data['IP'];
        $config->default['username'] = $data['ID'];
        $config->default['password'] = $data['PASSWORD'];
        $config->default['database'] = $data['NAME'];

    }

    public function before(RequestInterface $request, $arguments = null)
    {
        helper(['auth']);

        $header = $request->getServer('HTTP_AUTHORIZATION');
        if(!$header) {
            return Services::response()
                            ->setJSON(['status' => API_INVALID_TOKEN, 'result' => 'Token Required1'])
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $token = explode(' ', $header)[1];

        try {
            $decode = JWT::decode($token, new Key(getPublicKey(), JWT_ALG));
            $ykiho = AESDecrypt($decode->iss);
            $userId = AESDecrypt($decode->sub);

            $userM = new \App\Models\UserModel();
            $info = $userM->getUserInfo($ykiho, $userId);
            
            if (!isset($info['YKIHO'])){
                return Services::response()
                            ->setJSON(['status' => API_INVALID_TOKEN, 'result' => 'Invalid Token2'])
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
        } catch (\Throwable $th) {
            return Services::response()
                            ->setJSON(['status' => API_INVALID_TOKEN, 'result' => 'Invalid Token3'])
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}