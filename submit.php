<?php
            $username = 'Unknown';
            $kozya = 'Hello ';
            // print $kozya;
            if (isset($_POST['Submit1']))
            {
                $username = $_POST["username"];
            }
            if ($username == 'Sergey')
            {
                echo ($kozya.$username);
            }
 else { echo 'Who are you?'; }
        ?>
    
        

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

