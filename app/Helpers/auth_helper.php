<?php
if ( !function_exists('getPrivateKey') ) {
    function getPrivateKey()
    {
        $privateKey = <<<EOD
        -----BEGIN RSA PRIVATE KEY-----
        MIICWgIBAAKBgGenBT9l1YgDUSrMrNkKk0WUrAOpjaIYXgAfHCyDFVzEq+eT8D2r
        +DbfWlcrIrbnSahcEod47qmRUN7p09h1VL2NqxpqQVrjVMjhLqAec5IzLkRAtAZ8
        SrMds+uNk4PAtP8CHk7JxVtqxZR5wRn+n4SKBxYDnHepVCFAw5puHTc9AgMBAAEC
        gYAGxBpNlyInLMBBoPavfEc8xYWs/4ZlTF3meCForm3YjmYCSd/mxrD/M/k7s28i
        rVpbiRcUjMpXe0riIjVX7eDIIUJmDS8Yds6TkE0xkJnhHr0v3nfDcCmTzoyS99Gq
        MCAvsVEEqzodi654P1PSRqHAxIpi5nImHQwtio6swPrHQQJBAMEHDzgZNpyBqaS0
        1eCCGpFlKSkKW9S97wdtn/lGdM+NNA7PhRm3bpJ03oy4MzgUCyC5vEPMHgj+chlr
        7zbenmUCQQCJd67TihAOZGTWs112lgwAMkUfGuQ53DyZbGIOVsCQEiQksaVoy+8h
        j08C7rb+yy5aZHuRywz1SqNhfrn1Fpv5AkAabaoCx0j34rTkbTH/XDDhCVW6XcW2
        +g5ZGQRXL/NcW3vuLzGNFNVZzhCOecXhfrULVQLW0YKnPpdGrcWB4LcVAkByZPdG
        MAkGgQLtK9vmGB0qeKrOpKkxgRWosumydvzNp1sOcgps9/AqZEBi91WvGFOdgyrV
        ezxwT0lAk52Z19O5AkBOsB/4s4BeGSusA67pg6I11q6GzL8p/65aeSz3PWhcIA08
        /QIyuBUiPI3Yg0yF8O05Wzkm+Oxs9GGlACNIYwYP
        -----END RSA PRIVATE KEY-----
        EOD;

        return $privateKey;
    }
}

if ( !function_exists('getPublicKey') ) {
    function getPublicKey() 
    {
        $publicKey = <<<EOD
        -----BEGIN PUBLIC KEY-----
        MIGeMA0GCSqGSIb3DQEBAQUAA4GMADCBiAKBgGenBT9l1YgDUSrMrNkKk0WUrAOp
        jaIYXgAfHCyDFVzEq+eT8D2r+DbfWlcrIrbnSahcEod47qmRUN7p09h1VL2Nqxpq
        QVrjVMjhLqAec5IzLkRAtAZ8SrMds+uNk4PAtP8CHk7JxVtqxZR5wRn+n4SKBxYD
        nHepVCFAw5puHTc9AgMBAAE=
        -----END PUBLIC KEY-----
        EOD;

        return $publicKey;
    }
}

if ( !function_exists('AESEncrypt') ) {
    function AESEncrypt($str) {
        return base64_encode(openssl_encrypt($str, "AES-256-CBC", ENCRYPT_KEY, true, str_repeat(chr(0), 16)));
    }
}

if ( !function_exists('AESDecrypt') ) {
    function AESDecrypt($str) {
        return openssl_decrypt(base64_decode($str), "AES-256-CBC", ENCRYPT_KEY, true, str_repeat(chr(0), 16));
    }
}

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