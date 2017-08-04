<?php
require_once 'header.php';
require_once 'functions.php';
require_once('balance.php');
require_once('democusbackend.php');

$agent_id = $_SESSION['id'];

if(isset($_POST['submit'])){
    
    $phone_nu = mysqli_real_escape_string($con, $_POST['phone_nu']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    $error = '';
    $successmsg = '';
    
    if(!isset($phone_nu) || strlen(trim($phone_nu)) <10){
        $error = "Please enter valid phone number ";
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error ="Please enter valid email address";
    }
    if((checkalready($email,$phone_nu))==0){
        $error = "Email or phone number already exsit";
    }
    
    if(empty($error)){
        $successmsg= createagdemo($agent_id,$email,$phone_nu);
    } 
        
}

$sql ="SELECT `demo_account_create`.`id`,`demo_account_create`.`email`,`demo_account_create`.`phonenumber`,`user_tb`.`username`,`user_tb`.`password` FROM `user_tb` INNER JOIN `demo_account_create` ON `demo_account_create`.`user_id`= `user_tb`.`user_id`WHERE `demo_account_create`.`agent_id`=$agent_id";
$query = mysqli_query($con, $sql);

$data = array();

while ($value = mysqli_fetch_assoc($query)){
    
    $data[] = $value;
    
}

$data = json_encode($data);

?>
<br/><br/><br/>
<div class="container">
    <div class="col-sm-8 col-sm-offset-2">
        <h2>Demo Customer Create</h2>
        <p style="color: green;"><?php if($successmsg){echo 'Account created successfull <br/> <span style="color: blue;" > your username and password is</span> &nbsp;<span style="color: red;" >'.$successmsg.'</span>';}?></p>
        <hr/>
        <form action="" method="post">
            <div class="form-group">
                <label for="Pnone-number">Phone Number:</label>
                <a style="float: right; margin:0 10px 5px 0;" class="btn btn-primary " href="index.php">Go back</a>
                <input type="text" name="phone_nu" class="form-control"/>
            </div>
            <div class="form-group">
                <label for="Email">Email:</label>
                <input type="email" name="email" class="form-control"/>
            </div>
            <p style="color: red;"><?php if($error){echo $error; }?></p>
            <input type="submit" name="submit" value="Submit" class="btn btn-success"/>
        </form>
    </div>
    <div class="col-sm-8 col-sm-offset-2"><br/><br/><br/></div>
    <div class="col-sm-8 col-sm-offset-2" id="show_table"  style="height: 500px; overflow: auto;"></div>
</div>

<?php require_once 'footer.php'; ?>

<script type="text/javascript">
    
    
var data_table = '<table class="table table-bordered table-striped table-responsive"><tr><th>Username</th><th>Password</th><th>Phone number</th><th>Email</th></tr>';
var data_array = <?php echo $data ?>;

//console.log(data_array);
$.each(data_array, function( index, value ){
    
    data_table = data_table+'<tr><td>'+data_array[index]["username"]+'</td><td ><div id="enctype'+data_array[index]["id"]+'"><input type="password" class="form-control" style="border: none;"  readonly=""  value="'+data_array[index]["password"]+'"/><button class="btn"  onclick="showinput('+data_array[index]["id"]+')"><img src="image/icon.png"/></button></div>   <div style="display: none;" id="showtype'+data_array[index]["id"]+'"><input type="text"  class="form-control" style="border: none;" readonly="" value="'+data_array[index]["password"]+'"/><button class="btn"  onclick="hideinput('+data_array[index]["id"]+')"><img src="image/icon.png"/></button></div></td><td>'+data_array[index]["phonenumber"]+'</td><td>'+data_array[index]["email"]+'</td></tr>';
    
//    console.log(data_array[index]["id"]);
    
});
    
$('#show_table').html(data_table+'</table>');


function showinput(test){
        
 $("#showtype"+test+"").css("display", "block");
        $("#enctype"+test+"").css("display", "none");
      
  
}

function hideinput(testh){    
        
    $("#enctype"+testh+"").css("display", "block");
    $("#showtype"+testh+"").css("display", "none");
    
    
}


</script>
</html>

