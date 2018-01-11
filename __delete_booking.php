<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Existing Bookings </title>
    </head>
    <body>
        <?php
$booking2delete = '';
// $username = 'root';
// $password = '';
        
class TableRows extends RecursiveIteratorIterator {
        
    function __construct($it) {
        parent::__construct($it,self::LEAVES_ONLY);
        }
        function current() {
                
                $current = parent::current();
                $key = parent::key();
                // global $value2pass;
                if ($key == 'BookingID') {
                    
                        global $booking2delete;
                        $booking2delete = $current; 
                        
                    
                }
                
                if ($key != 'GuestHouseID')
                {
                return "<td style='width:200px;border:1px solid black;'>" . 
                parent::current()."</td>";
                }
        }
        function beginChildren() {
                echo "<tr>";
        }
        function endChildren() {
            echo "<td>";
            

echo '<form name="form" method="POST" action="__bookingDeleted.php">';
echo '<input value="'; 
global $booking2delete; 
// $data2pass = base64_encode(serialize($value2pass));
echo $booking2delete;
echo '" type="hidden" name="booking2delete">';
echo '<input type="submit" value="Delete">';
echo '</form>';
echo "</td>";
            echo "</tr>"."\n";
        }
}
        // put your code here
        if (isset($_SESSION["guestID"]))
        {
            $guest = $_SESSION["guestID"];
            
            $username = 'root';
$password = '';
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Booking ID</th><th>Guest House</th><th>Room</th><th>Date From</th><th>Date To</th><th>Delete</th></tr>";
try
{
    $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare('SELECT bookings.BookingID, guesthouses.City, bookings.roomNumber, bookings.dateFrom, bookings.dateTo FROM bookings,guesthouses where bookings.guesthouseID = guesthouses.GuestHouseID and bookings.GuestID = (:Guest)');
    $stmt->bindParam(':Guest',$guest,PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($row = new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) 
as $k => $v
            ) 
                
                {
                    echo $v; 
                }
            //$value2pass = $row['city'];

}
catch (PDOException $e)
{
    echo 'ERROR: ' . $e->getMessage();
}
$conn = null;
echo "</table>";        
        }
        else 
            {
                echo "Failed to retrieve existing bookings";
                
            }
        echo('<br><br><a href = "__connect2db_btn.php">Go back</a>');
        ?>
    </body>
</html>
