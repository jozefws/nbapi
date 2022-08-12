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
        if($resp === True){
            echo "
            <meta name='viewport' content='width=device-width, initial-scale=1.0'> 
            <link rel='stylesheet' type='text/css' href='nbstyle/style.css'>
            <style>text-align: center;</style>
            <h1>Authenticated - ".$_SESSION['user_name']."</h1>
            <br>
            <h2 id='authsub'>You are now authenticated and can access the API.</h2>
            <br>
            <div id='auth-links'>
            <a href='interfaceadd.php'>Add an interface</a>
            <br>
            <a href='wireconn.php'>Add a Wire Connection</a>
            <br>
            <br>
            <a href='nblogout.php'>Logout</a>
            </div>
";

        

        }
        exit();

    }

    session_unset();


?>
<!DOCTYPE html>
<html>
<head>
<title>Login to Netbox</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" type="text/css" href="nbstyle/style.css">
</head>
<body>
<div class="mainconn">
    <h1>Login For CS Interface</h1>
    <form id="add_form"  action="nblogin.php" method="post">
        <br>
        <p id="error" style="color:#fff;"><?php if(isset($_GET['error'])){echo $_GET['error'];} ?></p>
        <input name="user-name" id="user-name" type="text" placeholder="Netbox Username" required />
        <input name="user-pass" id="user-pass" type="password" placeholder="Netbox Password" required  />
       <br>

        <input style="opacity:100;" class="submit" type="submit" name="login-submit" id="login-submit" value="Login" onclick="addInterface()" />

        </form>


</script>
            
</body>

</html>