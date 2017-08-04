<?php
require_once 'includes/dbcon.php';
require_once 'header.php';
require_once 'functions.php';
require_once('balance.php');
$action = $_GET['action'];
$id = $_SESSION['id'];
$customerid = $_GET['customerid'];
$success = '';
$error = '';

$sql = "SELECT * FROM `user_tb` WHERE `user_id`='$customerid'";
$query = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($query);
//print_r($row);
$username = $row['username'];
$memberid = $row['m_id'];




if(isset($_POST['submit'])){
        
        $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
        $var_password = mysqli_real_escape_string($con, $_POST['var_password']);
        $is_have_privilages = check_customer_privilaages($id,$customerid,$action);
        
//        echo $old_password;
//        echo $new_password;
//        echo $var_password;
//    print_r($is_have_privilages);
        if($is_have_privilages){
                               
                                if($new_password == $var_password){
                                    $sql="UPDATE `user_tb` SET `password`='$new_password' WHERE `user_id`='$customerid'";
                                    $result= mysqli_query($con, $sql);
                                    if($result==TRUE){
                                        $success = 'Password changed successfully';
            //                            echo $success;    
                                    }

                                } else {
                                    $error = 'New password and varify password do not match';
            //                        echo $error;
            //                        
                                }
                   


            } else {
                $error = 'Not privilaage to selected user';
            }
}
?>

<div class="container">
    <div class="col-sm-8 col-sm-offset-2">
        <br/><br/>
    <h2>Change password</h2>
    <hr/>
    <p style="font-size: 16px; color: blue;"><?php  echo ucfirst($action);?>&nbsp; Username :<?php echo $username;?></p>
    <p style="font-size: 16px; color: blue;"><?php  echo ucfirst($action);?>&nbsp; Member Id :<?php echo $memberid;?></p>
    
    <?php
    if($success || $error){
        if (!empty($success)){
            echo '<div class="alert alert-success">
                    <strong>Success!</strong> '.$success.'
                    </div>';
        } else {
            if(!empty($error)){
            echo '<div class="alert alert-danger">
                    <strong>Error!</strong> '.$error.'
                    </div>';
            }
        }
    }
    ?>   
     <a style="float: right; margin-bottom: 5px;" class="btn btn-primary " href="view_user.php?action=<?php echo $action; ?>">Go back</a>
     
    <form action="change_password.php?<?php echo 'action='.$action.'&customerid='.$customerid.''; ?>" method="post" >       
        <div class="form-group">
            <label for="New-password">New Password</label>
            <input type="password" class="form-control" name="new_password" placeholder="Enter new password" required=""/>
        </div>
        <div class="form-group">
            <label for="Varify-password">Varify Password</label>
            <input type="password" class="form-control" name="var_password" placeholder="Varify your password" required=""/>
        </div>
        <input type="submit" name="submit" class="btn btn-primary" value="Submit"/>
        <input type="reset" class="btn btn-warning" value="Reset"/>
    </form>
</div>
</div>
<br/><br/><br/><br/><br/><br/><br/><br/><br/>
<?php  
require_once 'footer.php';
?>
</html>

