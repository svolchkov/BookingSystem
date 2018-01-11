<?php
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>City</th><th>Location</th></tr>";
class TableRows extends RecursiveIteratorIterator {
        function __construct($it) {
        parent::__construct($it,self::LEAVES_ONLY);
        }
        function current() {
                return "<td style='width:150px;border:1px solid black;'>" . 
                parent::current()."</td>";
        }
        function beginChildren() {
                echo "<tr>";
        }
        function endChildren() {
            echo "</tr>"."\n";
        }
}
$username = 'root';
$password = '';
try
{
    $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare('SELECT city,location FROM guesthouses');
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) 
as $k => $v) {echo $v;}}
catch (PDOException $e)
{
    echo 'ERROR: ' . $e->getMessage();
}
$conn = null;
echo "</table>";

?>


