<?php

// 调试模式开关
define( 'DEBUG_MODE', false);
define( 'DEVELOP_MODE', false);

define( 'COOKIE_PATH', './shaver1602/');
define( 'COOKIE_EXPIRE', 60*60*24*7);

define( 'DB_PREFIX', 'shaver1602_');

//设置错误报告
if ( DEBUG_MODE ) {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    //$_COOKIE["userId"] = 1;
}else{
    ini_set('display_errors', false);
}

//设置数据库是本地还是服务器
if ( DEVELOP_MODE ) {
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "root";
    $db_data = "campaign";
    $db=mysql_connect($db_host,$db_user,$db_pass);
}else{
    $db_host = SAE_MYSQL_HOST_M;
    $db_port = SAE_MYSQL_PORT;
    $db_user = SAE_MYSQL_USER;
    $db_pass = SAE_MYSQL_PASS;
    $db_data = SAE_MYSQL_DB;
    $db=mysql_connect($db_host.':'.$db_port,$db_user,$db_pass);
}


mysql_select_db($db_data);
mysql_set_charset('utf8',$db);



class Response{
    const JSON="json";
    /*
     * 按show方式输出数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * @param array $type 数据
     * return string
     * */
    public static function show($code,$message='',$data=array(),$type=self::JSON){
        if(!is_numeric($code)){
            return '';
        }

        $type = isset($_GET['format'])?$_GET['format']:self::JSON;
        $result = array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );

        if($type=='json'){
            self::json($code,$message,$data);
            exit;
        }elseif($type=='xml'){
            self::xmlToEncode($code,$message,$data);
            exit;
        }elseif($type=='array'){
            echo '<pre>';
            print_r($result);
            echo '</pre>';
        }else{

        }
    }

    /*
     * 按json方式输出数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * return string
     * */
    public static function json($code,$message='',$data=array()){

        if(!is_numeric($code)){
            return '';
        }

        $result = array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );
        echo json_encode($result);
        exit;
    }


    /*
     * 按XML方式输出数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * return string
     * */
    public static function xml($code,$message='',$data=array()){
        if(!is_numeric($code)){
            return '';
        }

        $result = array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );
        //header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF_8'?>\n";
        $xml .= "<root>\n";
        $xml .=self::xmlToEncode($result);
        $xml .= "</root>";
        echo $xml;
    }
    public static function xmlToEncode($data){
        $xml = "";
        foreach($data as $key=>$value){
            $xml .="<{$key}>";
            $xml .=is_array($value)?self::xmlToEncode($value):$value;
            $xml .="</{$key}>\n";
        }
        return $xml;
    }
}
?>