<?php


function checkrobot($useragent=''){
    static $kw_spiders = array('bot', 'crawl', 'spider' ,'slurp', 'sohu-search', 'lycos', 'robozilla');
    static $kw_browsers = array('msie', 'netscape', 'opera', 'konqueror', 'mozilla');
 
    $useragent = strtolower(empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent);
    if(strpos($useragent, 'http://') === false && dstrpos($useragent, $kw_browsers)) return false;
    if(dstrpos($useragent, $kw_spiders)) return true;
    return false;
}
function dstrpos($string, $arr, $returnvalue = false) {
    if(empty($string)) return false;
    foreach((array)$arr as $v) {
        if(strpos($string, $v) !== false) {
            $return = $returnvalue ? $v : true;
            return $return;
        }
    }
    return false;
}

function domiantop(){
	$domian = $_SERVER['SERVER_NAME'];
	if ($domian == '127.0.0.1' || $domian == 'localhost') {
		return '127.0.0.1';
	}else{
		$res = '';
		$d_arr = explode('.', $domian);
		foreach ($d_arr as $key => $value) {
			if($key == count($d_arr) - 2){
				$res = $value;
			}
			if ($key == count($d_arr) - 1) {
				$res = $res.'.'.$value;
			}
		}
		return $res;
	}
}

if (checkrobot()) {
	//机器人访问
	show();
}else{
	//人访问
	header('Content-type: text/html; charset=utf-8');
	show();
}

function show(){
	$keyword = '';
	$html = file_get_contents('template.html');
	$ci = file_get_contents('all/'.domiantop().'/ci');
	$liang = file_get_contents('all/'.domiantop().'/liang');
	$liang_arr = explode("\r\n", $liang);
	$domian_list = explode("\r\n", file_get_contents('conf.ini'));
	foreach ($domian_list as $key => $value) {
		if ($value !== '') {
			$domian = explode('----', $value);
			if ($domian[0] == domiantop() ) {
				$keyword = $domian[1];
				break;
			}
		}
	}
		
	$data['keyword'] =  unicode_encode($keyword);
	$data['keys'] = $ci;
	foreach ($liang_arr as $key => $value) {
		if ($value !== '') {
			$s = 'key'.($key + 1);
			$data[$s] = $value;
		}
	}
	$res = templatereplace($data,$html);
	echo $res;

}

function templatereplace($arr,$tempstr){
	foreach ($arr as $key => $value) {
		$tempstr = str_replace('{$'.$key.'}', $value, $tempstr);
	}
	return $tempstr;
}

function unicode_encode($str, $encoding = 'utf-8', $prefix = '&#', $postfix = ';') {
    //将字符串拆分
    $str = iconv("UTF-8", "gb2312", $str);
    $cind = 0;
    $arr_cont = array();
 
    for ($i = 0; $i < strlen($str); $i++) {
        if (strlen(substr($str, $cind, 1)) > 0) {
            if (ord(substr($str, $cind, 1)) < 0xA1) { //如果为英文则取1个字节
                array_push($arr_cont, substr($str, $cind, 1));
                $cind++;
            } else {
                array_push($arr_cont, substr($str, $cind, 2));
                $cind+=2;
            }
        }
    }
    foreach ($arr_cont as &$row) {
        $row = iconv("gb2312", "UTF-8", $row);
    }
 	$unicodestr = '';
    //转换Unicode码
    foreach ($arr_cont as $key => $value) {
        $unicodestr.= $prefix . base_convert(bin2hex(iconv('utf-8', 'UCS-4', $value)), 16, 10) .$postfix;
    }
 
    return $unicodestr;
}
  
?>