<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (!isset($_SESSION))
    {
        session_start();
    }

    require "netbox-api.php";

    if (isset($_SESSION['user_id'], $_SESSION['auth'], $_SESSION['key_id']))
    {
        $resp = testAuth($_SESSION['user_id'], $_SESSION['auth'], $_SESSION['key_id']);
        if ($resp === False)
        {
            header("Location: interface.php");
        }
    }
    else
    {
        header("Location: interface.php");
    }

    $auth = $_SESSION['auth'];

?>
<!DOCTYPE html>
<html>
    <head>
    <title>Update Wire</title>
    <link rel="stylesheet" type="text/css" href="nbstyle/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script>
        function getCookie(key){
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }

        function clearAllCookies() {  
            for(var i = 0; i < list.length; i++){
                document.cookie = list[i] + "=";
            }
            window.location.reload();
        }

        

    </script>
    </head>
    <body>
    <div class="mainconn">
    <form id="add_form" action="/addwire.php" method="POST">
        <a style="margin-top:0.5em;"href="nblogout.php">Logout</a>
        <h1 style="margin-top:0em;">Update Wired Connection</h1>
        <h2><?php echo $_SESSION['user_name'] ?></h2>
        <br>
        <p>Scan Cable Matrix</p>
        <div id="video-container">
            <video id="qr-video"></video>
        </div>
        <b>Detected QR code: </b>
            <span id="cam-qr-result">Nothing Found</span>
        <br>
        <input name = "sideA" id="sideA" type="text" list="sideA_dl" value="Side A..." />
        <datalist id="sideA_dl">
        </datalist>
        <br>
        <input name = "sideB" id="sideB" type="text" list="sideB_dl" value="Side B..." />
        <datalist id="sideB_dl">
        </datalist>
    
        <script src="wufunc.js" type="module"></script>

    </form>
    </body>
</html>
