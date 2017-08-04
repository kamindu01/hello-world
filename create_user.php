<?php
require_once('includes/dbcon.php');
require_once('header.php');
require_once('functions.php');
require_once('resizeimage.php');
require_once('balance.php');
is_loggedin();

$error = '';
$action = $_GET['action'];
$lvl = '';
$user_level = get_session('level');
$agent_id = $_SESSION['id'];
$isImageSelected = 0;

if($action == 'agent'){	
	if($user_level == 1){
		$lvl = '<div class="form-group">
                        <label for="file">Agent Level<span style="color:red">*</span></label>
                        <select class="form-control" id="agent_level" name="agent_level" >
                        <option value="2">Level 2</option>
                        <option value="3">Level 3</option>
                        </select>
                        </div>';	
		}		
        elseif($user_level == 2){
                        $lvl = '<input type="hidden" value="3" name="agent_level"/>';	
                    }
        else {
		if ($action == 'agent' && $_SESSION['level']>2){    
                redirect('index.php');
            }
			
	}
}        
if(isset($_POST['submit'])){
    
	
$name = mysqli_real_escape_string($con, $_POST['name']);
$username = mysqli_real_escape_string($con, $_POST['username']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$country = mysqli_real_escape_string($con, $_POST['country']);
$phone = mysqli_real_escape_string($con, $_POST['tel']);
$password = mysqli_real_escape_string($con, $_POST['password']);
$passport = mysqli_real_escape_string($con, $_POST['number']);
$upload_location = 'uploads';
$image = basename($_FILES['image']['name']);
$target_file = $upload_location.'/'.$image;


//echo move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

 if(!isset($name) || strlen(trim($name)) <2){
       $error = 'custormer name is missing';
    }
    if(!isset($username) || strlen(trim($username)) <2){
        $error = 'custormer username is missing';       
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error ="Please enter valid email address";
    }
    if(!isset($country)){
        $error = 'custormer country is missing';
    }
    if(!isset($phone) || strlen(trim($phone)) <10){
        $error = 'Not a valid telephone number';
    }
    if(!isset($password) || strlen(trim($password)) <2){
        $error = 'custormer password is missing';
    }
    if(!isset($passport) || strlen(trim($passport)) <2){
        $error = 'custormer passport id is missing';
    }
    
    
    if(!isset($image) || strlen(trim($image)) <2){
       $target_file = 'uploads/default-image.png'; 
       $isImageSelected = 0;
    }
    else {
        
        $isImageSelected = 1;
        if(file_exists($target_file)){
            $error='File '.''.$image.''.'alredy exsit';
             $isImageSelected = 0;
        }
        
    }

    if($action=='customer'){        
            $sql1 ="SELECT * FROM `user_tb` WHERE `username`='$username'";
            $resultset = mysqli_query($con, $sql1);
            $value1= mysqli_fetch_assoc($resultset);
            $urnam = $value1['username'];
//            print_r($value1);
            if($urnam == $username){
                $error = 'Username alredy exsist';
            }
        } else {
            $sql1="SELECT * FROM `agent` WHERE `agent_username`='$username'";
            $resultset= mysqli_query($con, $sql1);
            $value1= mysqli_fetch_assoc($resultset);
            $urnam= $value1['agent_username'];
            if($urnam == $username){
                $error='Username alredy exsist';
            }
        }
//        print_r($value1);
   
    
    if(empty($error)){        

	if($action=='customer'){            
//              echo $name . ' ' . $name.' '.$email.' '.$phone.' '.$password.' '.$passport.' '.$agent_level;
                $sql = "INSERT INTO `user_tb`(`user_id`, `imagename`, `username`, `password`, `user_role`, `user_status`, `name`, `country`, `emailadddress`, `phone_no`, `city`, `street`, `pobox`, `approval`, `date_time`, `availability`, `last_name`, `birthday`, `gender`, `nic`, `m_id`, `typest`)";
	$sql .=" VALUES ('','$target_file','$username','$password','customer','5','$name','$country','$email','$phone','','','','',NOW(),'offline','','','','$passport','','live')";
            
	
	}else{		
		$agent_level = $_POST['agent_level'];		
		$sql = "INSERT INTO `agent`(`id`, `agent_name`, `agent_username`, `agent_l1`, `agent_l2`, `agent_l3`, `agent_password`, `agent_level`, `country`, `agent_code`, `parentid`, `amount`, `agent_status`, `ex_time`, `login_status`, `imagename`, `phone_no` , `passportnumber`)";
		$sql .= " VALUES ('','$name','$username','','','','$password','$agent_level','$country','','$agent_id','','5','','','$target_file','$phone','$passport')";
            }	
            //
            //echo $sql;
	if($_FILES['image']['size']>1000000){	
                $error = 'File You Selected Is too Large';	
	}else{
            
            if( $isImageSelected){  
                
                 if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $error = "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                     } else {
		$error = "Sorry, there was an error uploading your file.";
            }
                    
            }
        
		if ($con->query($sql) === TRUE) {
                    if($action == 'customer'){
                            $lastid = mysqli_insert_id($con);
                            $sql1="INSERT INTO `account_tb`(`id`, `Amount`, `user_id`) VALUES('', '', '$lastid')";
                            $resultset1 = mysqli_query($con, $sql1);
                            $id = $_SESSION['id'];
                            $sql ="INSERT INTO `agent_cus`(`id`, `cus_id`, `agent_id`) VALUES ('', '$lastid', '$id')";
                            $resultset2= mysqli_query($con, $sql);
                            $m_id = $lastid+10000;
                            $win ='WIN';
                            $sql2 = "UPDATE `user_tb` SET `m_id`='$win$country$m_id' WHERE user_id = '$lastid'";
                            $resultset3 = mysqli_query($con, $sql2);
                            } else {
                                $lastagentid = mysqli_insert_id($con);
                                $winag = 'WINAG';
                                $agent_code = $lastagentid+20000;
                                $sql3 = "UPDATE `agent` SET `agent_code`='$winag$country$agent_code' WHERE id ='$lastagentid'";
                                $resultset4 = mysqli_query($con, $sql3);
                            }
                            if($isImageSelected == 1){
                            resize_image($target_file);
                            }
                            $error = "New record created successfully";
                            //unset($_POST);
                            //redirect('http://127.0.0.1/admin/create_user.php?action='.$action); 
                        }else {
                                $error = "Error: " . $sql . "<br>" . $con->error;
                            }
                $con->close();
           
	}

		//$_POST = array();
    }    
    
    echo '<script type="text/javascript">
    setTimeout(function(){ location.href = "index.php" }, 2500);
    </script>';

}
?>


<div class="container">
    <div class="col-sm-8 col-sm-offset-2">
<h2>Create <?php echo(ucfirst($action)); ?></h2>
<hr />

<div style="color:red" id="error"><?php echo $error ?></div>
<a style="float: right; margin-bottom: 5px;" class="btn btn-primary " href="index.php">Go back</a>
<form action="" method="post" enctype="multipart/form-data">


<!--<input type="hidden" name="tag" value="" />-->
<div class="form-group">
<label for="name"><?php echo ucfirst($action).' Name'  ?> <span style="color:red">*</span></label>
<input class="form-control" type="text" name="name" placeholder="<?php echo($action.' name')  ?>" id="name" required="required" />

</div>
<div class="form-group">
<label for="email">Email Address <span style="color:red">*</span></label>
<input type="email" name="email" placeholder="Email Address" required="required" class="form-control" id="email" />

</div>
<div class="form-group">
<label for="Country">Country <span style="color:red">*</span></label>
<select class="form-control" name="country" id="country" required="">
	<option value="">Please select your country</option>
	<option value="AF">Afghanistan</option>
	<option value="AX">Åland Islands</option>
	<option value="AL">Albania</option>
	<option value="DZ">Algeria</option>
	<option value="AS">American Samoa</option>
	<option value="AD">Andorra</option>
	<option value="AO">Angola</option>
	<option value="AI">Anguilla</option>
	<option value="AQ">Antarctica</option>
	<option value="AG">Antigua and Barbuda</option>
	<option value="AR">Argentina</option>
	<option value="AM">Armenia</option>
	<option value="AW">Aruba</option>
	<option value="AU">Australia</option>
	<option value="AT">Austria</option>
	<option value="AZ">Azerbaijan</option>
	<option value="BS">Bahamas</option>
	<option value="BH">Bahrain</option>
	<option value="BD">Bangladesh</option>
	<option value="BB">Barbados</option>
	<option value="BY">Belarus</option>
	<option value="BE">Belgium</option>
	<option value="BZ">Belize</option>
	<option value="BJ">Benin</option>
	<option value="BM">Bermuda</option>
	<option value="BT">Bhutan</option>
	<option value="BO">Bolivia, Plurinational State of</option>
	<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
	<option value="BA">Bosnia and Herzegovina</option>
	<option value="BW">Botswana</option>
	<option value="BV">Bouvet Island</option>
	<option value="BR">Brazil</option>
	<option value="IO">British Indian Ocean Territory</option>
	<option value="BN">Brunei Darussalam</option>
	<option value="BG">Bulgaria</option>
	<option value="BF">Burkina Faso</option>
	<option value="BI">Burundi</option>
	<option value="KH">Cambodia</option>
	<option value="CM">Cameroon</option>
	<option value="CA">Canada</option>
	<option value="CV">Cape Verde</option>
	<option value="KY">Cayman Islands</option>
	<option value="CF">Central African Republic</option>
	<option value="TD">Chad</option>
	<option value="CL">Chile</option>
	<option value="CN">China</option>
	<option value="CX">Christmas Island</option>
	<option value="CC">Cocos (Keeling) Islands</option>
	<option value="CO">Colombia</option>
	<option value="KM">Comoros</option>
	<option value="CG">Congo</option>
	<option value="CD">Congo, the Democratic Republic of the</option>
	<option value="CK">Cook Islands</option>
	<option value="CR">Costa Rica</option>
	<option value="CI">Côte d'Ivoire</option>
	<option value="HR">Croatia</option>
	<option value="CU">Cuba</option>
	<option value="CW">Curaçao</option>
	<option value="CY">Cyprus</option>
	<option value="CZ">Czech Republic</option>
	<option value="DK">Denmark</option>
	<option value="DJ">Djibouti</option>
	<option value="DM">Dominica</option>
	<option value="DO">Dominican Republic</option>
	<option value="EC">Ecuador</option>
	<option value="EG">Egypt</option>
	<option value="SV">El Salvador</option>
	<option value="GQ">Equatorial Guinea</option>
	<option value="ER">Eritrea</option>
	<option value="EE">Estonia</option>
	<option value="ET">Ethiopia</option>
	<option value="FK">Falkland Islands (Malvinas)</option>
	<option value="FO">Faroe Islands</option>
	<option value="FJ">Fiji</option>
	<option value="FI">Finland</option>
	<option value="FR">France</option>
	<option value="GF">French Guiana</option>
	<option value="PF">French Polynesia</option>
	<option value="TF">French Southern Territories</option>
	<option value="GA">Gabon</option>
	<option value="GM">Gambia</option>
	<option value="GE">Georgia</option>
	<option value="DE">Germany</option>
	<option value="GH">Ghana</option>
	<option value="GI">Gibraltar</option>
	<option value="GR">Greece</option>
	<option value="GL">Greenland</option>
	<option value="GD">Grenada</option>
	<option value="GP">Guadeloupe</option>
	<option value="GU">Guam</option>
	<option value="GT">Guatemala</option>
	<option value="GG">Guernsey</option>
	<option value="GN">Guinea</option>
	<option value="GW">Guinea-Bissau</option>
	<option value="GY">Guyana</option>
	<option value="HT">Haiti</option>
	<option value="HM">Heard Island and McDonald Islands</option>
	<option value="VA">Holy See (Vatican City State)</option>
	<option value="HN">Honduras</option>
	<option value="HK">Hong Kong</option>
	<option value="HU">Hungary</option>
	<option value="IS">Iceland</option>
	<option value="IN">India</option>
	<option value="ID">Indonesia</option>
	<option value="IR">Iran, Islamic Republic of</option>
	<option value="IQ">Iraq</option>
	<option value="IE">Ireland</option>
	<option value="IM">Isle of Man</option>
	<option value="IL">Israel</option>
	<option value="IT">Italy</option>
	<option value="JM">Jamaica</option>
	<option value="JP">Japan</option>
	<option value="JE">Jersey</option>
	<option value="JO">Jordan</option>
	<option value="KZ">Kazakhstan</option>
	<option value="KE">Kenya</option>
	<option value="KI">Kiribati</option>
	<option value="KP">Korea, Democratic People's Republic of</option>
	<option value="KR">Korea, Republic of</option>
	<option value="KW">Kuwait</option>
	<option value="KG">Kyrgyzstan</option>
	<option value="LA">Lao People's Democratic Republic</option>
	<option value="LV">Latvia</option>
	<option value="LB">Lebanon</option>
	<option value="LS">Lesotho</option>
	<option value="LR">Liberia</option>
	<option value="LY">Libya</option>
	<option value="LI">Liechtenstein</option>
	<option value="LT">Lithuania</option>
	<option value="LU">Luxembourg</option>
	<option value="MO">Macao</option>
	<option value="MK">Macedonia, the former Yugoslav Republic of</option>
	<option value="MG">Madagascar</option>
	<option value="MW">Malawi</option>
	<option value="MY">Malaysia</option>
	<option value="MV">Maldives</option>
	<option value="ML">Mali</option>
	<option value="MT">Malta</option>
	<option value="MH">Marshall Islands</option>
	<option value="MQ">Martinique</option>
	<option value="MR">Mauritania</option>
	<option value="MU">Mauritius</option>
	<option value="YT">Mayotte</option>
	<option value="MX">Mexico</option>
	<option value="FM">Micronesia, Federated States of</option>
	<option value="MD">Moldova, Republic of</option>
	<option value="MC">Monaco</option>
	<option value="MN">Mongolia</option>
	<option value="ME">Montenegro</option>
	<option value="MS">Montserrat</option>
	<option value="MA">Morocco</option>
	<option value="MZ">Mozambique</option>
	<option value="MM">Myanmar</option>
	<option value="NA">Namibia</option>
	<option value="NR">Nauru</option>
	<option value="NP">Nepal</option>
	<option value="NL">Netherlands</option>
	<option value="NC">New Caledonia</option>
	<option value="NZ">New Zealand</option>
	<option value="NI">Nicaragua</option>
	<option value="NE">Niger</option>
	<option value="NG">Nigeria</option>
	<option value="NU">Niue</option>
	<option value="NF">Norfolk Island</option>
	<option value="MP">Northern Mariana Islands</option>
	<option value="NO">Norway</option>
	<option value="OM">Oman</option>
	<option value="PK">Pakistan</option>
	<option value="PW">Palau</option>
	<option value="PS">Palestinian Territory, Occupied</option>
	<option value="PA">Panama</option>
	<option value="PG">Papua New Guinea</option>
	<option value="PY">Paraguay</option>
	<option value="PE">Peru</option>
	<option value="PH">Philippines</option>
	<option value="PN">Pitcairn</option>
	<option value="PL">Poland</option>
	<option value="PT">Portugal</option>
	<option value="PR">Puerto Rico</option>
	<option value="QA">Qatar</option>
	<option value="RE">Réunion</option>
	<option value="RO">Romania</option>
	<option value="RU">Russian Federation</option>
	<option value="RW">Rwanda</option>
	<option value="BL">Saint Barthélemy</option>
	<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
	<option value="KN">Saint Kitts and Nevis</option>
	<option value="LC">Saint Lucia</option>
	<option value="MF">Saint Martin (French part)</option>
	<option value="PM">Saint Pierre and Miquelon</option>
	<option value="VC">Saint Vincent and the Grenadines</option>
	<option value="WS">Samoa</option>
	<option value="SM">San Marino</option>
	<option value="ST">Sao Tome and Principe</option>
	<option value="SA">Saudi Arabia</option>
	<option value="SN">Senegal</option>
	<option value="RS">Serbia</option>
	<option value="SC">Seychelles</option>
	<option value="SL">Sierra Leone</option>
	<option value="SG">Singapore</option>
	<option value="SX">Sint Maarten (Dutch part)</option>
	<option value="SK">Slovakia</option>
	<option value="SI">Slovenia</option>
	<option value="SB">Solomon Islands</option>
	<option value="SO">Somalia</option>
	<option value="ZA">South Africa</option>
	<option value="GS">South Georgia and the South Sandwich Islands</option>
	<option value="SS">South Sudan</option>
	<option value="ES">Spain</option>
	<option value="LK">Sri Lanka</option>
	<option value="SD">Sudan</option>
	<option value="SR">Suriname</option>
	<option value="SJ">Svalbard and Jan Mayen</option>
	<option value="SZ">Swaziland</option>
	<option value="SE">Sweden</option>
	<option value="CH">Switzerland</option>
	<option value="SY">Syrian Arab Republic</option>
	<option value="TW">Taiwan, Province of China</option>
	<option value="TJ">Tajikistan</option>
	<option value="TZ">Tanzania, United Republic of</option>
	<option value="TH">Thailand</option>
	<option value="TL">Timor-Leste</option>
	<option value="TG">Togo</option>
	<option value="TK">Tokelau</option>
	<option value="TO">Tonga</option>
	<option value="TT">Trinidad and Tobago</option>
	<option value="TN">Tunisia</option>
	<option value="TR">Turkey</option>
	<option value="TM">Turkmenistan</option>
	<option value="TC">Turks and Caicos Islands</option>
	<option value="TV">Tuvalu</option>
	<option value="UG">Uganda</option>
	<option value="UA">Ukraine</option>
	<option value="AE">United Arab Emirates</option>
	<option value="GB">United Kingdom</option>
	<option value="US">United States</option>
	<option value="UM">United States Minor Outlying Islands</option>
	<option value="UY">Uruguay</option>
	<option value="UZ">Uzbekistan</option>
	<option value="VU">Vanuatu</option>
	<option value="VE">Venezuela, Bolivarian Republic of</option>
	<option value="VN">Viet Nam</option>
	<option value="VG">Virgin Islands, British</option>
	<option value="VI">Virgin Islands, U.S.</option>
	<option value="WF">Wallis and Futuna</option>
	<option value="EH">Western Sahara</option>
	<option value="YE">Yemen</option>
	<option value="ZM">Zambia</option>
	<option value="ZW">Zimbabwe</option>
</select>

</div>

<div class="form-group">
<label for="tel">Teliphone Number <span style="color:red">*</span></label>
<input type="tel" name="tel" placeholder="Teliphone Number" required="required" class="form-control" id="tel" />

</div>

<div class="form-group">
<label for="username">Username <span style="color:red">*</span></label>
<input type="text" name="username" placeholder="Username" required="required" class="form-control" id="username" />

</div>

<div class="form-group">
<label for="password">Password <span style="color:red">*</span></label>
<input type="password" name="password" placeholder="Password" required="required" class="form-control" id="password" />

</div>

<?php echo $lvl ?>

<div class="form-group">

<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
<label for="file">Image</label>
<input type="file" name="image" placeholder="Image To Upload"  class="form-control" id="image" />

</div>

<div class="form-group">
<label for="number">Passport / Membership Number <span style="color:red">*</span></label>
<input type="text" name="number" placeholder="Passport / Membership Number" required="required" class="form-control" id="number" />

</div>



<br />

<input name="submit" class="btn btn-primary" type="submit" value="Submit">
<input name="reset" class="btn btn-warning" type="reset" value="Reset ">

<!--<button type="submit" class="btn btn-primary"> &nbsp;&nbsp;  Submit  &nbsp;&nbsp; </button>-->


</form>
<br />

</div>
</div>



<?php require_once('footer.php'); ?>

</html>