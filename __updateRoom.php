<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
         <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/> <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script> <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
        <title>Update Room Info</title>
    </head>
    <body>
        <?php
        $value2pass = [];
    if (isset($_POST["search"]))
    {
        $value2pass = unserialize(base64_decode($_POST["search"]));
        // $value2pass
        $_SESSION["guesthouse"] = $value2pass["guesthouse"];
        $_SESSION["guesthouseID"] = $value2pass["guesthouseID"];
    }
 else {
       $value2pass["guesthouse"] = $_SESSION["guesthouse"];
       $value2pass["guestname"] = $_SESSION["guestname"];
       $value2pass["guestID"] = $_SESSION["guestID"];
       $value2pass["guesthouseID"] = $_SESSION["guesthouseID"];
}

    // $value2pass["guesthouse"] = $guesthouse;
    // echo "Welcome, ".$value2pass["guestname"]."<br";
    echo "Rooms in ".$value2pass["guesthouse"];
    echo "<br><br>"; 
    
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Room #</th><th>Beds</th><th>Cost</th><th>Extra Info</th><th>Book</th></tr>";
class TableRows extends RecursiveIteratorIterator {
        
    function __construct($it) {
        parent::__construct($it,self::LEAVES_ONLY);
        }
        function current() {
                
                $current = parent::current();
                $key = parent::key();
                if ($key == 'roomnumber') {
                    
                        global $value2pass;
                        $value2pass["room"] = $current;
                    
                }
                return "<td style='width:150px;border:1px solid black;'>" . 
                parent::current()."</td>";
        }
        function beginChildren() {
                echo "<tr>";
                
        }
        
        function endChildren() {
            global $value2pass;
            echo "<td>";
 
            echo '<form name="form" method="POST" action="__updateRoom.php">';
        // echo '<input value="'; 
         
        
        $result = base64_encode(serialize($value2pass));
        echo '<input value="';
        echo $result;
        echo '" type="hidden" name="booking">';
        echo '<input type="submit" value="Update">';
        echo '</form>';
}
        }

            
            
            echo "</td>";
            echo "</tr>"."\n";
        


$username = 'root';
$password = '';
try
{
    $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $stmttxt = 'SELECT roomnumber, beds, cost, extra '
    //        . 'FROM rooms where guesthouseid in (select guesthouseid from'
    //        . 'guesthouses where city =  "'.$guesthouse.'")';
    $stmttxt = 'SELECT roomnumber,beds,cost,extra FROM rooms WHERE guesthouseid in (select guesthouseID from guesthouses where city = "'.$value2pass["guesthouse"].'" )';
    $stmt = $conn->prepare($stmttxt);
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($row = new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) 
as $k => $v
            ) {echo $v; 
            //$value2pass = $row['city'];

}}
catch (PDOException $e)
{
    echo 'ERROR: ' . $e->getMessage();
}
$conn = null;
echo "</table>";
echo "<br><br>";

        ?>
        <br><br><a href = "__guesthouseSelect.php">Back to guesthouse selection</a>
    
    </body>
</html>
