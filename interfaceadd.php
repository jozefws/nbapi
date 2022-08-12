<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if(!isset($_SESSION)){ 
        session_start(); 
    } 
    require "netbox-api.php";

    if(isset($_SESSION['user_id'], $_SESSION['auth'], $_SESSION['key_id'])){
        $resp = testAuth($_SESSION['user_id'], $_SESSION['auth'], $_SESSION['key_id']);
        if($resp === False){
            header("Location: interface.php");
        }
    }else{
        header("Location: interface.php");
    }

   

    $auth = $_SESSION['auth'];

   
   
?>
<!DOCTYPE html>
<html>
<head>
<title>Interface Add</title>
<link rel="stylesheet" type="text/css" href="nbstyle/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body>
<div class="mainconn">
<form id="add_form" action="" method="post">
    <?php

        if(isset($_POST['add_submit'], $_POST['roomSel'], $_POST['rackSel'], $_POST['deviceSel'], $_POST['interface_name'], $_POST['interface_type'])){
                
            $roomSel = $_POST['roomSel'];
            $rackSel = $_POST['rackSel'];
            $deviceSel = $_POST['deviceSel'];
            $interface_name = $_POST['interface_name'];
            $interface_type = $_POST['interface_type'];
            
            $resp = addInterface($deviceSel, $interface_name, $interface_type, $_SESSION['auth']);
            if($resp === True){
                echo "Interface Added Successfully!";
            }else{
                echo "Error Adding Interface!";
            }

        }

    ?>
    <a style="margin-top:0.5em;"href="nblogout.php">Logout</a>

    <h1 style="margin-top:0em;">Add Interface</h1>
    <h2><?php echo $_SESSION['user_name']?></h2>
   
        <select id="roomSel" name="roomSel" onchange="roomSelected()">
            <option value="">Select Room</option>
            <?php
                    $res = endpointReq("dcim/locations/", $auth);
                    $res = json_decode($res, true);
                    $results = $res["results"];
                    foreach($results as $room){
                        echo "<option value='".$room["id"]."'>".$room["name"]."</option>";
                    } 
            ?>
        </select>
        <br>
        <select id="rackSel" name = "rackSel" onChange="rackSelected()" disabled="true">
            <option value="">Select Rack</option>
            <?php

                    if(isset($_GET['room'])){
                        echo "<script>document.getElementById('roomSel').value = '".$_GET['room']."';
                            document.getElementById('rackSel').disabled = false;
                        </script>";
                    }

                    $res = endpointReq("dcim/racks/?location_id=".$_GET['room'], $auth);
                    $res = json_decode($res, true);
                    $results = $res["results"];
                    foreach($results as $racks){
                        echo "<option value='".$racks["id"]."'>".$racks["name"]."</option>";
                    }
            ?>
        </select>
        <br>
        <select id="deviceSel" name="deviceSel" onChange="deviceSelected()" disabled="true">
            <option value="">Select Device</option>
            <?php

                    if(isset($_GET['rack']) AND isset($_GET['room'])){
                        echo "<script>document.getElementById('roomSel').value = '".$_GET['room']."';
                                document.getElementById('rackSel').value = '".$_GET['rack']."';
                                document.getElementById('deviceSel').disabled = false;
                            </script>";
                    }


                    $res = endpointReq("dcim/devices/?rack_id=".$_GET['rack'], $auth);

                    $res = json_decode($res, true);
                    $results = $res["results"];
                    foreach($results as $devices){
                        echo "<option value='".$devices["id"]."'>".$devices["display"]."</option>";
                    }
                   
            ?>
        </select>

        <br>
        <select id="interface-type-sel" onchange="typesel()" disabled="true">>
            <option value="100base-tx">100 Base</option>
            <option selected="selected" value="1000base-t">1gb Base</option>
            <option value="2.5gbase-t">2.5g base</option>
            <option value="10gbase-x-sfpp">10g base SFP</option>
        </select>

        <br>
        <input name = "interface_type" id="interface_type" type="text" list="inteface_type_dl" value="1000base-t" />
        <datalist id="inteface_type_dl">
            <option value="virtual">Virtual</option>
            <option value="bridge">Bridge</option>
            <option value="lag">Link Aggregation Group (LAG)</option>
            <option value="100base-tx">100BASE-TX (10/100ME)</option>
            <option value="1000base-t">1000BASE-T (1GE)</option>
            <option value="2.5gbase-t">2.5GBASE-T (2.5GE)</option>
            <option value="5gbase-t">5GBASE-T (5GE)</option>
            <option value="10gbase-t">10GBASE-T (10GE)</option>
            <option value="10gbase-cx4">10GBASE-CX4 (10GE)</option>
            <option value="1000base-x-gbic">GBIC (1GE)</option>
            <option value="1000base-x-sfp">SFP (1GE)</option>
            <option value="10gbase-x-sfpp">SFP+ (10GE)</option>
            <option value="10gbase-x-xfp">XFP (10GE)</option>
            <option value="10gbase-x-xenpak">XENPAK (10GE)</option>
            <option value="10gbase-x-x2">X2 (10GE)</option>
            <option value="25gbase-x-sfp28">SFP28 (25GE)</option>
            <option value="50gbase-x-sfp56">SFP56 (50GE)</option>
            <option value="40gbase-x-qsfpp">QSFP+ (40GE)</option>
            <option value="50gbase-x-sfp28">QSFP28 (50GE)</option>
            <option value="100gbase-x-cfp">CFP (100GE)</option>
            <option value="100gbase-x-cfp2">CFP2 (100GE)</option>
            <option value="200gbase-x-cfp2">CFP2 (200GE)</option>
            <option value="100gbase-x-cfp4">CFP4 (100GE)</option>
            <option value="100gbase-x-cpak">Cisco CPAK (100GE)</option>
            <option value="100gbase-x-qsfp28">QSFP28 (100GE)</option>
            <option value="200gbase-x-qsfp56">QSFP56 (200GE)</option>
            <option value="400gbase-x-qsfpdd">QSFP-DD (400GE)</option>
            <option value="400gbase-x-osfp">OSFP (400GE)</option>
            <option value="ieee802.11a">IEEE 802.11a</option>
            <option value="ieee802.11g">IEEE 802.11b/g</option>
            <option value="ieee802.11n">IEEE 802.11n</option>
            <option value="ieee802.11ac">IEEE 802.11ac</option>
            <option value="ieee802.11ad">IEEE 802.11ad</option>
            <option value="ieee802.11ax">IEEE 802.11ax</option>
            <option value="ieee802.15.1">IEEE 802.15.1 (Bluetooth)</option>
            <option value="gsm">GSM</option>
            <option value="cdma">CDMA</option>
            <option value="lte">LTE</option>
            <option value="sonet-oc3">OC-3/STM-1</option>
            <option value="sonet-oc12">OC-12/STM-4</option>
            <option value="sonet-oc48">OC-48/STM-16</option>
            <option value="sonet-oc192">OC-192/STM-64</option>
            <option value="sonet-oc768">OC-768/STM-256</option>
            <option value="sonet-oc1920">OC-1920/STM-640</option>
            <option value="sonet-oc3840">OC-3840/STM-1234</option>
            <option value="1gfc-sfp">SFP (1GFC)</option>
            <option value="2gfc-sfp">SFP (2GFC)</option>
            <option value="4gfc-sfp">SFP (4GFC)</option>
            <option value="8gfc-sfpp">SFP+ (8GFC)</option>
            <option value="16gfc-sfpp">SFP+ (16GFC)</option>
            <option value="32gfc-sfp28">SFP28 (32GFC)</option>
            <option value="64gfc-qsfpp">QSFP+ (64GFC)</option>
            <option value="128gfc-qsfp28">QSFP28 (128GFC)</option>
            <option value="infiniband-sdr">SDR (2 Gbps)</option>
            <option value="infiniband-ddr">DDR (4 Gbps)</option>
            <option value="infiniband-qdr">QDR (8 Gbps)</option>
            <option value="infiniband-fdr10">FDR10 (10 Gbps)</option>
            <option value="infiniband-fdr">FDR (13.5 Gbps)</option>
            <option value="infiniband-edr">EDR (25 Gbps)</option>
            <option value="infiniband-hdr">HDR (50 Gbps)</option>
            <option value="infiniband-ndr">NDR (100 Gbps)</option>
            <option value="infiniband-xdr">XDR (250 Gbps)</option>
            <option value="t1">T1 (1.544 Mbps)</option>
            <option value="e1">E1 (2.048 Mbps)</option>
            <option value="t3">T3 (45 Mbps)</option>
            <option value="e3">E3 (34 Mbps)</option>
            <option value="xdsl">xDSL</option>
            <option value="cisco-stackwise">Cisco StackWise</option>
            <option value="cisco-stackwise-plus">Cisco StackWise Plus</option>
            <option value="cisco-flexstack">Cisco FlexStack</option>
            <option value="cisco-flexstack-plus">Cisco FlexStack Plus</option>
            <option value="cisco-stackwise-80">Cisco StackWise-80</option>
            <option value="cisco-stackwise-160">Cisco StackWise-160</option>
            <option value="cisco-stackwise-320">Cisco StackWise-320</option>
            <option value="cisco-stackwise-480">Cisco StackWise-480</option>
            <option value="juniper-vcp">Juniper VCP</option>
            <option value="extreme-summitstack">Extreme SummitStack</option>
            <option value="extreme-summitstack-128">Extreme SummitStack-128</option>
            <option value="extreme-summitstack-256">Extreme SummitStack-256</option>
            <option value="extreme-summitstack-512">Extreme SummitStack-512</option>
            <option value="other">Other</option>
        </datalist>

        <br>
        <input name = "interface_name" id="interface_name" type="text" placeholder="Interface Name" required disabled="true"/>
        
       <br>

        <input type="submit" class="submit" name = "add_submit" id="add_submit" value="Add Interface" onclick="addInterface()" disabled="true"/>

        <?php

            if(isset($_GET['rack']) AND isset($_GET['room']) AND isset($_GET['device'])){
                echo "<script>document.getElementById('roomSel').value = '".$_GET['room']."';
                        document.getElementById('rackSel').value = '".$_GET['rack']."';
                        document.getElementById('deviceSel').disabled = false;
                        document.getElementById('deviceSel').value = '".$_GET['device']."';
                        document.getElementById('interface-type-sel').disabled = false;
                        document.getElementById('interface_name').disabled = false;
                        document.getElementById('interface_type').disabled = false;
                        document.getElementById('add_submit').disabled = false;
                        document.getElementById('add_submit').style.opacity = 100;
                    </script>";
            }

        ?>
        <br>
        <br>
        <a style="margin-top:0.5em; padding-bottom: 2em;" href="wireconn.php">Add Wire</a>
        </form>
        <script>
        function roomSelected(){
            var room = document.getElementById("roomSel");
            var roomID = room.selectedIndex;
            var room = room.options[roomID].value;
            if(room == ""){
                document.getElementById("rackSel").disabled = true;
                return;
            }else{
                var url = "interfaceadd.php?room="+ room;
                window.location.href = url;
                document.getElementById("rackSel").disabled = false;

            }
        }
          
        function rackSelected(){
            var rack = document.getElementById("rackSel");
            var rackID = rack.selectedIndex;
            var rack = rack.options[rackID].value;
            if(rack == ""){
                document.getElementById("deviceSel").disabled = true;
                return;
            }else{
                var url = "interfaceadd.php?room="+ document.getElementById("roomSel").value + "&rack=" + rack;
                window.location.href = url;
                document.getElementById("deviceSel").disabled = false;
            }
        }

        function deviceSelected(){
            var device = document.getElementById("deviceSel");
            var deviceID = device.selectedIndex;
            var device = device.options[deviceID].value;
            if(device == ""){
                return;
            }else{
                var url = "interfaceadd.php?room="+ document.getElementById("roomSel").value + "&rack=" + document.getElementById("rackSel").value + "&device=" + device;
                window.location.href = url;
                document.getElementById("add_submit").disabled = false;
            }
        }

        function typesel(){
            var type = document.getElementById("interface-type-sel");
            var typeID = type.selectedIndex;
            var type = type.options[typeID].value;
            document.getElementById("interface_type").value = type;
            document.getElementById("interface_type").innerHTML = type;
        }


</script>
            
</body>

</html>