<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Guest Login </title>
         <?php
            $guestemail = '';
            $userErr = '';
            $username = 'root';
            $password = '';
            if (isset($_POST['GuestLogin']))
            {
                $guestemail = $_POST["guestemail"];
            }
            
            function test_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            } 
            
            
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
    // validating the form to see all required fields are entered
                if (empty($_POST["guestemail"]))
                {
                    $userErr = "Email is required";
                }
                else 
                    {
                    try
                    {
                    $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $guestmail = mysql_real_escape_string($_POST["guestemail"]);
                    $stmttxt = 'select * from guests where email = "'.$guestmail.'"';
                    $stmt = $conn->prepare($stmttxt);
                // echo $stmttxt;
                    $stmt->execute();
                // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $result = $stmt-> fetch();
                    if (empty($result))
                    {
                        $userErr = "Email not found";
                    }
                    else
                    {
                        $_SESSION['guestname'] = $result['Name'];
                        $_SESSION['guestID'] = $result['GuestID'];
                        $userErr = "OK";
                    }

                    }
                    catch (PDOException $e)
                    {
                        echo 'ERROR: ' . $e->getMessage();
                    }
                    $conn = null;
            } 
            
            if ($userErr == "OK")
            {
                header("Location:__connect2db_btn.php");
                exit();
            }
            else 
                {
                    echo $userErr;
                }
            }
            else
            {
                echo("Please enter your email:<br>");
            }
            ?>
</head>
    <body>  
<FORM NAME ="form1" METHOD ="POST" ACTION = "__guestLogin.php">
        <INPUT TYPE = "TEXT" size="50" VALUE ="<?PHP print $guestemail ; ?>" NAME="guestemail">
<INPUT TYPE = "Submit" Name = "GuestLogin" VALUE = "Login">
    </FORM>
<br><br><a href = "__guesthouseSelect.php">Management Login</a>
</body>
</html>
