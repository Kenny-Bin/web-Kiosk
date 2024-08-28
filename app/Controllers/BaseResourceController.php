<?php
namespace App\Controllers;

use CodeIgniter\RESTful;

class BaseResourceController extends RESTful\ResourceController
{
    public function __construct()
    {
        helper(['user']);
        if (isset($_REQUEST['ykiho'])) {
            $ykiho = $_REQUEST['ykiho'];
        } else {
            $userInfo = getLoginUserInfo();
            $ykiho = $userInfo['ykiho'];
        }

        $db = \Config\Database::connect('main');
        $builder = $db->table('DB_INFO');
        $builder->select('*');
        $builder->where('ITEM', '0'); // 0 은 고정
        $builder->where('YKIHO', $ykiho); // 요양기관 번호
        $data = $builder->get()->getRowArray(0);


        $config = $config ?? config('Database');

        $config->default['hostname'] = $data['IP'];
        $config->default['username'] = $data['ID'];
        $config->default['password'] = $data['PASSWORD'];
        $config->default['database'] = $data['NAME'];


//        $db->close();
    }
}