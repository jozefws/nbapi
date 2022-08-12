<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if(!isset($_SESSION)){ 
        session_start(); 
    }
    include "constants.php";

    function endpointReq($endpoint, $auth){
        include "constants.php";
        $url =  $BASE_URL."/".$endpoint;
        echo $url;
        $ch = curl_init($url);
        $headers = array(
            'Authorization: Token '.$auth,
            'Content-Type: application/json'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        if($response === false)
        {
            return 'Curl error: ' . curl_error($ch);
        }
        else
        {
            return $response;
        }

      
    }

    function updateKeyExpiry($userID, $key, $keyID){
        include "constants.php";
        $expiry = ((string)date("Y-m-d", strtotime(date("Y-m-d"). ' + 1 days'))) ."T". ((string)date("H:i:s")). "Z";
        $url =  $BASE_URL."/".$keyID."/";
        $ch = curl_init();
        $data = array(
            "user" => $userID,
            "expires" => $expiry,
            "key" => $key,
            "write_enabled" => true,
            "description" => "REST API GENERATED KEY"
          );

          $headers = array(
            'Authorization: Token '.$key,
            'Content-Type: application/json'
        );

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if($response === false){
            return 'Curl error: ' . curl_error($ch);
        }else{
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode == 200 or $httpcode == 201){   
                curl_close($ch);
                return $response;
                
            }else{
                curl_close($ch);
                $jres = json_decode($response, true);
                if($jres["detail"] != ""){
                    return $jres["detail"];
                }else{
                    return "Unknown Error";
                }
            }
        }
    }

    function deleteKey($keyid, $key){
        include "constants.php";
        $url =  $BASE_URL."/".$keyid."/";
        $ch = curl_init();
        $headers = array(
            'Authorization: Token '.$key,
            'Content-Type: application/json'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        if($response === false){
            return 'Curl error: ' . curl_error($ch);
        }else{
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode == 200 or $httpcode == 201 or $httpcode == 204){   
                curl_close($ch);
                return true;
                
            }else{
                curl_close($ch);
                $jres = json_decode($response, true);
                if($jres["detail"] != ""){
                    return false;
                }else{
                    return false;
                }
            }
        }
    }

    function testAuth($userID, $key, $keyID){
        include "constants.php";
        $url =  $BASE_URL."/users/tokens/";
        $ch = curl_init();
        $headers = array(
            'Authorization: Token '.$key,
            'Content-Type: application/json'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);

        if($response === false){
            return 'Curl error: ' . curl_error($ch);
        }else{
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode == 200 or $httpcode == 201){   
                $jresp = json_decode($response, true);
                if($jresp["id"] = $keyID and $jresp["user"]["id"] = $userID and $jresp["key"] = $key){
                    curl_close($ch);
                    return true;
                }else{
                    curl_close($ch);
                    return false;
                }
            }
            curl_close($ch);
            return $response;
        }
    }

    function addInterface($deviceSel, $interface_name, $interface_type, $key){
        include "constants.php";
        $url =  $BASE_URL."/dcim/interfaces/";
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
            "device" => (int)$deviceSel,
            "name" => $interface_name,
            "type" => $interface_type
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        if($response === false){
            return 'Curl error: ' . curl_error($ch);
        }else{
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode == 200 or $httpcode == 201){   
                curl_close($ch);
                return true;
                
            }else{
                curl_close($ch);
                $jres = json_decode($response, true);
                var_dump($jres);
                if($jres["detail"] != ""){
                    return false;
                }else{
                    return false;
                }
            }
        }

    }


?>