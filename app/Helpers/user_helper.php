<?php
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
?>