<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


///////////////////////////////////////////////////////////////////////////////////////////////
// 커스텀 상수
defined('ENCRYPT_KEY')          || define('ENCRYPT_KEY', '4Lda22ah8GjvtFRAZWbhA3ZBz4F8K1ls');
defined('JWT_ALG')              || define('JWT_ALG', 'RS256');
defined('JNO_ENCRYPT_KEY')      || define('JNO_ENCRYPT_KEY', 'mysmallkey123456');

defined('MAX_LOGIN_COUNT')      || define('MAX_LOGIN_COUNT', 5);

defined('REFERER_HOMEPAGE')     || define('REFERER_HOMEPAGE', '1');
defined('REFERER_CHATBOT')      || define('REFERER_CHATBOT', '2');
defined('REFERER_PAYMENT')      || define('REFERER_PAYMENT', '3');
defined('REFERER_FASTTRACK')      || define('REFERER_FASTTRACK', '4');
defined('REFERER_PAYFAST')      || define('REFERER_PAYFAST', '5');


defined('CHART_IMAGE_URL')      || define('CHART_IMAGE_URL', 'chart-image.motionecosystem.com');
defined('CNST_IMAGE_URL')       || define('CNST_IMAGE_URL', 'chart-image.motionecosystem.com');

///////////////////////////////////////////////////////////////////////////////////////////////
// API 응답 코드
defined('API_INVALID_TOKEN')                || define('API_INVALID_TOKEN', '100');
defined('API_NEED_RESET_PASSWORD')          || define('API_NEED_RESET_PASSWORD', '110');
defined('API_RESET_PASSWORD_ADMIN')         || define('API_RESET_PASSWORD_ADMIN', '111');
defined('API_PASSWORD_FAIL')        || define('API_PASSWORD_FAIL', '112');
defined('API_SUCCESS')              || define('API_SUCCESS', '200');
defined('API_NO_DATA')              || define('API_NO_DATA', '210');
defined('API_VALID_DATA')           || define('API_VALID_DATA', '220');
defined('NO_QR')                    || define('NO_QR', '230');
defined('API_BAD_REQUEST')          || define('API_BAD_REQUEST', '400');
defined('API_ERROR')                || define('API_ERROR', '500');
///////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////
// 인트라넷 -> 모션E 키 값
defined('BBG_ISS')                || define('BBG_ISS', 'BBG');
defined('BBG_API_KEY')            || define('BBG_API_KEY', 'BBG-INTRANET-MOTION-E-API');
defined('BBG_RES_SUCCESS')        || define('BBG_RES_SUCCESS', 'SUCCESS');
defined('BBG_RES_FAIL')           || define('BBG_RES_FAIL', 'FAIL');
defined('BBG_RES_ERROR')           || define('BBG_RES_ERROR', 'ERROR');
///////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////
defined('QR_GENERATOR_URL') || define('QR_GENERATOR_URL', 'https://qr-generator.motionecosystem.com');