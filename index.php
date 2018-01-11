<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title> Login Form </title>
        <?php
            $username = 'Unknown';
            $male_status ='unchecked';
            $female_status = 'unchecked';
            $ch1 = 'unchecked';
            $ch2 = 'unchecked';
            $ch3 = 'unchecked';
            $ch4 = 'unchecked';
            $ch5 = 'unchecked';
            $ipAddress = $_SERVER['REMOTE_ADDR'];

            $kozya = 'Hello ';
            // print $kozya;
            if (isset($_POST['Submit1']))
            {
                $username = $_POST["username"];
                $selected_radio = $_POST['gender'];
                if ($selected_radio == 'male')
                {
                    $male_status ='checked';
                }
                elseif ($selected_radio == 'female') 
                {
                    $female_status ='checked';
                }
                
                if (isset($_POST['ch1'])) 
                    {
                        $ch1 = $_POST['ch1'];
                        if ($ch1 == 'net') 
                        {
                            $ch1 = 'checked';
                        }
                    }
                 if (isset($_POST['ch2'])) 
                    {
                        $ch2 = $_POST['ch2'];
                        if ($ch2 == 'java') 
                        {
                            $ch2 = 'checked';
                        }
                    }
                 if (isset($_POST['ch3'])) 
                    {
                        $ch3 = $_POST['ch3'];
                        if ($ch3 == 'python') 
                        {
                            $ch3 = 'checked';
                        }
                    }
 //               if ($username == 'Sergey')
  //          {
                echo ($kozya.$username."".$ipAddress);
            }
  //          else { echo 'Who are you?'; }
   //         }
            
            else
            {
                $username = "";
            }
        ?>
    </head>
    <body>     
    <FORM NAME ="form1" METHOD ="POST" ACTION = "index.php">
        <INPUT TYPE = "TEXT" VALUE ="<?PHP print $username ; ?>" NAME="username">
        <Input type = 'Radio' Name ='gender'  value= 'male'
               <?PHP print $male_status; ?> >Male
        <Input type = 'Radio' Name ='gender'  value= 'female'
        <?PHP print $female_status; ?> >Female
            <P>
        <Input type = 'Checkbox' Name ='ch1' value ="net" 
               <?PHP print $ch1; ?>
               > .NET
            <P>
        <Input type = 'Checkbox' Name ='ch2' value="java" 
               <?PHP print $ch2; ?>
               accept=""> Java
            <P>
            <Input type = 'Checkbox' Name ='ch3' value="python" 
            <?PHP print $ch3; ?>
                   > Python
<P>
    <INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Login">
    </FORM>
    </body>
</html>
