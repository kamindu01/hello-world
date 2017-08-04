<?php
require_once 'includes/dbcon.php';
require_once 'functions.php';
$action = $_GET['action'];
$value = $_GET['value'];
$user = $_GET['user'];
//echo $user;
$logged_agent = $_SESSION['id'];
$error = '';
$privilage = check_customer_privilaages($logged_agent,$user,$action);

if($privilage){

                if($action == 'customer'){

                        if($value =='disable'){

                                $sql ="UPDATE user_tb SET user_status='4' WHERE user_id='$user'";
//                                echo $sql;
                                $result = mysqli_query($con, $sql);
                                header('location:view_user.php?action='.$action.'');

                            } else {
                                $sql ="UPDATE `user_tb` SET `user_status`='5' WHERE `user_id`='$user'";
                                $result = mysqli_query($con, $sql);
                                header('location:view_user.php?action='.$action.'');
                            }
                } else {
                    if($value =='disable'){
                                $sql ="UPDATE `agent` SET `agent_status`='4' WHERE `id` = '$user' or `parentid` = '$user'";
                                $result = mysqli_query($con, $sql);
                                $sql2 = "UPDATE `user_tb` SET `user_status`='4' WHERE `user_id` IN (SELECT `cus_id` FROM `agent_cus` WHERE `agent_id`='$user')";
//                                echo $sql2;
                                $query = mysqli_query($con, $sql2);                              
                                
                                header('location:view_user.php?action='.$action.'');

                            } else {
                                $sql ="UPDATE `agent` SET `agent_status`='5' WHERE `id` = '$user' or `parentid` = '$user'";
                                $result = mysqli_query($con, $sql);
                                $sql2 = "UPDATE `user_tb` SET `user_status`='5' WHERE `user_id` IN (SELECT `cus_id` FROM `agent_cus` WHERE `agent_id`='$user')";
//                                echo $sql2;
                                $query = mysqli_query($con, $sql2);
//                             
                                header('location:view_user.php?action='.$action.'');
                            }

                }
} else {
    $error = 'Cant disabled this custormer';
    header('location:view_user.php?action='.$action.'&error='.$error.'');
}