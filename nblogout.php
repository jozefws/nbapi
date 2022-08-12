<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($_SESSION)){ 
    session_start(); 
} 
include "constants.php";
require "netbox-api.php";

if(isset($_SESSION['user_id'], $_SESSION['auth'], $_SESSION['key_id'])){
    $resp = testAuth($_SESSION['user_id'], $_SESSION['auth'], $_SESSION['key_id']);
    if($resp === True){
        if(deleteKey($_SESSION['key_id'], $_SESSION['auth'])){
            echo "Key Deleted. Logging you out...";
        }else{
            echo "Logging Out, but could not delete key - Redirecting...";
        }
        
        session_unset();
        session_destroy();
        echo '<meta http-equiv="refresh" content="0;URL= interface.php"> ';

    }else{
        echo "Logged out. Redirecting...";
        session_unset();
        session_destroy();
        echo '<meta http-equiv="refresh" content="0;URL= interface.php"> ';

    }
}else{
    echo "You need to login first! Redirecting...";
    session_unset();
    session_destroy();
    echo '<meta http-equiv="refresh" content="1;URL= interface.php"> ';
}

?>