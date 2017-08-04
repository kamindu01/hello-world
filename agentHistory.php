<?php

require_once 'includes/dbcon.php';
require_once 'functions.php';
$agent_id = $_SESSION['id'];

        $aghistorystart =  $_POST['agstartdate'];
        $aghistoryend = $_POST['agenddate'];
        $agstarttime = $_POST['agstarttime'];
        $agendtime = $_POST['agendtime'];
        
        $newaghistorystart= date('Y-m-d',strtotime($aghistorystart)).' '.$agstarttime.'';
        $newaghistoryend = date('Y-m-d',strtotime($aghistoryend)).' '.$agendtime.'';
        
        
        //echo $newaghistorystart.'<br/>';
        //echo $newaghistoryend;
        $sql3 = "SELECT agent_transaction.sub_agent,agent_transaction.amount,agent_transaction.	sub_agent_bal,agent.agent_username,agent.agent_code,agent_transaction.datetim FROM`agent` RIGHT JOIN `agent_transaction`ON `agent_transaction`.`sub_agent`= `agent`.`id` WHERE `agent_transaction`.`super_agent` = '$agent_id' AND `datetim` BETWEEN '$newaghistorystart' AND '$newaghistoryend'";
        //echo $sql3;
        $result1 = mysqli_query($con, $sql3);
        $value_array = array();
        
        while ($value3 = mysqli_fetch_assoc($result1)){
//           echo '<pre>';
//            print_r($value3);
//            echo '</pre>';
           $value_array[] = $value3;
           
       }
       
//       echo '<pre>';
//       print_r($value_array);
//       echo '</pre>';

?>

<table class="table table-bordered table-striped table-responsive " >

        <tr>
        <th>Agent Id</th>
        <th>Agent Username</th>
        <th>Agent Code</th>
        <th>Amount </th>
        <th>Effective Amount</th>
        <th>Transaction date & time </th>
        </tr>
        <?php        foreach ($value_array as $key => $value): ?>
        <tr>
            <td><?php echo $value['sub_agent']; ?></td>
            <td><?php echo $value['agent_username']; ?></td>
            <td><?php echo $value['agent_code']; ?></td>
        <td><?php echo number_format($value['amount'],2); ?></td>
        <td><?php echo number_format($value['sub_agent_bal'],2); ?></td>
        <td><?php echo $value['datetim']; ?></td>
        </tr>
        <?php endforeach;?>
    </table>