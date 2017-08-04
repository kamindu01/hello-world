
<?php require_once('header.php');
require_once('functions.php');
require_once 'includes/dbcon.php';
require_once('balance.php');

is_loggedin();

$action = $_GET['action'];
$logged_agent = $_SESSION['id'];
$logged_agent_level = $_SESSION['level'];
$logged_agent_username = $_SESSION['username'];
$logged_agent_code = $_SESSION['agent_code'];


if(($action == 'agent')&& ($logged_agent_level > 2)){
    
    redirect('index.php');    
} 


 ?>


<div class="container">

    <a style="float: right; margin-top: 35px;" class="btn btn-primary " href="index.php">Go back</a>
        <h2>View <?php echo ucfirst($action); ?></h2>
       
    <hr />
    
    <span><?php if(isset($_GET['error'])){
        
            echo '<div class="alert alert-danger">
          <strong>Error!</strong> '.$_GET['error'].'
          </div>';
    }?></span>
    

<h4 id="select_lvl2_header" style="display: none;">Select Level 2 Agent</h4>
    <div id="select_lvl2" style="display: none; height: 630px; overflow: auto;">
        

    </div>

    <button class="btn btn-primary" id="back_super" style="display: none;" onclick="back_super();">Back</button>

    <div class="row" id="cus_dta">

        <div  class="col-md-5" id="agent" >
        <h2 id="agent_table_header">Agent</h2>
        <div id="agent_table" style="height: 630px; overflow: auto;"></div>
        
    </div>

     <div  class="col-md-7" id="cus">
      <h2 id="customer_table_header">Customer</h2>
        <div id="customer_table" style="height: 630px; overflow: auto;"></div>
       
    </div>
        
    </div>
</div>


<?php require_once('footer.php') ?>
<script type="text/javascript">
    
    var logged_agent_id= '<?php echo $logged_agent; ?>';
    var logged_agent_code = '<?php echo $logged_agent_code ?>';
    var logged_agent_username = '<?php echo $logged_agent_username ?>';
    var logged_agent_level = '<?php echo $logged_agent_level ?>';
    var action = '<?php echo $action ?>';

</script>
<script type="text/javascript" src="layout/js/view_customer.js"></script>


</html>