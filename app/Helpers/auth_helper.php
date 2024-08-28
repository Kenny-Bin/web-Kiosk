<?php
// 주민번호 암호화
if ( !function_exists('jNoEncrypt') ) {
    function jNoEncrypt($jno) {
        return base64_encode(openssl_encrypt($jno, "AES-128-CBC", utf8_encode(JNO_ENCRYPT_KEY), OPENSSL_RAW_DATA, str_repeat(chr(0), 16)));
    }
}

// 주민번호 복호화
if ( !function_exists('jNoDecrypt') ) {
    function jNoDecrypt($jnoD) {
        return openssl_decrypt(base64_decode($jnoD), "AES-128-CBC", utf8_encode(JNO_ENCRYPT_KEY), OPENSSL_RAW_DATA, str_repeat(chr(0), 16));
    }
}
?>