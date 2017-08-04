<?php 
require_once('header.php');
require_once('functions.php');


is_loggedin();
require_once('balance.php');
//echo '<pre>';
//print_r($_SESSION);
//echo '<pre>';
$sessiondet =  $_SESSION['level'];
$agent_id = $_SESSION['id'];
$parentid = $_SESSION['parentid'];
$imagename = $_SESSION['imagename'];
//echo $parentid;
//echo $sessiondet;

//$stdate = get_commision($agent_id);
//print_r($stdate);

$balance = get_amount($agent_id);

if($sessiondet == 3){
    echo '<script type="text/javascript">function disabled(){window.alert("function disabled");}</script>';
    echo '<style>
    #agent{
        display: none;
    }
    #agent_view{
        display: none;
    }
</style>';
}



//$startDate = start_date();
$startDate=current_start_date();
$end_date = end_date();
//echo $startDate;
//echo '<br/>';
//echo $end_date;
$statics = dayenddetail(''.$startDate,''.$end_date.' 08:00:00',$agent_id);
//$statics = dayenddetail('2016-01-11 08:00:00','2017-01-11 08:00:00',$agent_id);
$dta = select_contributor($statics);
$dropers = get_droppers($statics);
$commcart=getcommchart($agent_id);
$user_details=cus_details($agent_id);
$jcuserdetails=json_encode($user_details);
//echo "<pre>";
//print_r($statics);
//echo "</pre>";

function select_contributor($statics){
$contribute = array();


foreach ($statics as $key => $value):
       if(empty($value[1])){
           $start_acc_bal = 0;
       } else {
           $start_acc_bal=$value[1];
       }
       if(empty($value[2])){
           $end_acc_bal = 0;
       } else {
           $end_acc_bal = $value[2];
       }
       if(empty($value[3])){
           $credit_cus_in = 0;
       } else {
           $credit_cus_in = $value[3];
       }
       if(empty($value[4])){
           $debit_cus_in = 0;
       } else {
           $debit_cus_in = $value[4];
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
$pos_arr=array(); 
$neg_arr=array();
foreach($contribute as $key => $val){
	($val<0) ?
    $neg_arr[$key]=$val:
    $pos_arr[$key]=$val;
            
}
//print_r($pos_arr);
//print_r($neg_arr);
foreach ($pos_arr as $key => $row)
{
    $id[$key] = $row['value'];
}
array_multisort($pos_arr, SORT_DESC, $id);
$data = array_slice($pos_arr, 0, 10);
return  json_encode($data);

}

function get_droppers($statics){
    $credit = array();
    $id = array();
    
   foreach ($statics as $key => $row)
{
       if(isset($row[3])){
           
          $credit[$key] = $row[3]; 
       }
       
    //
}

array_multisort($credit, SORT_DESC);
$data = array_slice($credit, 0, 10); 

//echo '<pre>';
//  print_r($data);
//echo '</pre>';  

return json_encode($data);
    
}

?>


<div class="container-fluid">

<br>
<!--<span style="font-size:14px;" id="bal">Available Bal : <?php //echo number_format("$balance",2);?></span>-->
<h1 align="center">Dashboard</h1>
<hr />

<!-- ================== Buttons =====================-->
<div class="container">

<a class="btn btn-primary smaller-break" href="create_user.php?action=customer">Create Customer</a>
<?php if($parentid == 23){
    
} else {
    echo '<a class="btn btn-danger" href="transaction.php?action=customer">Customer Transaction</a>';
} ?>

<div class="hidden-md hidden-lg hidden-sm" style="height:10px;"></div>
<a class="btn btn-warning" id="agent" href="create_user.php?action=agent">Create Agent</a>
<a class="btn btn-info " id="agent" href="transaction.php?action=agent">Agent Transaction</a>
<div class="hidden-md hidden-lg hidden-sm" style="height:10px;"></div>
<a class="btn btn-success " href="view_user.php?action=customer">View Customer</a>
<div class="hidden-md hidden-lg hidden-xs" style="height:10px;"></div>
<a class="btn btn-warning" id="agent_view" href="view_user.php?action=agent">View Agent</a>
<div class="hidden-sm hidden-lg hidden-xs" style="height:10px;"></div>
<a class="btn btn-primary smaller-break xs" href="democustomer.php">Demo Customer Create</a>
<a class="btn btn-danger smaller-break lWButton" href="lostwin.php">Lost&Win</a>

</div>

<br />

<!-- ================== Quick actions=====================-->

<div class="container-fluid">


<!--============left===========================-->
<div class="col-lg-5 col-md-12" style="border:thin #CCC solid; border-radius:8px; padding-bottom:28px;">
<h4>Customer Transaction</h4>
<hr />
<?php
$sql = "SELECT * FROM `user_tb` WHERE `user_id` in (SELECT `cus_id`FROM `agent_cus` WHERE `agent_id` = '$agent_id');";
$result = mysqli_query($con, $sql);
?>
<form action="transaction.php" method="get" class="form-inline">
<input type="hidden" name="action" value="customer">
<div class="form-group" >
<label for="customer">Customer</label>
<select class="form-control wid" name="customer-select"  required="" >
    <option value="">Select custormer</option>
    <?php while ($value = mysqli_fetch_assoc($result)){ ?>
    <option value="<?php echo $value['user_id'];?>"><?php echo $value['username'];echo '&nbsp;';echo '('.$value['user_id'].')'; ?></option>
    <?php } ?>

</select>

</div>

<div class="form-group">
<label for="amount">Amount</label>
<input type="text" class="form-control" name="cus-amount" placeholder="amount" required="" />


</div>

<button  style="color:#090; font-size:24px; float:right; background-color:transparent !important; border:none !important;"><i class="fa fa-arrow-circle-o-right"></i></button>


</form>

</div>

<!--===================center=====================-->

<div class="hidden-xs col-md-1"></div>

<br class="hidden-lg" />

<!--===================right=====================-->
<div class="col-lg-5 col-md-12" style="border:thin #CCC solid; border-radius:8px; padding-bottom:28px;" onclick="disabled()">
<h4>Agent Transaction</h4>
<hr />
<?php
$sql1 = "SELECT * FROM `agent` WHERE `parentid`='$agent_id'";
$resultset = mysqli_query($con, $sql1);
//echo $sql1;
?>
<form action="transaction.php" method="get" class="form-inline">
<input type="hidden" name="action" value="agent">
<div class="form-group">
<label for="customer">Agent</label>
<select class="form-control wid" name="agent-select" required="" onclick="disabled()">
<option value="">Select agent</option>
    <?php while ($value1 = mysqli_fetch_assoc($resultset)){ ?>
<option value="<?php echo $value1['id'];?>"><?php echo $value1['agent_username'];echo '&nbsp;';echo '('.$value1['id'].')'; ?></option>
    <?php } ?>
</select>

</div>

<div class="form-group">
<label for="amount">Amount</label>
<input type="text" class="form-control" name="agent-amount" placeholder="amount" required="" onclick="disabled()"/>


</div>

<button  style="color:#090; font-size:24px; float:right; background-color:transparent !important; border:none !important;" onclick="disabled()"><i class="fa fa-arrow-circle-o-right"></i></button>


</form>

</div>


</div>

<br />
<?php if($sessiondet== 1){
    echo'
<!-- ================== left side charts panel=====================-->
<div class="col-md-6 combreakc">

<div class="panel panel-default" style="padding-bottom:50px;">
<div class="panel-heading">Top ten contributor</div>
<div class="panel-body" style="padding: 20px 0px 0px 0px">


<div  id="contributor" ></div>
</div>
</div>

</div>

<!-- ================== center charts panel=====================-->
<div class="col-md-6 combreakc">

<div class="panel panel-default" style="padding-bottom:50px;">
<div class="panel-heading">Top ten drops</div>
<div class="panel-body" style="padding: 20px 10px 0px 0px">

    <div id="drops" ></div>


</div>
</div>

</div>';
} elseif($sessiondet== 3){
     echo'
<!-- ================== left side charts panel=====================-->
<div class="col-md-6 combreakc">

<div class="panel panel-default" style="padding-bottom:50px;">
<div class="panel-heading">Top ten contributor</div>
<div class="panel-body" style="padding: 20px 0px 0px 0px">


<div  id="contributor" ></div>
</div>
</div>

</div>

<!-- ================== center charts panel=====================-->
<div class="col-md-6 combreakc">

<div class="panel panel-default" style="padding-bottom:50px;">
<div class="panel-heading">Top ten drops</div>
<div class="panel-body" style="padding: 20px 10px 0px 0px">

    <div id="drops" ></div>


</div>
</div>

</div>';
} else {
    

   
        echo'
<!-- ================== left side charts panel=====================-->
<div class="col-md-4 combreakc">

<div class="panel panel-default" style="padding-bottom:50px;">
<div class="panel-heading">Top ten contributor</div>
<div class="panel-body" style="padding: 20px 0px 0px 0px; height: 370px;">


<div  id="contributor" ></div>
</div>
</div>

</div>

<!-- ================== center charts panel=====================-->
<div class="col-md-4 combreakc">

<div class="panel panel-default" style="padding-bottom:50px;">
<div class="panel-heading">Top ten drops</div>
<div class="panel-body" style="padding: 20px 10px 0px 0px; height: 370px;">

    <div id="drops" ></div>


</div>
</div>

</div>

<!-- ================== right side charts panel=====================-->

<div class="col-md-4 combreak">

<div class="panel panel-default" style="padding-bottom:50px;">
<div class="panel-heading">commision history</div>
<div class="panel-body" style="padding: 20px 0px 0px 0px; height: 370px;">

    <div id="commision"></div>


</div>
</div>

</div>
';
}

?>




<!-- ================== statics table left =====================-->
<?php 
       
?>
<div class="col-md-12 ">
    <h4>Transaction History</h4>
    <div class="col-md-12 trnsaction">
        <button class="btn btn-primary smaller-break break" onclick="Show_cus()">Custormer transaction history</button>
        <button class="btn btn-danger break" id="agent" onclick="Show_agent()">Agent transaction history</button>
    </div>
    <div style="display: none; margin-bottom: 15px;" id="customerdate" >
        <form class="form-inline">
            <div class="form-group">            
                <label>Start Date&Time:</label><input type="Text" class="form-control" name="startdate" id="startdate" >
            </div>
            <div class="form-group">            
                <label>HH:</label><select class="form-control" name="starttime" id="starttime">
                    <option value="00:">00</option>
                    <option value="01:">01</option>
                    <option value="02:">02</option>
                    <option value="03:">03</option>
                    <option value="04:">04</option>
                    <option value="05:">05</option>
                    <option value="06:">06</option>
                    <option value="07:">07</option>
                    <option value="08:">08</option>
                    <option value="09:">09</option>
                    <option value="10:">10</option>
                    <option value="11:">11</option>
                    <option value="12:">12</option>
                    <option value="13:">13</option>
                    <option value="14:">14</option>
                    <option value="15:">15</option>
                    <option value="16:">16</option>
                    <option value="17:">17</option>
                    <option value="18:">18</option>
                    <option value="19:">19</option>
                    <option value="20:">20</option>
                    <option value="21:">21</option>
                    <option value="22:">22</option>
                    <option value="23:">23</option>
                    
                </select>
            </div>
            <div class="form-group">            
                <label>MM:</label><select class="form-control" name="starttimemin" id="starttimemin">                    
                </select>
            </div>
            <div class="form-group">            
                <label>End Date&Time:</label><input type="Text" class="form-control" name="enddate" id="enddate">
            </div>
            <div class="form-group">            
                <label>HH:</label><select class="form-control" name="endtime" id="endtime">
                    <option value="00:">00</option>
                    <option value="01:">01</option>
                    <option value="02:">02</option>
                    <option value="03:">03</option>
                    <option value="04:">04</option>
                    <option value="05:">05</option>
                    <option value="06:">06</option>
                    <option value="07:">07</option>
                    <option value="08:">08</option>
                    <option value="09:">09</option>
                    <option value="10:">10</option>
                    <option value="11:">11</option>
                    <option value="12:">12</option>
                    <option value="13:">13</option>
                    <option value="14:">14</option>
                    <option value="15:">15</option>
                    <option value="16:">16</option>
                    <option value="17:">17</option>
                    <option value="18:">18</option>
                    <option value="19:">19</option>
                    <option value="20:">20</option>
                    <option value="21:">21</option>
                    <option value="22:">22</option>
                    <option value="23:">23</option>
                    
                </select>
            </div>
            <div class="form-group">            
                <label>MM:</label><select class="form-control" name="endtimemin" id="endtimemin">                    
                </select>
            </div>
            <div class="form-group">            
                <input type="submit" class="btn btn-info slow"  onclick="return check()">
            </div>
        </form>
    </div>
    <div style="display: none; margin-bottom: 15px;" id="agentdate">
       <form class="form-inline">
            <div class="form-group">            
                <label>Start Date&Time:</label><input type="Text" class="form-control" name="agstartdate" id="agstartdate">
            </div>
            <div class="form-group">            
                <label>HH:</label><select class="form-control" name="agstarttime" id="agstarttime">
                     <option value="00:">00</option>
                    <option value="01:">01</option>
                    <option value="02:">02</option>
                    <option value="03:">03</option>
                    <option value="04:">04</option>
                    <option value="05:">05</option>
                    <option value="06:">06</option>
                    <option value="07:">07</option>
                    <option value="08:">08</option>
                    <option value="09:">09</option>
                    <option value="10:">10</option>
                    <option value="11:">11</option>
                    <option value="12:">12</option>
                    <option value="13:">13</option>
                    <option value="14:">14</option>
                    <option value="15:">15</option>
                    <option value="16:">16</option>
                    <option value="17:">17</option>
                    <option value="18:">18</option>
                    <option value="19:">19</option>
                    <option value="20:">20</option>
                    <option value="21:">21</option>
                    <option value="22:">22</option>
                    <option value="23:">23</option>
                   
                </select>
            </div>
            <div class="form-group">            
                <label>MM:</label><select class="form-control" name="agstarttime" id="agstarttimemin">                    
                </select>
            </div>
            <div class="form-group">            
                <label>End Date&Time:</label><input type="Text" class="form-control" name="agenddate" id="agenddate">
            </div>
            <div class="form-group">            
                <label>HH:</label><select class="form-control" name="agendtime" id="agendtime">
                    <option value="00:">00</option>
                    <option value="01:">01</option>
                    <option value="02:">02</option>
                    <option value="03:">03</option>
                    <option value="04:">04</option>
                    <option value="05:">05</option>
                    <option value="06:">06</option>
                    <option value="07:">07</option>
                    <option value="08:">08</option>
                    <option value="09:">09</option>
                    <option value="10:">10</option>
                    <option value="11:">11</option>
                    <option value="12:">12</option>
                    <option value="13:">13</option>
                    <option value="14:">14</option>
                    <option value="15:">15</option>
                    <option value="16:">16</option>
                    <option value="17:">17</option>
                    <option value="18:">18</option>
                    <option value="19:">19</option>
                    <option value="20:">20</option>
                    <option value="21:">21</option>
                    <option value="22:">22</option>
                    <option value="23:">23</option>
                    
                </select>
            </div>
            <div class="form-group">            
                <label>MM:</label><select class="form-control" name="agstarttime" id="agendtimemin">                    
                </select>
            </div>
            
            <div class="form-group">            
                <input type="button" value="Submit" class="btn btn-info slow"  onclick="return checkagent()">
            </div>
       </form>
    </div>
   
    
    <div class="col-md-12" id="HistoryTable" style="display: none;"></div>

</div>
    

</div>


<?php require_once('footer.php'); ?>


<script type="text/javascript">

//=============first chart===========
   var categories1 = [];
    var amount1 = [];
    var index1 = 0;
   // var index2 = 0;
    
    var data1 = <?php echo $dta ?>;
	var userdetail=<?php echo $jcuserdetails ;?>;
   // console.log(data);
    var mySeries1 = data1;
//    console.log(mySeries1);
    for (var l in mySeries1){
    if (mySeries1.hasOwnProperty(l)) {
       // uerid=
//	var keyusern='c'+l.substr(2);
//	console.log(l);
        categories1[index1] = '('+userdetail['c'+l.substr(2)][3]+')'+userdetail['c'+l.substr(2)][0]+'';
        amount1[index1] = parseInt(mySeries1[l]);
        index1++;
        
    }
}
  
//  console.log(JSON.stringify(categories1));
  
  var  myChart1  = Highcharts.chart('contributor', {
    chart: {
        type: 'column',
        options3d: {
            enabled: true,
            alpha: 15,
            beta: 15,
            depth: 50,
            viewDistance: 35,
            
        }
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: categories1
    },
    yAxis: {
        title: {
            text: 'Amount'
        }
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'account',
        data: amount1,
        color: '#5cb85c'
    }]
  });
   </script>
   
<script type="text/javascript">
//=============secound chart===========
   var categories = [];
    var amount = [];
    var index = 0;
   // var index2 = 0;
    
    var data = <?php echo $dropers ?>;
  var userdetail1=<?php echo $jcuserdetails ;?>;

   // console.log(data);
    var mySeries = data;
//    console.log(mySeries);
    
    for (var k in mySeries){
    if (mySeries.hasOwnProperty(k)) {
        categories[index] = '('+userdetail1['c'+k.substr(2)][3]+')'+userdetail1['c'+k.substr(2)][0]+'';
        amount[index] = parseInt(mySeries[k]);
        index++;
        
    }
}
  
//  console.log(JSON.stringify(categories));
  
  var  myChart  = Highcharts.chart('drops', {
    chart: {
        type: 'column',
        options3d: {
            enabled: true,
            alpha: 15,
            beta: 15,
            depth: 50,
            viewDistance: 35
        }
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: categories
    },
    yAxis: {
        title: {
            text: 'Amount'
        }
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'account',
        data: amount        
    }]
  });

    </script>
    <!--//=============thired chart=========-->
    <script type="text/javascript">
var sessiondet = <?php echo $sessiondet  ?>;
//console.log(sessiondet);
if(sessiondet == 2){
var categoriescom = [];
    var comamount = [];
   // var index2 = 0;

    var comdata = <?php echo $commcart ?>;
   // console.log(data);
    var commySeries = comdata;
//	 var comindex = commySeries.length;
	 var comindex =4;

    for (var k in commySeries){
    if (commySeries.hasOwnProperty(k)) {

        categoriescom[comindex] = ''+k+'';
        comamount[comindex] = parseInt(commySeries[k]);
        comindex--;

    }
}

      Highcharts.chart('commision', {
    chart: {
        type: 'line',
    },
    title: {
        text: ''
    },
    xAxis: {
        categories:  categoriescom
    },
    yAxis: {
        title: {
            text: 'Amount'
        }
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Commision Date',
        data: comamount,
        color: '#d9534f'
    }]
  });
  }
  </script>

<script type="text/javascript">
    function Show_agent(){
        document.getElementById('agentdate').style.display = "block";
        document.getElementById('customerdate').style.display = "none";
    }
    function Show_cus(){        
        document.getElementById('customerdate').style.display = "block";
        document.getElementById('agentdate').style.display = "none";
    }
    function show_agent_table(){
        document.getElementById('agentTb').style.display = "block";
//        document.getElementById('agentdate').style.display = "block";
    }
    
    var ofset = 0;
    
//==========Date Plicker=============
    $( function() {
        $( "#startdate" ).datepicker({
          changeYear: true
        });
    });
    $( function() {
        $( "#enddate" ).datepicker({
          changeYear: true
        });
    });
    $( function() {
        $( "#agstartdate" ).datepicker({
          changeYear: true
        });
    });
    $( function() {
        $( "#agenddate" ).datepicker({
          changeYear: true
        });
    });
    
    
//============history============    
    
    function check(){
            var startdate = $('#startdate').val();
             var starttime = $('#starttime').val()+$('#starttimemin').val()+':00';
             
//            startdate = startdate.replace(/\//g, "-");

            //var starttime = $('#starttime').val();
            var enddate = $('#enddate').val();
             var endtime = $('#endtime').val()+$('#endtimemin').val()+':00';
//            enddate = enddate.replace(/\//g, "-");
//           console.log(starttime);
//           console.log(endtime);
            var dataString = 'startdate='+startdate+'&enddate='+enddate+'&starttime='+starttime+'&endtime='+endtime;
//            
            
//            console.log(dataString);
            $.ajax({
                    type: "POST",
                    url: "History.php",
                    data: dataString,
                    cache: false,
                    success: function(html){//                        console.log('hear');
                    
                        $('#HistoryTable').html(html);
                        
				},
			
					error: function(XMLHttpRequest, textStatus, errorThrown) {
//                                            console.log('error')
					},
				
                    async:true
                });
            return false;
        }
        
    function checkagent(){
        
         var agstartdate = $('#agstartdate').val();
             var agstarttime = $('#agstarttime').val()+$('#agstarttimemin').val()+':00';
             
//            startdate = startdate.replace(/\//g, "-");

            //var starttime = $('#starttime').val();
            var agenddate = $('#agenddate').val();
             var agendtime = $('#agendtime').val()+$('#agendtimemin').val()+':00';
//            enddate = enddate.replace(/\//g, "-");
          // console.log(agstartdate);
          // console.log(agstarttime);
            var agentdataString = 'agstartdate='+agstartdate+'&agenddate='+agenddate+'&agstarttime='+agstarttime+'&agendtime='+agendtime;
            
//        var agstartdate = $('#agstartdate').val();
//        var agenddate = $('#agenddate').val();
//        
//        var agentdataString = 'agstartdate='+agstartdate+'&agenddate='+agenddate;
////        var agentdataString = 'agenddate='+agenddate;
        
        $.ajax({
                    type: "POST",
                    url: "agentHistory.php",
                    data: agentdataString,
                    cache: false,
                    success: function(html){//                        console.log('hear');
                    
                        $('#HistoryTable').html(html);
                        
				},
			
					error: function(XMLHttpRequest, textStatus, errorThrown) {
//                                            console.log('error')
					},
				
                    async:true
                });
            return false;
    }
    
    $(document).ready(function(){
        $(".slow").click(function(){
            $("#HistoryTable").slideDown("slow");
        });
    });
         
</script>

<script type="text/javascript">
var i;
for(i=0;i<60;i++){
                                                
        if(i<10){
            i = '0'+i;
            }
    $("#starttimemin").append('<option value="'+i+'">'+i+'</option>');
    $("#endtimemin").append('<option value="'+i+'">'+i+'</option>');
    $("#agstarttimemin").append('<option value="'+i+'">'+i+'</option>');
    $("#agendtimemin").append('<option value="'+i+'">'+i+'</option>');
}
                                            
n =  new Date();
y = n.getFullYear();
m = n.getMonth() + 1;
d = n.getDate();
t = d+1;
$("#startdate").val(m + "/" + d  + "/" + y) ;
$("#enddate").val(m + "/" + t  + "/" + y) ;
$("#agstartdate").val(m + "/" + d  + "/" + y) ;
$("#agenddate").val(m + "/" + t  + "/" + y) ;
                                            
</script>

</html>
