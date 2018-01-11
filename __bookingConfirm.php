<html>
    <head>
        <meta charset="UTF-8">
<title> Booking Confirmation </title>
<?php
// echo $_POST["booking"];
    if (isset($_POST["booking"]))
    {
        $bookingData = unserialize(base64_decode($_POST["booking"]));
        $username = 'root';
        $password = '';
    
    
        try
        {
            $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmttxt = "INSERT INTO bookings(dateFrom, dateto, guestID, guesthouseID, roomNumber, comment) VALUES ((:DateFrom),(:DateTo),(:Guest),(:GuestHouse),(:RoomNumber),(:Comment))";
            $stmt = $conn->prepare($stmttxt);
            $stmt->bindParam(':DateFrom',$bookingData['datefrom'],PDO::PARAM_STR);
            $stmt->bindParam(':DateTo',$bookingData['dateto'],PDO::PARAM_STR);
            $stmt->bindParam(':Guest',$bookingData['guestID'],PDO::PARAM_INT);
            $stmt->bindParam(':GuestHouse',$bookingData['guesthouseID'],PDO::PARAM_INT);
            $stmt->bindParam(':RoomNumber',$bookingData['room'],PDO::PARAM_INT);
            $stmt->bindParam(':Comment',$bookingData['comment'],PDO::PARAM_STR);
            // echo $stmttxt;
            $stmt->execute();
            // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
           
             
            echo "Booking Info <br><br>";
          foreach ($bookingData as $k => $v) {
                  echo $k."--".$v."<br>";
              }
        }
        catch (PDOException $e)
            {
                echo 'ERROR: ' . $e->getMessage();
            }
        $conn = null; 
    }
    else
    {
        echo "Sorry, booking failure. Please try again <br><br>";      
        echo '<a href = "__roomSelect.php">Back to Room Selection</a>';
    }
?>
</head>
    <body>
        <br><br>
<a href = "__connect2db_btn.php">Make another booking</a>
    </body>
</html>
