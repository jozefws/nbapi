<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($_SESSION)){ 
    session_start(); 
} 
require "netbox-api.php";
if(isset($_POST["user-name"]) AND isset($_POST["user-pass"]) AND isset($_POST["login-submit"])){
        
    $user = $_POST["user-name"];
    $pass = $_POST["user-pass"];
    if(empty($user) OR empty($pass)){
        echo "Please fill in all fields";
    }
    else{

        $url = "https://precursor.cs.nott.ac.uk/netbox/api/users/tokens/provision/";
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("username" => $user,"password" => $pass)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if($response === false){
            return 'Curl error: ' . curl_error($ch);
        }else{
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode == 200 or $httpcode == 201){   
                curl_close($ch);
                $jres = json_decode($response, true);
                updateKeyExpiry($jres['user']['id'], $jres['key'], $jres['id']);
                $_SESSION['auth'] = $jres['key'];
                $_SESSION['user_id'] = $jres['user']['id'];
                $_SESSION['user_name'] = $jres['user']['display'];
                $_SESSION['key_id'] = $jres['id'];
                echo "logging you in...";
                echo '<meta http-equiv="refresh" content="0;URL= interface.php"> ';
                
            }else{
                curl_close($ch);
                $jres = json_decode($response, true);
                if($jres["detail"] != ""){
                    echo '<meta http-equiv="refresh" content="0;URL= interface.php?error='.$jres['detail'].'"> ';
                }else{
                    echo "Error";
                   echo '<meta http-equiv="refresh" content="0;URL= interface.php?error=Unknown Error, try again or contact ps-cs-rst@nottingham.ac.uk"> ';
                }
            }
        }

    }

}else{
    echo '<meta http-equiv="refresh" content="0;URL= interface.php"> ';
}


?>