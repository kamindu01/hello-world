<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<link href="layout/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
<link href="layout/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="layout/css/animate.css" type="text/css" rel="stylesheet" />
<link href="layout/css/custom.css" type="text/css" rel="stylesheet" />
<link href="layout/jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <!--<link rel="stylesheet" href="/resources/demos/style.css"/>-->
<title>Win 365 Agent</title>


</head>

<body>
<?php 
require_once 'session.php';
?>
<header>   
   
        <h3 class="h3 header-text"><a href="index.php">WIN 365</a></h3>


        <ul class="list-inline navbar-right cus-ul">


         <!--<li><a><i class="fa fa-cog "></i></a></li>-->
            <?php
            if(isset($_SESSION['id'])){
            echo '<li><a href="change_agent_password.php" style="font-size: 14px;">Change Password</a></li>
        <li><a href="logout.php"><i class="fa fa-sign-out "></i>LogOut</a></li>';
            }
        
                ?>
<!--        <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog "></i>
                <span class="caret"></span></a>
                <ul class="dropdown-menu" style="font-size:14px;">
                  <li><a href="#">Change Password</a></li>
                </ul>
              </li>-->


        </ul>

</header>
