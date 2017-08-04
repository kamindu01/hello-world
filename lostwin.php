<?php
require_once('header.php');
require_once('functions.php');
require_once 'includes/dbcon.php';
require_once('balance.php');

is_loggedin();

$action = $_GET['action'];
$logged_agent = $_SESSION['id'];
$logged_agent_level = $_SESSION['level'];
$logged_agent_username = $_SESSION['username'];
$logged_agent_code = $_SESSION['agent_code'];
?>
<br/><br/><br/>

<div class="container">

    <a style="float: right; margin-top: 35px;" class="btn btn-primary " href="index.php">Go back</a>
        <h2>Lost & Win</h2>
       
    <hr />
    
 <section id="date">  

 <h2>Pick A date</h2> 
    <div id="customerdate" style="height:400px" >
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
                <input type="button" class="btn btn-info slow" value="Submit"  onclick="return getdate()">
            </div>
        </form>
    </div>
 </section>
    
    <section id="data" style="display:none"> 
        
<h4 id="select_lvl2_header" style="display: none;">Select Level 2 Agent</h4>
    <div id="select_lvl2" style="display: none; height: 630px; overflow: auto;">
        

    </div>

    <button class="btn btn-primary" id="back_super" style="display: none;" onclick="back_super();">Back</button>

    <div class="row" id="cus_dta">

        <div  class="col-md-6" id="agent" >
        <h2 id="agent_table_header">Agent</h2>
        <div id="agent_table" style="height: 630px; overflow: auto;"></div>
        
    </div>

     <div  class="col-md-6" id="cus">
      <h2 id="customer_table_header">Customer</h2>
        <div id="customer_table" style="height: 630px; overflow: auto;"></div>
       
    </div>
        
    </div>

    </section>
    
</div>
<?php require_once('footer.php') ?>
<script type="text/javascript">
    
    var logged_agent_id= '<?php echo $logged_agent; ?>';
    var logged_agent_code = '<?php echo $logged_agent_code ?>';
    var logged_agent_username = '<?php echo $logged_agent_username ?>';
    var logged_agent_level = '<?php echo $logged_agent_level ?>';
    var action = '<?php echo $action ?>';
    
    
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
    
n =  new Date();
y = n.getFullYear();
m = n.getMonth() + 1;
d = n.getDate();
t = d+1;
$("#startdate").val(m + "/" + d  + "/" + y) ;
$("#enddate").val(m + "/" + t  + "/" + y) ;
    
    var i;
for(i=0;i<60;i++){
                                                
        if(i<10){
            i = '0'+i;
            }
    $("#starttimemin").append('<option value="'+i+'">'+i+'</option>');
    $("#endtimemin").append('<option value="'+i+'">'+i+'</option>');
}

</script>
<script type="text/javascript" src="layout/js/lostwing.js"></script>
</html>
