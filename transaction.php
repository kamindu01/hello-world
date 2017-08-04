<?php 

require_once ('functions.php');
$action = $_GET['action'];


if($action=='customer'){
    if(isset($_GET['customer-select'])&& (isset($_GET['cus-amount']))){
    $customer_select = $_GET['customer-select'];
    $cus_amount = $_GET['cus-amount'];
    
        if($customer_select==''||$customer_select==null){
            
            redirect('index.php');
        }
    }
   
}else {
    if(isset($_GET['agent-select'])&& (isset($_GET['agent-amount']))){
        $agent_select = $_GET['agent-select'];
        $agent_amount = $_GET['agent-amount'];
        
             if($agent_select==''||$agent_select==null){
            
            redirect('index.php');
        }
        
    }
}
if($action=='customer'){
    if(isset($_GET['customer-select'])){
    $customer_select = $_GET['customer-select'];
    
    }
}else {
    if(isset($_GET['agent-select'])){
        $agent_select = $_GET['agent-select'];
        
        
    }
}

//echo $agent_select;
//echo $agent_amount;
//$tag = $_POST['tag'];
//echo $tag;
?>
<?php 
require_once('header.php');
require_once('functions.php');
require_once('balance.php');
//require_once('includes/dbcon.php');
require_once('transaction_process.php');
is_loggedin();
?>
<?php
//print_r($_SESSION);
$agent_level = $_SESSION['level'];
$agent_id = $_SESSION['id'];
$parentid = $_SESSION['parentid'];
$name ='agent_username';
$id = 'id';




//echo $agent_level;
//$sql = "SELECT * FROM `agent` WHERE `parentid`='$agent_level'";
//$resultset= mysqli_query($con, $sql);
//$value = mysqli_fetch_assoc($resultset);
//echo '<pre>';
//print_r($value);
//echo $agent_id;
//echo $action;
if($parentid == 23){
    if($action=='customer'){
        redirect('index.php');
    }
} else {

        if($action=='customer'){    
            $name = 'username';
            $id = 'user_id'; 
            $sql = "SELECT * FROM `user_tb` WHERE `user_id` in (SELECT `cus_id`FROM `agent_cus` WHERE `agent_id` = '$agent_id');";

            //echo $sql;
        } else {
            if ($action == 'agent' && $_SESSION['level']>2){    
                        redirect('index.php');
            }
             $sql = "SELECT * FROM `agent` WHERE `parentid`='$agent_id'";

        //        $value = mysqli_fetch_assoc($resultset);
        //        $uname = $value['agent_username'];
        }
        $resultset= mysqli_query($con, $sql);
}

/*if (mysqli_num_rows($resultset) > 0) {    
   //echo 'come here';    
    while ($row = mysqli_fetch_assoc($resultset)) {        
       echo $row['id'];         
    }
}*/

$sql5 = "SELECT `country`,`rate` FROM `currency_rate`";
$query = mysqli_query($con, $sql5);

$currency_converter = array();

while ($row = mysqli_fetch_assoc($query)){
     $currency_converter[$row['country']]= $row['rate'];
 
}

 //print_r($currency_converter);
    
$currency = json_encode($currency_converter);

//==========get back in creditdebit data========
$credit = '';
$debit = '';



?>
<div class="container">
    <div class="col-sm-8 col-sm-offset-2">

<h2><?php echo ucfirst($action).' Transaction'; ?></h2>

<hr />
<?php
if(isset($_GET['action']) && isset($_GET['error'])){
    echo '<div class="alert alert-danger">
          <strong>Error!</strong> '.$_GET['error'].'
          </div>';
} //else {
if(isset($_GET['action']) && isset($_GET['success'])){
    echo '<div class="alert alert-success">
          <strong>Success!</strong> Transaction successfull.
          </div>';
    echo '<script type="text/javascript">
            setTimeout(function(){ location.href = "index.php" }, 2500);
            </script>';
} 
//if(isset($_GET['currency'])&& isset($_GET['amount'])){
//    

?>
<form action="transaction_confirm.php?action=<?php echo $action; ?>" method="post">

    
<div class="form-group">
    <label for="user">Select <?php echo ucfirst($action) ?></label><a href = "view_user.php?action=<?php echo $action;?>" class="btn btn-danger " style="float:right; margin-bottom:10px">View</a>
    <a style="float: right; margin-right: 10px;" class="btn btn-primary " href="index.php">Go back</a>
<select class="form-control" name="user" id="user" required="" >
    <?php 
    
//   echo $customer_select;
    if(isset($_GET['user'])){
        $user= $_GET['user'];
        //echo $user;
        if($action == 'agent'){
            $usr_name = getnamebyid($user,'agent');
            //echo $usr_name;
            echo '<option value="'.$user.'">'.$usr_name.'</option>';
        } else {
            $usr_name = getnamebyid($user,'customer');
            //echo $usr_name;
            echo '<option value="'.$user.'">'.$usr_name.'</option>';
        }
    }


   
            if($action=='customer'){

                $cus_name = getnamebyid($customer_select,'customer');
                
               
                


                        if(isset($_GET['customer-select'])){
                            echo '<option value="'.$customer_select.'">'.$cus_name.'</option>';
                        }
                    } else {

                        $agent_name = getnamebyid($agent_select,'agent');



                        if(isset($_GET['agent-select'])){
                            echo '<option value="'.$agent_select.'">'.$agent_name.'</option>';
                        }
                    }
?>
    <option value="">Please select &nbsp;<?php echo $action; ?></option>
            <?php while ($row = mysqli_fetch_assoc($resultset)) {?>    
            <option value="<?php echo $row[''.$id.''];?> "><?php  echo $row[''.$name.''];?> </option>
<?php    }?>

</select> 

</div>
<?php   

if(isset($_GET['credit_debit'])){
    $credit_debit = $_GET['credit_debit'];
    if($credit_debit == 'credit'){
        echo '<div class="radio">
                <label><input type="radio" value="credit" checked="" name="credit_debit" >Credit</label>
                </div>';
        echo '<div class="radio">
                <label><input type="radio" value="debit" name="credit_debit" >Debit</label>
                </div>';
    } else {
        echo '<div class="radio">
                <label><input type="radio" value="credit"  name="credit_debit" >Credit</label>
                </div>';
        echo '<div class="radio">
                <label><input type="radio" value="debit" checked="" name="credit_debit" >Debit</label>
                </div>';
        

    }
} else {
    echo ' <div class="form-group">

        <label for="credit_debit">Credit / Debit </label>
        <div class="radio">
            <label><input type="radio" value="credit" id="credit" name="credit_debit" checked="">Credit</label>
        </div>
        <div class="radio">
            <label><input type="radio" value="debit" id="debit" name="credit_debit" >Debit</label>
        </div>

        </div>';
}
       
 ?>   
<div class="form-group">
<label for="currency">Currency Type</label>

<select class="form-control" name="currency" id="currency" required="">
<?php
    if(isset($_GET['currency'])){
        $currency1= $_GET['currency'];
        //echo $user;
        echo '<option value="'.$currency1.'">'.$currency1.'</option>';
        
    }
?>
    <option value="">Please select currency type</option>
    <option value="LKR">LKR</option>
    <option value="USD">USD</option>    
    <option value="INR">INR</option>

</select> 

</div>

<div class="form-group">
<label for="amount">Amount <span style="color:red">*</span></label>

<input type="text" name="amount" id="amount" class="form-control" <?php if($action=='customer'){
                                                                                if(isset($_GET['cus-amount'])){
                                                                                        echo 'value="'.$cus_amount.'"';
                                                                                    }
                                                                            } else {
                                                                                if(isset($_GET['agent-amount'])){
                                                                                        echo 'value="'.$agent_amount.'"';
                                                                                }
                                                                            } 
                                                                            if(isset($_GET['amount'])){
                                                                                $amount = $_GET['amount'];
                                                                                echo 'value="'.$amount.'"';
                                                                            }
                                                                            
                                                                            
                                                                    ?>  required=""/> 

</div>

    

<div><label for="LKR Convert Amount">LKR Amount :</label><span style="color: red;" id="lkrconvert"></span></div>
<br/>
<input type="submit" class="btn btn-primary" name="submit" value="Submit" />

</form>

<br />
<br />
<br />
<br />

</div>
</div>
<?php require_once('footer.php'); ?>
<script type="text/javascript">
    
    var cur = [];
    
    cur = <?php echo $currency; ?>;
  
   
   //console.log(cur);
    
    var currency;
    var  amount;
    var amt;
    var str;
  
    $("#amount").keyup(function get_currency(){
        currency = $('#currency').val();
        amount = $('#amount').val(); 
        
       cal_currency(amount,currency);

    });
    
   $("#currency").change(function() {


       currency = $('#currency').val();   
          amount = $('#amount').val(); 

       cal_currency(amount,currency);

    });
    

   function cal_currency(amount,currency){
   
       str = numeral(amount*cur[currency]).format('0,0.00');
       $("#lkrconvert").html(str+'LKR'); 

   }     
        
    

</script>
</html>