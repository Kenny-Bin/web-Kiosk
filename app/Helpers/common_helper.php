<?php
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

function getValidValue(&$data, $key, $defaultVal='')
{
    $val = $defaultVal;

    if (isset($data[$key]) && $data[$key] != '') {
        $val = $data[$key];
    }

    return $val;
}

function allTags($data)
{
    if ( $data ) {
        if (is_array($data) ) {
            foreach ( $data as $key => $value ) {
                $data[$key]        =    strip_tags(addslashes(trim($value)));
            }
            return $data;
        } else {
            return strip_tags(addslashes(trim($data)));
        }
    } else {
        return $data;
    }
}

function getPageNo($pno, $recordPerPage)
{
    return ($pno * $recordPerPage) - $recordPerPage;
}

function getPageInfo($recordPerPage, $pnoPerPage, $pno, $totalCount)
{
    $page = [];
    $page['recordPerPage'] = $recordPerPage; // 한 페이지당 최대 게시글 개수.
    $page['pnoPerPage'] = $pnoPerPage; // 한 페이지당 최대 페이지번호 개수.
    $page['pno'] = $pno; // 페이지번호.
    $page['totalCount'] = $totalCount; // 페이지번호.

    $page['pnoCount'] = ceil($page['totalCount'] / $page['recordPerPage']);
    $page['ppno'] = 1;

    $temp = 0;
    while (1) {
        $temp += $page['pnoPerPage'];
        if ( $temp >= $page['pno'] ) break;
        $page['ppno']++;
    }
    $page['maxPpno'] = ceil($page['pnoCount'] / $page['pnoPerPage']);
    $page['spno'] = ($page['ppno'] - 1) * $page['pnoPerPage'] + 1;
    $page['epno'] = $page['ppno'] * $page['pnoPerPage'];
    $page['sno'] = $page['totalCount'] - (($page['pno'] - 1) * $page['recordPerPage']);

    if ( $page['epno'] > $page['pnoCount'] ) $page['epno'] = $page['pnoCount'];

    return $page;
}

?>