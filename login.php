<?php
require_once ('includes/dbcon.php');
require_once('session.php');

$is_loggedin = check_login();


if($is_loggedin){	
redirect('index.php');
//header('location:index.php');
//	echo 'come here';
}
	else{
		
		
		
		}



$massage = '' ;

if (isset($_POST["submit"])) {
	
	$uname= mysqli_real_escape_string ($con, $_POST['uname']);
	$pword= mysqli_real_escape_string ($con, $_POST['pword']);
        
	$sql ="SELECT * FROM `agent` WHERE `agent_username` ='$uname' LIMIT 1";	
	$result= mysqli_query($con, $sql);	
	$value= mysqli_fetch_array($result);
//        echo '<pre>';
//        print_r($value);
//        echo '</pre>';
        $unm=$value['agent_username'];
        $pwd=$value['agent_password'];
        $ant_st=$value['agent_status'];
        $agent_name=$value['agent_name'];
        $id=$value['id'];
	$agent_level = $value['agent_level'];
	$parentid = $value['parentid'];
        $agent_code = $value['agent_code'];
        
	
		
		
		
        $existCount = mysqli_num_rows($result);
		

			
                if(($pwd==$pword)&&($existCount == 1)){
					
                if($ant_st==5){
				//echo $agent_level;
				//exit;
				login($agent_name,$id,$agent_level,$parentid,$agent_code,$_POST['uname']);
				  
					 } else {
					
					$massage = 'User Blocked !';
                                        
               // header('location:index.php?err=2');
           }
				}
						else {
							
							$massage = 'invalid Username or Password !';
                                                        
                                //  header('location:index.php?err=1');
                        }
						
						
				//echo $existCount;		
                }
              

?>
<?php require_once('header.php');?>

<div class="container">

<br />
<div class="col-sm-6 col-sm-offset-3">
<h2>Login</h2>

<hr />
<br />


                    <form  method="post"> 
                                           
                        <div class="form-group">
                        
                        <label for="uname">Username</label>  
                        <input type="text" name="uname" class="form-control" id="uname" placeholder="Enter your username" required="required"/>
                        </div> 
                        
                        <div class="form-group" style="margin-top: 30px;">
                            <label for="pword">Password</label> 
                            <input type="password" name="pword" class="form-control" id="password" placeholder="Enter your password" required="required"/>
                        </div>                       
                        
                        <p style="color:red; font-size:15px"><?php echo $massage; ?></p>
                        
                        <div id="reset" style="margin-top: 20px;"><a href="include/forget.php">Forgot your password ?</a></div>
                        
                        <br />
                        <div class="inp form-group" >
                            <input type="submit" name="submit" class="btn btn-block btn-info" value="LogIn"/>
                        </div>
                        
                    </form>

                
            
            <div style="height:140px;"></div>
            </div>
            
        </div>        
       
      
        
 <?php require_once('footer.php');?>
<script type="text/javascript">
    $(document).ready(function() {
     $(':input[type="submit"]').prop('disabled', true);
     $('#uname')&& $('#password').keyup(function() {
        if($(this).val() != '') {
           $(':input[type="submit"]').prop('disabled', false);
        }
     });
 });

</script>

</html>
