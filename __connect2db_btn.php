<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Guesthouse Selection </title>
</head>
    <body>
<?php
$value2pass = [];
if (isset($_SESSION["guestname"]))
{
    echo "Welcome, ".$_SESSION["guestname"]."! <br>";
    $value2pass["guestname"] = $_SESSION["guestname"];
}
if (isset($_SESSION["guestID"]))
{
    $value2pass["guestID"] = $_SESSION["guestID"];
}
// else {
//    $_SESSION["guestID"] = $value2pass["guestID"];
//}
    

echo "<table style='border: solid 1px black;'>";
echo "<tr><th>City</th><th>Location</th><th>Book</th></tr>";
class TableRows extends RecursiveIteratorIterator {
        
    function __construct($it) {
        parent::__construct($it,self::LEAVES_ONLY);
        }
        function current() {
                
                $current = parent::current();
                $key = parent::key();
                global $value2pass;
                if ($key == 'City') {
                    
                        
                        $value2pass["guesthouse"] = $current; 
                        
                    
                }
                elseif ($key == 'GuestHouseID') 
                    {
                        $value2pass["guesthouseID"] = $current; 
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
            

echo '<form name="form" method="POST" action="__roomSelect.php">';
echo '<input value="'; 
global $value2pass; 
$data2pass = base64_encode(serialize($value2pass));
echo $data2pass;
echo '" type="hidden" name="search">';
echo '<input type="submit" value="Book">';
echo '</form>';

            
            
            echo "</td>";
            echo "</tr>"."\n";
        }
}

$username = 'root';
$password = '';
try
{
    $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare('SELECT * FROM guesthouses');
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

//foreach ($value2pass as $k => $v)
//{
//                  echo $k."--".$v."<br>";
//   
echo('<br><br><a href = "__delete_booking.php">Delete an existing booking</a>');
?>
    </body>
</html>




