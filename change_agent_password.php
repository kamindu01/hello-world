<?php 
require_once 'header.php';
require_once 'functions.php';
require_once 'includes/dbcon.php';
require_once('balance.php');
$agent_id = $_SESSION['id'];

$massage = '';


$sql = "SELECT * FROM `agent` WHERE `id` = '$agent_id' ";
$query = mysqli_query($con, $sql);
//echo $sql;
$row = mysqli_fetch_assoc($query);
$dbpasswordinagent = $row['agent_password'];
//print_r($dbpasswordinagent);

if(isset($_POST['submit'])){
        $old_password = mysqli_real_escape_string($con, $_POST['old_password']);
        $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
        $var_password = mysqli_real_escape_string($con, $_POST['var_password']);


        if($dbpasswordinagent == $old_password){
            if($new_password == $var_password){
                $sql1 = "UPDATE `agent` SET `agent_password`='$new_password' WHERE `id` = '$agent_id'";
                $query1 = mysqli_query($con, $sql1);
                $massage = 'Password changed successfully';
                echo '<script type="text/javascript">
                        setTimeout(function(){ location.href = "logout.php" }, 2000);
                        </script>';
            } else {
                $massage = 'New password and varify password do not match';
            }
        } else {
            $massage = 'Old password is incorrect';
        }

}

?>


<div class="container">
    <div class="col-sm-8 col-sm-offset-2">
    <h3>Change Agent password</h3>
    <hr/>
    <?php 
    if ($massage){
        echo '<p style="color:red;">'.$massage.'</p>';
    }
    ?>
    <a style="float: right; margin-bottom: 5px;" class="btn btn-primary " href="index.php">Go back</a>
     <form action="change_agent_password.php?massage=massage" method="post" >
        <div class="form-group">           
            <label for="old-password">Old Password</label>           
            <input type="password" class="form-control" name="old_password" placeholder="Enter old password" required=""/>
        </div>
        <div class="form-group">
            <label for="old-password">New Password</label>
            <input type="password" class="form-control" name="new_password" placeholder="Enter new password" required=""/>
        </div>
        <div class="form-group">
            <label for="old-password">Varify Password</label>
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
