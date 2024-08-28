<?php
if (!function_exists('ImgUpload')) {
    function ImgUpload($uploadDir, $fileName, $file)
    {

        $response = [
            'code' => 0,
            'msg' => '',
            'data' => []
        ];

        $host = getenv('IMAGE_SERVER_HOST');
        $port = getenv('IMAGE_SERVER_PORT');
        $ftpId = getenv('IMAGE_SERVER_FTP_ID');
        $ftpPwd = getenv('IMAGE_SERVER_FTP_PWD');
        $rootDir = getenv('IMAGE_SERVER_ROOT_DIR');

        $dir = getImplode([$rootDir, $uploadDir, $fileName]);

        $new_uploadDir = str_replace("/Image", '', $uploadDir);
        $con = getImplode([$rootDir, $new_uploadDir]);

        $fc = ftp_connect($host, $port);
        if (!$fc) {
            $response['msg'] = 'FTP 연결 오류';
            return $response;
        }

        $isLogined = ftp_login($fc, $ftpId, $ftpPwd);
        if (!$isLogined) {
            ftp_close($fc);
            $response['msg'] = 'FTP 로그인 실패';
            return $response;
        }

        ftp_pasv($fc, true);

        $contents = ftp_nlist($fc, $con);
        if (!$contents) {
            ftp_mkdir($fc, $con);
            ftp_chmod($fc, 0777, $con);

            ftp_mkdir($fc, $con . '/Image');
            ftp_chmod($fc, 0777, $con . '/Image');

            ftp_mkdir($fc, $con . '/ResizeImage');
            ftp_chmod($fc, 0777, $con . '/ResizeImage');
        }
        /*
        $image_contents = ftp_nlist($fc, $con . '/Image');
        if (!$image_contents) {
            ftp_mkdir($fc, $con . '/Image');
            ftp_chmod($fc, 0777, $con . '/Image');
        }

        $resizeImage_contents = ftp_nlist($fc, $con . '/ResizeImage');

        if (!$resizeImage_contents) {

            ftp_mkdir($fc, $con . '/ResizeImage');
            ftp_chmod($fc, 0777, $con . '/ResizeImage');
        }*/

        $isUploaded = ftp_put($fc, $dir, $file, FTP_BINARY);

        if (!$isUploaded) {
            ftp_close($fc);
            $response['msg'] = '원본 파일업로드 실패';
            return $response;
        } else {//파일업로드 성공시 이미지 리사이즈 진행

            $info = getimagesize($file);
            $upFileWidth = $info[0];
            $upFileHeight = $info[1];

            if ($upFileWidth > 1280 || $upFileHeight > 720) {

                if ($upFileWidth >= $upFileHeight) { //가로가 더 긴 이미지
                    $resizeWidth = 1280;
                    $resizeHeight = 720;
                } else { //세로가 더 긴이미지
                    $resizeWidth = 720;
                    $resizeHeight = 1280;
                }

                $motion_img_path = '/home/real-api/www/public/resize_image';//리사이즈하여 저장할 모션 API 서버의 경로 및 파일명
                //$motion_img_path = '/home/motion-api/www/motion/public/resize_image';//리사이즈하여 저장할 모션 API 서버의 경로 및 파일명
                //$motion_img_path = '/var/www/html/public/resize_image';//리사이즈하여 저장할 모션 API 서버의 경로 및 파일명

                if (!file_exists($motion_img_path)) {
                    if (!mkdir($motion_img_path, 0777, true)) {
                        ftp_close($fc);
                        $response['msg'] = '모션 API 서버 리사이즈 이미지 저장용 임시 디렉토리 생성 실패';
                        return $response;
                    }
                }

                $upfileInfo = pathinfo($fileName);
                $resizeFileName = $upfileInfo['filename'] . '_Resize.' . $upfileInfo['extension']; //리사이즈 하여 원격에 저장할 파일명

                $motion_img_path .= '/' . $resizeFileName;

                $resizeImg = imgResize($file, $motion_img_path, 70, $resizeWidth, $resizeHeight);

                $resize_dir = getImplode([$rootDir, str_replace('Image', 'ResizeImage', $uploadDir), $resizeFileName]); //리사이즈 된 파일 저장할 원격 디렉토리 경로

                //리사이즈된 이미지 업로드
                $isResizeUploaded = ftp_put($fc, $resize_dir, $motion_img_path, FTP_BINARY);

                if (!$isResizeUploaded) {
                    ftp_close($fc);
                    $response['msg'] = '리사이즈 파일 업로드 실패';
                    return $response;
                } else {
                    if (is_file($motion_img_path)) {
                        if (!unlink($motion_img_path)) {
                            ftp_close($fc);
                            $response['msg'] = '모션 API 서버에 리사이즈된 이미지 파일 삭제 실패';
                            return $response;
                        } else {
                            ftp_close($fc);
                            $response['code'] = 1;
                            $response['msg'] = 'SUCCESS';
                            $response['data'] = ['fileName' => $fileName, 'resizeFileName' => $resizeFileName];

                            return $response;
                        }
                    } else {
                        ftp_close($fc);
                        $response['msg'] = '모션 API 서버에 리사이즈된 이미지 파일 없음';
                        return $response;
                    }
                }
            } else {
                $response['code'] = 1;
                $response['msg'] = 'SUCCESS';
                $response['data'] = ['fileName' => $fileName];

                return $response;
            }
        }
    }
}

if (!function_exists('ImgResize')) {
    function ImgResize($file, $destination, $quality, $w, $h)
    {
        $info = getimagesize($file);

        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($file);

        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($file);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($file);

        $dst = imagecreatetruecolor($w, $h);

        imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, imagesx($image), imagesy($image));

        $result = imagejpeg($dst, $destination, $quality);

        imagedestroy($dst);
        imagedestroy($image);

        return $result;

    }
}