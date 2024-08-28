<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Config\Services;

class AuthBBG implements FilterInterface
{
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
            $iss = AESDecrypt($decode->iss);
            $apiKey = AESDecrypt($decode->api);

            if ($iss != BBG_ISS || $apiKey != BBG_API_KEY ){
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