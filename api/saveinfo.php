<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/4
 * Time: 16:04
 */
include_once ("config.php");
$username = $_POST['username'];
$tel      = $_POST['tel'];
$sex      = $_POST['sex'];
$iphone   = $_POST['iphone'];
$use_shaver= $_POST['use_shaver'];
$skin_care=$_POST['skin_care'];
$age      = $_POST['age'];
$city     = $_POST['city'];
$hmsr     = $_POST['hmsr'];

//$username = 'qweqew';
//$tel      = '15800663463';
//$sex      = 1;
//$iphone   = 0;
//$use_shaver= 0;
//$skin_care=0;
//$age      = 2;
//$city     = 2;
//$hmsr     = 23;


$username = addslashes($username);
$tel      = addslashes($tel);
$sex      = (int)$sex;
$iphone   = (int)$iphone;
$use_shaver= (int)$use_shaver;
$skin_care=(int)$skin_care;
$age      = (int)$age;
$city     = (int)$city;
$hmsr     = addslashes($hmsr);


$hasError = false;
$strError = '';

if(mb_strlen($username,'utf-8')<=0||mb_strlen($username,'utf-8')>30){
    $hasError = true;
    $strError .= '姓名为空或多于30字。';
}

$pattern = "/^1[3-8]\d{9}$/i";

if ( !preg_match( $pattern, $tel ) ) {
    $hasError = true;
    $strError .= '手机格式不对。';
}

if($hasError){
    echo '{"result":0,"msg":"'.$strError.'"}';
    exit();
}
$sql = "select tel from ".DB_PREFIX."userinfo where tel=".$tel;
$query = mysql_query($sql);
if(mysql_num_rows($query)>0){
    echo json_encode(array('result'=>-1,'msg'=>'抱歉!该手机号码已经注册过.'));
    exit();
}else{
    $sql = "insert into ".DB_PREFIX."userinfo(username,tel,sex,iphone,use_shaver,skin_care,age,city,hmsr) values('$username',
    '$tel','$sex','$iphone','$use_shaver','$skin_care','$age','$city','$hmsr')";

    $query = mysql_query($sql);

    if($query){
        echo json_encode(array('result'=>1,'msg'=>'保存数据成功.'));
    }else{
        echo json_encode(array('result'=>-2,'msg'=>'保存数据失败!'));

    }
}



