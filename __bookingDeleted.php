<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Booking Deleted</title>
    </head>
    <body>
        <?php
        if (isset($_POST["booking2delete"]))
    {
        $bookingData = $_POST["booking2delete"];
        $username = 'root';
        $password = '';
    
    
        try
        {
            $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmttxt = "DELETE FROM bookings where BookingID = (:BookingID)";
            $stmt = $conn->prepare($stmttxt);
            $stmt->bindParam(':BookingID',$bookingData,PDO::PARAM_INT);
            // echo $stmttxt;
            $stmt->execute();
            // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
           
             
            
        }
        catch (PDOException $e)
            {
                echo 'ERROR: ' . $e->getMessage();
            }
        $conn = null; 
    }
 else {
    echo "Failed to delete the booking";    
    }
        ?>
    <br><br><a href = "__connect2db_btn.php">Back to guesthouse selection</a>
    <a href = "__delete_booking.php">Delete another booking</a>
    </body>
</html>
