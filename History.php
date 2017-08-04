<?php
require_once 'includes/dbcon.php';
require_once 'functions.php';
$agent_id = $_SESSION['id'];
        
        $cushistorystart =  $_POST['startdate'];
        $cushistoryend = $_POST['enddate'];
        $starttime = $_POST['starttime'];
        $endtime = $_POST['endtime'];
        
        $newcushistorystart= date('Y-m-d',strtotime($cushistorystart)).' '.$starttime.'';
        $newcushistoryend = date('Y-m-d',strtotime($cushistoryend)).' '.$endtime.'';
        
        
//        echo $newcushistorystart;
//        echo $newcushistoryend;
        
        $sql4 = "SELECT `agent_treasury_cus`.`cus_id`,`agent_treasury_cus`.`amount`,`account_tb`.`Amount`,`agent_treasury_cus`.`ad_time`,`user_tb`.`username`,`user_tb`.`m_id` From `account_tb` INNER JOIN `agent_treasury_cus`ON `agent_treasury_cus`.`cus_id`= `account_tb`.`user_id`INNER JOIN `user_tb` ON `agent_treasury_cus`.`cus_id`= `user_tb`.`user_id` WHERE `agent_treasury_cus`.`cash_id`= '$agent_id' AND `ad_time` BETWEEN '$newcushistorystart' AND '$newcushistoryend'";
//        echo $sql4;    
        $result2 = mysqli_query($con, $sql4);
            $cus_array =array();
            
            while ($fetch = mysqli_fetch_array($result2)) {
//                echo '<pre>';
//                print_r($fetch);
//                echo '</pre>';
                $cus_array[] = $fetch;                
//                print_r($cus_array);               
    
}
//                echo '<pre>';
//                print_r($cus_array);
//                echo '</pre>';
        
        
//        echo $cushistorystart.'<br/>';
//        echo $cushistoryend;



       
    

?>


   

<table class="table table-bordered table-striped table-responsive ">
<?php      
        echo'<tr>
            <th>Customer Id</th>
            <th>Customer Name</th>
            <th>Member Id</th>
            <th>Amount </th>
            <th>Available Balance</th>
            <th>Transaction date & time </th>
            </tr>' ?>
        
    
<?php       foreach ($cus_array as $key => $value):?>
       
                   <tr>
                        <td><?php echo $value['cus_id'];?></td>
                        <td><?php echo $value['username'];?></td>
                        <td><?php echo $value['m_id'];?></td>
                        <td><?php echo number_format($value['amount'],2);?></td>
                        <td><?php echo number_format($value['Amount'],2);?></td>
                        <td><?php echo $value['ad_time'];?></td>
                        </tr>
<?php    endforeach;?>                        
                    </table>
