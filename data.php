<?php

//header('Content-Type: application/json');
require_once('functions.php');
$agent_id = $_SESSION['id'];



$statics = dayenddetail('2016-11-10 08:00:00','2016-12-10 08:00:00',$agent_id);

//echo "<pre>";
//print_r($statics);
//
//echo "</pre>";
$contribute = array();


foreach ($statics as $key => $value):
       if(empty($value[1])){
           $start_acc_bal = '0';
       } else {
           $start_acc_bal=$value['1'];
       }
       if(empty($value[2])){
           $end_acc_bal = '0';
       } else {
           $end_acc_bal = $value['2'];
       }
       if(empty($value[3])){
           $credit_cus_in = '0';
       } else {
           $credit_cus_in = $value['3'];
       }
       if(empty($value[4])){
           $debit_cus_in = '0';
       } else {
           $debit_cus_in = $value['4'];
       }       
//       echo $start_acc_bal,$end_acc_bal,$credit_cus_in,$debit_cus_in;
       $start_n_credit_bal = $start_acc_bal + $credit_cus_in;
       $end_n_debit_bal = $end_acc_bal + $debit_cus_in;

       $change_amt =$start_n_credit_bal - $end_n_debit_bal; 
       if($change_amt!=0){
           $contribute[$key] = $change_amt;
       }
//       echo $start_n_credit_bal - $end_n_debit_bal;      
endforeach;
//echo '<pre>';
//print_r($contribute);
//echo '</pre>';
foreach ($contribute as $key => $row)
{
    $id[$key] = $row['value'];
    
}
array_multisort($contribute, SORT_DESC, $id);
$data = array_slice($contribute, 0, 10);

mysqli_close($con);

//echo "<pre>";
//print_r($contribute);
//
//echo "</pre>";

print json_encode($data);


