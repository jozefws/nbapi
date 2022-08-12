<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if(!isset($_SESSION)){ 
        session_start(); 
    } 
    require "netbox-api.php";
    require "constants.php";

    if(isset($_SESSION['user_id'], $_SESSION['auth'], $_SESSION['key_id'])){
        $resp = testAuth($_SESSION['user_id'], $_SESSION['auth'], $_SESSION['key_id']);
        if($resp === False){
            header("Location: interface.php");
        }
    }else{
        header("Location: interface.php");
    }

    $key = $_SESSION['auth'];



    $auth = $_SESSION['auth'];

    if(isset(
        $_POST['wire_submit'],
        $_POST['roomSel'],
        $_POST['rackSelA'],
        $_POST['rackSelB'],
        $_POST['deviceSelA'],
        $_POST['deviceSelB'],
        $_POST['interfaceSelA'],
        $_POST['interfaceSelB'],
    )){
        $room = $_POST['roomSel'];
        $rackA = $_POST['rackSelA'];
        $rackB = $_POST['rackSelB'];
        $deviceA = $_POST['deviceSelA'];
        $deviceB = $_POST['deviceSelB'];
        $interface_nameA = $_POST['interfaceSelA'];
        $interface_nameB = $_POST['interfaceSelB'];


        $url = $url."/dcim/cables/";
        $ch = curl_init();
        $headers = array(
            'Authorization: Token '.$key,
            'Content-Type: application/json'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = array(
            "side_a_device" => $deviceA,
            "termination_a_type" => "dcim.interface",
            "termination_a_id"=> $interface_nameA,
            "side_b_device"=> $deviceB,
            "termination_b_type"=> "dcim.interface",
            "termination_b_id"=> $interface_nameB,
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        if($response === false){
            return 'Curl error: ' . curl_error($ch);
        }else{
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode == 200 or $httpcode == 201){   
                curl_close($ch);
                header("Location: wireconn.php?messsage=Connection Added");
                
            }else{
                curl_close($ch);
                $jres = json_decode($response, true);
                var_dump($jres);
                if($jres["detail"] != ""){
                    header("Location: wireconn.php?message=".$jres["non_field_errors"][0]);
                }else{
                    header("Location: wireconn.php?message=".$jres["non_field_errors"][0]);
                }
            }
        }
    }

?>