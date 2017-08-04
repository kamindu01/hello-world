<?php
require_once 'includes/dbcon.php';

$tag = $_POST['tag'];
$agent = $_POST['user'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

$start = date('Y-m-d', strtotime(str_replace('-', '/', $start_date))). ' ' .$start_time.':00';
$end = date('Y-m-d', strtotime(str_replace('-', '/', $end_date))). ' ' .$end_time.':00';

if($tag=='customer'){

	select_customer($agent,$start,$end);
}
else if($tag=='agent'){

	select_agent($agent,$start,$end);
}
else if($tag=='super'){

	select_super($agent,$start,$end);
}

else if($tag=='first'){

	get_first($agent,$start,$end);
}

else if($tag=='get_username'){

	select_user($agent);
}

function select_customer($agent,$start,$end){

$result_arr = array();
	global $con;

// $sql = "SELECT * FROM `user_tb` WHERE `user_id` in (SELECT `cus_id` FROM `agent_cus` WHERE `agent_id` = $agent)";
   $sql ="SELECT `agent_commision`.`date_time`,`agent_commision`.`lost_win`,`user_tb`.`username`,`user_tb`.`m_id` FROM `agent_commision` RIGHT JOIN `agent_cus` ON `agent_cus`.`cus_id`=`agent_commision`.`user_id` RIGHT JOIN `user_tb` ON `user_tb`.`user_id`=`agent_commision`.`user_id` WHERE `agent_cus`.`agent_id`=$agent AND `agent_commision`.`date_time` BETWEEN '$start' AND '$end'";

$result = $con->query($sql);

	if ($result->num_rows > 0){

		    while($row = mysqli_fetch_assoc($result)){

		      
		      $result_arr[] =  $row; 
		        
		    }     
	}

	echo json_encode($result_arr); 

}


function select_agent($agent,$start,$end){

global $con;
$result_arr = array();	
$index = 0;
 $sql = "SELECT * FROM `agent` WHERE `parentid` = $agent and agent_level=3";

$result = $con->query($sql);

	if ($result->num_rows > 0){

		    while($row = mysqli_fetch_assoc($result)){
  
		      $result_arr[$index]['data'] =  $row; 
				$result_arr[$index]['lostwin'] =  get_lost_win($row['id'],$start,$end,'agent'); 

			  $index++;
		        
		    }

	     
	}

	echo json_encode($result_arr); 

}

function get_lost_win($agent,$start,$end,$who){

	global $con;

if($who == 'agent'){$sql = "SELECT sum(`lost_win`) as `lost_win` FROM `agent_commision` where `user_id` in (SELECT `cus_id` FROM `agent_cus` WHERE `agent_id` = '$agent') AND `agent_commision`.`date_time` BETWEEN '$start' AND '$end'";

}

else{

	$sql = "SELECT sum(`lost_win`) as `lost_win` FROM `agent_commision` where `user_id` in (SELECT `cus_id` FROM `agent_cus` WHERE `agent_id` in(SELECT `id` FROM `agent` WHERE `parentid` = '$agent'  or `id` = '$agent')) AND `agent_commision`.`date_time` BETWEEN '$start' AND '$end'";
}
	
	$result = $con->query($sql);
	if ($result->num_rows > 0){

		$row = mysqli_fetch_assoc($result);
		return ($row['lost_win']);

	}

}


function select_super($agent,$start,$end){

$result_arr = array();
	global $con;
$index = 0;
 $sql = "SELECT * FROM `agent` WHERE `parentid` = $agent and agent_level=2";

$result = $con->query($sql);

	if ($result->num_rows > 0){

		    while($row = mysqli_fetch_assoc($result)){

		       $result_arr[$index]['data'] =  $row; 
				$result_arr[$index]['lostwin'] =  get_lost_win($row['id'],$start,$end,'super'); 

			  $index++;
		        
		        
		    }

	     
	}

	echo json_encode($result_arr); 

}

function get_first($agent,$start,$end){

	$result_arr;
	global $con;

 $sql = "SELECT * FROM `agent` WHERE `id` = $agent ";

$result = $con->query($sql);
		    $row = mysqli_fetch_assoc($result);
		  $result_arr['data'] =  $row; 
		  $result_arr['lostwin'] =  get_lost_win($agent,$start,$end,'super');; 


	echo json_encode($result_arr); 

}

function select_user($agent){

	global $con;
		$sql = "SELECT  `agent_username` FROM `agent` WHERE `id` = $agent";
		$result = $con->query($sql);
		    $row = mysqli_fetch_assoc($result);

			echo json_encode($row); 

}


?>
