<?php 

require_once ('includes/dbcon.php');
require_once('session.php');
//print_r($_SESSION);

function is_loggedin(){
$is_loggedin = check_login();

if($is_loggedin){	
//	echo 'come here';
}
	else{
		
		redirect('login.php');
		//header('location:login.php');
		
		}
		
}

function redirect($url = 'login.php'){
	
	header('location:'.$url.'');
	
	}
	
	function get_session($value){
		
		return $_SESSION[''.$value.''];
		
		
		}
                

  function get_amount($agent_id){
      
      global $con;
      $sql2 = "SELECT (amount-(SELECT COALESCE(sum(amount),0) FROM `agent_treasury_cus` WHERE `cash_id` = '$agent_id' and `status` = 'pending')) as `amount` FROM `agent` WHERE `id` = '$agent_id'";

//echo $sql2;
$resultset2 = mysqli_query($con, $sql2);
$value2 = mysqli_fetch_assoc($resultset2);
//print_r($value2);
$balance = $value2['amount'];
//$_SESSION['balance']= $balance;
      
   return $balance;   
  }   
  
  function getnamebyid($id,$action){
      
      global $con;
      
      $table = 'user_tb';
      $nme = 'username';
      $usrid = 'user_id';
      
      if($action=='agent'){
          
        $table = 'agent';
      $nme = 'agent_name'; 
      $usrid = 'id';
      }
      
      
      $sql = "SELECT `$nme` FROM `$table` WHERE `$usrid` = '$id'";
      
      $result = mysqli_query($con, $sql);
              
          $value = mysqli_fetch_assoc($result);
          
          if (mysqli_num_rows($result) > 0) {
              
             return  $value[$nme];  
              
          }
          
          else{
              
              
              return 'no data';
          }
        
          
     
      
      
  }
  
    
    
  function check_customer_privilaages($agent,$customer,$action){
      
      
      $have_privilages = 0;
      global $con;
     // global $have_privilages;
    
      $id = 'cus_id';
      
      $sql = "SELECT `cus_id` FROM `agent_cus` WHERE `agent_id` = $agent"; 
       
      if($action=='agent'){
        
       $id = 'id';
       $sql = "SELECT `id` FROM `agent` WHERE `parentid` = $agent";   
          
      }
      
       $result = mysqli_query($con, $sql);
   
          if (mysqli_num_rows($result) > 0) {
         
              while($value = mysqli_fetch_assoc($result)){
                  
            //  return  $value['cus_id'].$customer;
              
           if($value[$id] == intval($customer)){
                    
                 $have_privilages=1;
                  
            } 
               
              
              }
 
          }
          
          else{
              
              $have_privilages = 0;
          }
    
          return $have_privilages ;
      
  }
  function check_currency_rate($currency,$amount){
      global $con;
   
        $sql6="SELECT `country`,`rate` FROM `currency_rate` WHERE `country`='$currency'";
        $resaltset2= mysqli_query($con, $sql6);
        $value = mysqli_fetch_assoc($resaltset2);
        $country = $value['country'];
        $rate = $value['rate'];
        
        if($country='USD'){
            $new_amount =$amount * $rate;
            
            
        }elseif ($country='INR') {
            $new_amount =$amount * $rate;
        } else {
            $new_amount =$amount * $rate;
        }
        return $new_amount;
      
  }
//  echo check_currency_rate(USD,100);

  function dayenddetail($startdate,$enddate,$agent_id){
		global $con;
                $startli=substr($startdate,0,10);
                $endli=substr($enddate,0,10);
                $data_arr=array();
                  $sql="SELECT * FROM dayend_acc where date_time like '$startli%' and `user_id` in (SELECT `cus_id` FROM agent_cus where agent_id in (SELECT `id` FROM agent where `parentid` = '$agent_id' or id='$agent_id'))";
        $sql1="SELECT * FROM dayend_acc where date_time like '$endli%' and `user_id` in (SELECT `cus_id` FROM agent_cus where agent_id in (SELECT `id` FROM agent where `parentid` = '$agent_id' or id='$agent_id'))";
//                $result = sendquery($sql);
		 $result = mysqli_query($con, $sql);

         while($row = mysqli_fetch_assoc($result)){
                  $user_id=$row['user_id'];
                 $user_id="ac$user_id";

                         $data_arr[$user_id][0]="ok";
                         $data_arr[$user_id][1]=$row['acc_bal'];

                 }

// $result = sendquery($sql1);
 $result = mysqli_query($con, $sql1);

         while($row = mysqli_fetch_assoc($result)){
                  $user_id=$row['user_id'];
                 $user_id="ac$user_id";

                         $data_arr[$user_id][0]="ok";
                         $data_arr[$user_id][2]=$row['acc_bal'];

                 }

                $sql="SELECT * FROM treasury_cus where (ad_time between '$startdate' and '$enddate') and `cus_id` in (SELECT `cus_id` FROM `agent_cus` WHERE `agent_id` in (SELECT `id` FROM agent where `parentid` = '$agent_id' or id='$agent_id'))";
//                 $result = sendquery($sql);
		 $result = mysqli_query($con, $sql);

         while($row = mysqli_fetch_assoc($result)){
                  $user_id=$row['cus_id'];
                  $user_id="ac$user_id";
                 $cr_type=$row['cr_db'];
                 if($cr_type=='credit'){
                          $data_arr[$user_id][3]= $data_arr[$user_id][3]+$row['amount'];
                         }
                         else{
                                  $data_arr[$user_id][4]= $data_arr[$user_id][4]+$row['amount'];
                                 }
                 }
                 return  $data_arr;
                }


  
		function current_start_date(){
		 global $con;
                    $sql="SELECT `date_time` FROM `agent_av_com` order by `date_time` desc  limit 1";
                    $result= mysqli_query($con, $sql);
			$lastdate=mysqli_fetch_assoc($result);
			return $lastdate['date_time'];

		}
                function start_date(){
                    global $con;                   
                    $sql="SELECT DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                    $query = mysqli_query($con, $sql);
                    list($date)= mysqli_fetch_assoc($query);
                    return $date;
                }
                function end_date(){
                     global $con;                   
                    $sql="SELECT CURDATE() as datetim  FROM `dayend_acc`";
                    $query = mysqli_query($con, $sql);
                    $date= mysqli_fetch_array($query);
                    return $date['datetim'];
                } 
                
                function get_commision($agent_id){
                    
                    global $con; 
                    $startdate;
                    $enddate;
                    
                    $sql1= "SELECT  `date_time` FROM `agent_commision` order by `date_time` desc limit 1";
                    $query1 = mysqli_query($con, $sql1);
                    list($startdate)= mysqli_fetch_array($query1);
                    
                    $sql2="SELECT CURDATE() FROM `agent_commision`";
                    $query2 = mysqli_query($con, $sql2);
                    list($enddate)= mysqli_fetch_array($query2);
                    $enddate = $enddate.' 08:00:00';
                    
                    $sql3 = "SELECT `user_id` , SUM(`lost_win`)*25/100 as `lostwin` FROM `agent_commision` WHERE `date_time` BETWEEN '$startdate;' AND '$enddate' and `user_id` in (SELECT `id` FROM `agent` WHERE `parentid` = '$agent_id')";
                   $query3 = mysqli_query($con, $sql3);
                   $commision_arr = array() ;
                   
                   while($row = mysqli_fetch_assoc($query3)){
                       
                       $commision_arr[] = $row;
                   }
                           
                          
return $commision_arr.$sql3;
                     
                    
                }


function getcommchart($agent_id){
global $con;
$comcahrtarray=array();
$sql="SELECT * FROM `agent_av_com` where `agent_id`=$agent_id order by `date_time` desc limit 5;";
$query = mysqli_query($con, $sql);
 while($row = mysqli_fetch_assoc($query)){
$date_time=$row['date_time'];
$amont=$row['amount']+0;
$comcahrtarray[$date_time]=$amont;
//return json_encode($comcahrtarray);
}
return json_encode($comcahrtarray);
}
function cus_details($agent_id){
global $con;
$cus_data=array();
$sql="SELECT a.name, a.username, a.user_id, a.m_id, b.agent_id, c.agent_username
FROM user_tb a, agent_cus b, agent c
WHERE b.cus_id
IN (

SELECT cus_id
FROM agent_cus
WHERE agent_id
IN (

SELECT id
FROM agent
WHERE id =$agent_id
OR parentid =$agent_id
)
)
AND b.cus_id = a.user_id
AND b.agent_id = c.id";
$query = mysqli_query($con, $sql);
 while($row = mysqli_fetch_assoc($query)){
$user_id='c'.$row['user_id'];
$cus_data[$user_id][0]=$row['username'];
$cus_data[$user_id][1]=$row['m_id'];
$cus_data[$user_id][2]=$row['agent_id'];
$cus_data[$user_id][3]=$row['agent_username'];

}
return $cus_data;
}
?>
