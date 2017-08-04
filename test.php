<?php
require_once ('includes/dbcon.php');
echo $dat= createagdemo(74,'abc@abcd.com','543654');
//echo $che=checkalready('abc@abc.co',543654);
function createagdemo($agent_id,$email,$phonenumber){
if(checkalready($email,$phonenumber)){
$user_id= getdemo();
if($user_id!=''){
$gendemo=gendemo($user_id,$agent_id,$email,$phonenumber);
return $gendemo;
}
else{
return "please contact callcenter";
}
}
else{
return "already given please contact callcenter";
}
//$rtval

}

function checkalready($email,$phonenumber){
global $con;

 $sql="SELECT * FROM `demo_account_create` WHERE `phonenumber`='$phonenumber' and `email`='$email'";
$result = mysqli_query($con, $sql);
$num_rows = mysqli_num_rows($result);
if($num_rows>0){
return false;
}
else{
return true;
}
}

function getdemo(){
global $con;
$sql="select demo_id from demo_pool where demo_st='available' or demo_add_time<adddate(now(),-7) limit 1";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
//print_r($value2);
$user_id = $row['demo_id'];
return $user_id;
}
function gendemo($user_id,$agent_id,$email,$phonenumber){
global $con;
$genname=time().$agent_id;
$sql="update user_tb set name='$genname',username='$genname',password='$genname',user_status='5' where user_id=$user_id";
$result = mysqli_query($con, $sql);
$sql="update account_tb set Amount='20000' where user_id='$user_id'";
$result = mysqli_query($con, $sql);
$sql="INSERT INTO `demo_account_create`( `user_id`, `crt_date`, `agent_id`, `email`, `phonenumber`) VALUES ($user_id,now(),$agent_id,'$email','$phonenumber')";
$result = mysqli_query($con, $sql);
$sql="UPDATE `demo_pool` SET `demo_add_time`=now(),`demo_st`='not_available',expire_time=adddate(now(),+7) WHERE demo_id=$user_id";
$result = mysqli_query($con, $sql);
return $genname;
}


?>
