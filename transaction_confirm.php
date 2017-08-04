<?php
require_once 'header.php';
require_once 'functions.php';
$action = $_GET['action'];
$id = $_SESSION['id'];
$error='error';
if(isset($_POST['submit'])){
    
    $user = mysqli_real_escape_string($con, $_POST['user']);
    $credit_debit = mysqli_real_escape_string($con, $_POST['credit_debit']);
    $currency = mysqli_real_escape_string($con, $_POST['currency']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']); 
    $is_have_privilages = check_customer_privilaages($id,$user,$action);
}
//echo $user;
//echo $amount;
if(empty($user) && empty($currency) && empty($amount)){
    $error='Some field are empty!';
    header('location:transaction.php?action='.$action.'&error='.$error.'');
}

?>

<br/><br/><br/><br/><br/><br/>
                
                <div class="col-md-5 col-md-offset-3">
                    <form action="transaction_process.php?action=<?php echo $action; ?>" method="post">
                    <h1 align="center">Your Transaction summary!</h1>
                    <br/>
                    <br/>
                    
                    <table class="table">                    
                    <tr><td>Customer :</td><td><input type="hidden" name="user" value="<?php echo $user;?>"/><?php echo getnamebyid($user,$action);?></td></tr>
                    <tr><td>Credit/Debit :</td><td><input type="hidden" name="credit_debit" value="<?php echo $credit_debit;?>"/><?php echo $credit_debit;?></td></tr>
                    <tr><td>Currency Type :</td><td><input type="hidden" name="currency" value="<?php echo $currency;?>"/><?php echo $currency;?></td></tr>
                    <tr><td>Amount :</td><td><input type="hidden" name="amount" value="<?php echo $amount;?>"/><?php echo number_format($amount,2);?></td></tr>
                    
                    </table>
                                          
                <input type="submit" class="btn btn-primary btn-block" name="submit_sum" value="Confirm Your Transaction"/>
                <a href="transaction.php?action=<?php echo $action; ?>&user=<?php echo $user; ?>&credit_debit=<?php echo $credit_debit; ?>&currency=<?php echo $currency;?>&amount=<?php echo $amount;?>" class="btn btn-warning btn-block">Back</a>
                </form>  