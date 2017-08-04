<?php

require_once('functions.php');
$agent_id = $_SESSION['id'];

$balance = get_amount($agent_id);

?>
<div style="font-size:14px; text-align: right; margin-right: 20px;  position: absolute; width: 100%;" id="bal"><h4>Available Bal : <?php echo number_format("$balance",2);?></h4></div>


