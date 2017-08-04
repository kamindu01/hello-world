<?php 
require_once 'includes/dbcon.php';

$tag = $_POST['tag'];
$agent = $_POST['user'];

if($tag=='customer'){

	select_customer($agent);
}
else if($tag=='agent'){

	select_agent($agent);
}
else if($tag=='super'){

	select_super($agent);
}

else if($tag=='first'){

	get_first($agent);
}

else if($tag=='get_username'){

	select_user($agent);
}



function select_customer($agent){

$result_arr = array();
	global $con;

 $sql = "SELECT * FROM `user_tb` WHERE `user_id` in (SELECT `cus_id` FROM `agent_cus` WHERE `agent_id` = $agent)";

$result = $con->query($sql);

	if ($result->num_rows > 0){

		    while($row = mysqli_fetch_assoc($result)){

		      
		      $result_arr[] =  $row; 
		        
		    }

	     
	}

	echo json_encode($result_arr); 

}


function select_agent($agent){

$result_arr = array();
	global $con;

 $sql = "SELECT * FROM `agent` WHERE `parentid` = $agent and agent_level=3";

$result = $con->query($sql);

	if ($result->num_rows > 0){

		    while($row = mysqli_fetch_assoc($result)){

		      
		      $result_arr[] =  $row; 
		        
		    }

	     
	}

	echo json_encode($result_arr); 

}

function select_super($agent){

$result_arr = array();
	global $con;

 $sql = "SELECT * FROM `agent` WHERE `parentid` = $agent and agent_level=2";

$result = $con->query($sql);

	if ($result->num_rows > 0){

		    while($row = mysqli_fetch_assoc($result)){

		      
		      $result_arr[] =  $row; 
		        
		    }

	     
	}

	echo json_encode($result_arr); 

}

function get_first($agent){

	$result_arr;
	global $con;

 $sql = "SELECT * FROM `agent` WHERE `id` = $agent ";

$result = $con->query($sql);
		    $row = mysqli_fetch_assoc($result);
		  $result_arr =  $row; 


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