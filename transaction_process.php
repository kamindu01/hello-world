<?php 
$action = $_GET['action'];
?>
<?php
require_once 'includes/dbcon.php';
require_once('session.php');
//require_once('functions.php');
$id = $_SESSION['id'];
$balance = get_amount($id);

//$sql ="SELECT * FROM `agent` WHERE `id`='$id'";
//$result = mysqli_query($con, $sql);
//$value = mysqli_fetch_assoc($result);
////print_r($value);
//$available_amount = $value['amount'] ;
//echo $id;
//echo $balance;
//print_r($id);
$massage='success';
$error='error';

//mysqli_autocommit($con,FALSE);

if(isset($_POST['submit_sum'])){
    
    $user = mysqli_real_escape_string($con, $_POST['user']);
    $credit_debit = mysqli_real_escape_string($con, $_POST['credit_debit']);
    $currency = mysqli_real_escape_string($con, $_POST['currency']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']); 
    $is_have_privilages = check_customer_privilaages($id,$user,$action);
    $convert_amount = check_currency_rate($currency,$amount);
//    echo $user;
//    echo $credit_debit;
//    echo $currency;
//    echo $amount;
//     print_r($is_have_privilages);
    $sql3 ="SELECT `amount` FROM `agent` WHERE `id`=$user";
    $query = mysqli_query($con, $sql3);
    $value= mysqli_fetch_assoc($query);
    $agent_amount = $value['amount'];
//    print_r($value);
    if($balance<$amount){
        
        $error='not enough Credit balance!';
        header('location:transaction.php?action='.$action.'&error='.$error.'');
    }
    
    else if(!$is_have_privilages ){
        
       $error='Transaction not allowed!';
       header('location:transaction.php?action='.$action.'&error='.$error.''); 
    }
    
    else {
        if($action == 'customer'){
            
                        
             
                //      $sql1 ="UPDATE `agent` SET `amount`= `amount` - '$amount' WHERE `id`='$id'";
//                        $sql ="UPDATE `account_tb` SET `Amount`=`Amount` + '$convert_amount' WHERE `user_id`='$user';";                                     

      
                        $sql = "INSERT INTO `agent_treasury_cus`(`id`, `cus_id`, `cr_db`, `ap_id`, `cash_id`, `ad_time`, `amount`, `status`) VALUES ('', '$user', '$credit_debit', '', '$id', NOW(),'$convert_amount', 'pending');"; 
                        
//                        echo $sql;
                        $sql .= "INSERT INTO `approval_table_customer`(`id`, `edited_user`, `cus_id`, `amount`, `date_time`, `type_transaction`, `agent_tr_id`) VALUES('', '$id', '$user', '$convert_amount',NOW(), '$credit_debit', LAST_INSERT_ID())";                        
                        
//                        print_r($query);            
//                        print_r($query1);            
                        if(mysqli_multi_query($con, $sql)){
                                                        
                            header('location:transaction.php?action='.$action.'&success='.$massage.'');
                        }else { 
                            
                            $error = 'Database error';
                            header('location:transaction.php?action='.$action.'&error='.$error.'');
                        }
                        
                            
                            
                        
    }//agent 
    
    
    else {
        
        
        
//            $conn2 = mysqli_connect("$dbhost","$username","","$dbname");
            $sub_agent_bal = 0;
            
            if($credit_debit == 'debit'){
                $dbtamt = '-';
                $sub_agent_bal = $agent_amount - $amount;         
            } else {
                $sub_agent_bal = $agent_amount + $amount;
            }
            
            $final_amount = intval($dbtamt.$convert_amount);
            

            $sql1 ="UPDATE `agent` SET `amount`= `amount` - '$convert_amount' WHERE `id`='$id';";              
            
                       
            $sql1 .="UPDATE `agent` SET `amount` = `amount` + '$convert_amount' WHERE `id`='$user';";                   
                    
         
            $sql1 .="INSERT INTO `agent_transaction`(`id`, `super_agent`, `sub_agent`, `amount`, `datetim`, `sub_agent_bal`)VALUES ('','$id','$user',$final_amount,NOW(),$sub_agent_bal)";           
            
            if(mysqli_multi_query($con, $sql1)){
//                echo 'hear';                
                header('location:transaction.php?action='.$action.'&success='.$massage.'');
            }else {
                $error = 'Database error';
                header('location:transaction.php?action='.$action.'&error='.$error.'');
            }
            
                            
                       

            }
        }
    }

    






//require_once 'footer.php';
?>

