<?php 

require_once ('includes/dbcon.php');
require_once('functions.php');

 session_start();
 

function check_login(){
	
	if(!isset($_SESSION['agent_name'])){
		
		return false;
		
		}
		
		else{
			
			return true;
			
			}
	
	}
	function login($agent_name,$id,$agent_level,$parentid,$agent_code,$username){
		
		$_SESSION['agent_name']= $agent_name;              
                $_SESSION['id'] = $id;
		$_SESSION['level'] = $agent_level;
		$_SESSION['parentid'] = $parentid;
		$_SESSION['agent_code'] = $agent_code;
		$_SESSION['username'] = $username;                
			    
				header('location:index.php');
		}
		
		function log_out(){
                    global $con;
                    $sql2 ="UPDATE agent SET ex_time= NOW()";
                    $sql2 .= "WHERE id = '{$_SESSION['id']}'";

                    $result2= mysqli_query($con, $sql2);

//                    echo $result2;

                    $_SESSION = array();
//
//                    if(isset($_COOKIE[session_name()])){
//                        setcookie(session_name(), '', time()-86400, '/');
//                    }
			

			session_destroy();
			
	  redirect('login.php');
				//header('location:login.php');
			
			}



?>