<?php
if ( !function_exists('getPwdHashing') ) {
    function getPwdHashing($pwd) {
        $pwdHashing = '';

        if ($pwd) {
            $pwdHashing = hash("sha256", $pwd);
        }

        return $pwdHashing;
    }
}

if ( !function_exists('getToken') ) {
    function getToken($header) {
        $token = '';

        if ($header) {
            $token = explode(' ', $header)[1];
        }

        return $token;
    }
}

if ( !function_exists('getLoginUserInfo') ) {
    function getLoginUserInfo() {
        return \Config\Services::getLoginUserInfo();
    }
}

if ( !function_exists('getJno2') ) {
    function getJno2($jno) {
        $jno2 = '';

        if ($jno) {
            $jno2 = substr($jno, 0, 8);
            $jno2_2=substr_replace($jno2,'-',6,0);
        }

        return $jno2;
    }
}

if ( !function_exists('getBirth') ) {
    function getBirth($jno) {
        $birth = '';

        if ($jno) {
            $birth = substr($jno, 2, 4);
        }

        return $birth;
    }
}

if ( !function_exists('getGenderCode') ) {
    function getGenderCode($jno) {
        $jnoArr = explode('-', $jno);
        $genderCd = 'X';
        if (count($jnoArr) <= 1) return $genderCd;

        $kind = substr($jnoArr[1], 0, 1);

        if ($kind == 1 || $kind == 3) {
            $genderCd = 'Y';
        }

        return $genderCd;
    }
}

if ( !function_exists('getRefererCd') ) {
    function getRefererCd($referer) {
        $refererCd = 'TP005';
        
        if ($referer == REFERER_HOMEPAGE) {
            $refererCd = 'TP005';
        } else if ($referer == REFERER_CHATBOT) {
            $refererCd = 'TP001';
        } else if ($referer == REFERER_PAYMENT){
            $refererCd = 'TP007';
        } else if ($referer == REFERER_FASTTRACK){
            $refererCd = 'TP008';
        } else if ($referer == REFERER_PAYFAST){
            $refererCd = 'TP009';
        }


        return $refererCd;
    }
}

// 휴대폰 - 삭제
if ( !function_exists('replaceDash') ) {
    function replaceDash($str) {
        $newStr = '';

        if ($str) {
            $newStr = str_replace('-', '', $str);
        }

        return $newStr;
    }
}

// 휴대폰 - 추가
if ( !function_exists('addDashMobile') ) {
    function addDashMobile($mobile) {
        $mobile = preg_replace("/[^0-9]*/s", "", $mobile); //숫자이외 제거

        if (substr($mobile, 0, 2) =='02') {
            return preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/","\\1-\\2-\\3", $tel);
        } else if (substr($mobile, 0, 2) =='8' && substr($mobile, 0, 2) =='15' || substr($mobile, 0, 2) =='16'||  substr($mobile, 0, 2) =='18') {
            return preg_replace("/([0-9]{4})([0-9]{4})$/","\\1-\\2", $mobile);
        } else {
            return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/","\\1-\\2-\\3" ,$mobile);
        }
    }
}

// 경로 만들기
if ( !function_exists('getImplode') ) {
    function getImplode($arr) {
        return implode( '/', $arr );
    }
}
?>