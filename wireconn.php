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
    <title>Wire Add</title>
    <link rel="stylesheet" type="text/css" href="nbstyle/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script>
        function getCookie(key){
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }

        var list = ["room","rackA","rackB","deviceA","deviceB","interfaceA","interfaceB"];

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
        <h1 style="margin-top:0em;">Add Wired Connection</h1>
        <h2><?php echo $_SESSION['user_name'] ?></h2>
        
        <select id="roomSel" name="roomSel" onchange="roomSelected()">
                <option value="">Select Room</option>
            <?php
                $res = endpointReq("dcim/locations/", $auth);
                $res = json_decode($res, true);
                $results = $res["results"];
                foreach ($results as $room)
                {
                    echo "<option value='" . $room["id"] . "'>" . $room["name"] . "</option>";
                }
            ?>
        </select>
        <div id="selCon">
            <p class="selP" id="sideselA" onclick="sides('a')">Side A</p>
            <p class="selP" id="sideselB" onclick="sides('b')">Side B</p>
            <br>
            <br>
            <p style="font-size: 0.8em;  color: white;" id="error"><?php
                if(isset($_GET['message'])){
                    $message = $_GET['message'];
                    if($message == "The fields termination_b_type, termination_b_id must make a unique set."){
                        echo "Connection already exists from either port on device A or device B.";
                        echo "<script>document.getElementById('error').style.color = '#ffaa18';</script>";
                    }else{
                        echo $message;
                    }

                }
            
            ?></p>
        </div>

      
        <div id="sideA">
                <select id="rackSelA" name = "rackSelA" onchange="rackSelected('a')" disabled="true">
                        <option value="">Select Rack A</option>
                        <script>
                                if(getCookie("room") != null){
                                    document.getElementById('roomSel').value = getCookie("room");
                                    document.getElementById('rackSelA').disabled = false;
                                }
                        </script>
                        <?php
            $res = endpointReq("dcim/racks/?location_id=" . $_COOKIE['room'], $auth);
            $res = json_decode($res, true);
            $results = $res["results"];
            foreach ($results as $racks)
            {
                echo "<option value='" . $racks["id"] . "'>" . $racks["name"] . "</option>";
            }
            ?>
                </select>

                <select id="deviceSelA" name="deviceSelA" onchange="deviceSelected('a')" disabled="true">
                        <option value="">Select Device A</option>
                        <script>
                            if(getCookie("rackA") != null && getCookie("room") != null){
                                document.getElementById('roomSel').value = getCookie("room");
                                document.getElementById('rackSelA').value = getCookie("rackA");
                                document.getElementById('deviceSelA').disabled = false;
                            }
                    </script>
                        <?php
            $res = endpointReq("dcim/devices/?rack_id=" . $_COOKIE['rackA'], $auth);

            $res = json_decode($res, true);
            $results = $res["results"];
            foreach ($results as $devices)
            {
                echo "<option value='" . $devices["id"] . "'>" . $devices["display"] . "</option>";
            }

            ?>
                </select>

                <input name = "interfaceSelA" id="interfaceSelA" type="text"   onchange="interfaceSelected('a')" list="inteface_dl_a" required placeholder="Select Interface"/>
                <datalist id="inteface_dl_a">
                        <script>
                            if(getCookie("rackA") != null && getCookie("room") != null && getCookie("deviceA") != null){
                                document.getElementById('roomSel').value = getCookie("room");
                                document.getElementById('rackSelA').value = getCookie("rackA");
                                document.getElementById('deviceSelA').value = getCookie("deviceA");
                            }
                            if(getCookie("interfaceA") != null){                        
                                document.getElementById('interfaceSelA').value = getCookie("interfaceA");
                            }
                        </script>
                    <?php
                        if(isset($_COOKIE['deviceA']) and $_COOKIE['deviceA'] != ""){
                            $res = endpointReq("dcim/interfaces/?device_id=" . $_COOKIE['deviceA'] . "&limit=100", $auth );
                            $res = json_decode($res, true);
                            $results = $res["results"];
                            if($results == null){
                            echo   "<script>document.getElementById('interfaceSelA').value = 'No Interfaces';
                                    document.getElementById('interfaceSelA').disabled = true;</script>";
                            }else{
                                foreach ($results as $devices){
                                    echo "<option value='" . $devices["id"] . "'>" . $devices["display"] . "</option>";
                                }
                            }
                         
                        }
                    ?>
            </datalist>
        </div>
        <div id="sideB">
            <select id="rackSelB" name = "rackSelB" onchange="rackSelected('b')" disabled="true">
                <option value="">Select Rack B</option>
                <script>
                    if(getCookie("room") != null){
                        document.getElementById('roomSel').value = getCookie("room");
                        document.getElementById('rackSelB').disabled = false;
                    }
                </script>
                <?php

            $res = endpointReq("dcim/racks/?location_id=" . $_COOKIE['room'], $auth);
            $res = json_decode($res, true);
            $results = $res["results"];
            foreach ($results as $racks)
            {
                echo "<option value='" . $racks["id"] . "'>" . $racks["name"] . "</option>";
            }
            ?>
                </select>

                <select id="deviceSelB" name="deviceSelB" onchange="deviceSelected('b')" disabled="true">
                    <option value="">Select Device B</option>
                    <script>
                        if(getCookie("rackB") != null && getCookie("room") != null){
                            document.getElementById('roomSel').value = getCookie("room");
                            document.getElementById('rackSelB').value = getCookie("rackB");
                            document.getElementById('deviceSelB').disabled = false;
                        }
                    </script>
                    <?php
            $res = endpointReq("dcim/devices/?rack_id=" . $_COOKIE['rackB'], $auth);
            $res = json_decode($res, true);
            $results = $res["results"];
            foreach ($results as $devices)
            {
                echo "<option value='" . $devices["id"] . "'>" . $devices["display"] . "</option>";
            }

            ?>
                </select>
                <input name = "interfaceSelB" id="interfaceSelB" type="text"  onchange="interfaceSelected('b')"  list="inteface_dl_b" required placeholder="Select Interface"/>
                <datalist id="inteface_dl_b">
                    <script>
                        if(getCookie("rackB") != null && getCookie("room") != null && getCookie("deviceB") != null){
                            document.getElementById('roomSel').value = getCookie("room");
                            document.getElementById('rackSelB').value = getCookie("rackB");
                            document.getElementById('deviceSelB').value = getCookie("deviceB");
                        }
                        if(getCookie("interfaceB") != null){
                                document.getElementById('interfaceSelB').value = getCookie("interfaceB");
                        }
                    </script>
                    <?php
            if(isset($_COOKIE['deviceB']) and $_COOKIE['deviceB'] != ""){
                $res = endpointReq("dcim/interfaces/?device_id=" . $_COOKIE['deviceB']. "&limit=100", $auth );
                $res = json_decode($res, true);
                $results = $res["results"];
                if($results == null){
                    echo "<script>
                            document.getElementById('interfaceSelB').innerHTML = 'No Interfaces';
                            document.getElementById('interfaceSelB').disabled = true;
                          </script>";
                }else{
                    foreach ($results as $devices){
                        echo "<option value='" . $devices["id"] . "'>" . $devices["display"] . "</option>";
                    }
                }

              
            }
           

            ?>
                </datalist>
        </div>

        <input type="submit" id="wire_submit" name="wire_submit" class="submit" value="Submit" />
        <br>
        <div style="color: white;" id="ext-links">
            <br>
            <a style="margin-top:0.5em; font-size: 0.8em;" onClick="clearAllCookies()">Clear ALL Options</a>
            &nbsp; | &nbsp;
            <a style="margin-top:0.5em; font-size: 0.8em; padding-bottom: 2em;" onClick="redirectInterface()">Add Interface</a>
        </div>

    </form>
    <script src="wcfunc.js"></script> 
    <script>if(getCookie("side")!=null){sides(getCookie("side"));}</script>
    </body>
</html>
