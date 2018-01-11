<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/> <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script> <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
        <title> Room Selection </title>
        <script> $(document).ready(function() { $("#datepicker_from").datepicker(); }); </script>
        <script> $(document).ready(function() { $("#datepicker_to").datepicker(); }); </script>
         
</head>
    <body>
<?php
    // $comment = "Enter your comments here";
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
if (isset($_POST["datefrom"]))
    {
        $datefrom = $_POST["datefrom"];
    }
 else {
        $datefrom = '';
}
if (isset($_POST["dateto"]))
    {
        $dateto = $_POST["dateto"];
    }
 else {
        $dateto = '';
}
if (isset($_POST["textarea"]))
    {
        $comment = $_POST["textarea"];
    }
 else {
        $comment = "";
}
    // $value2pass["guesthouse"] = $guesthouse;
    // echo "Welcome, ".$value2pass["guestname"]."<br";
    echo "Rooms available in ".$value2pass["guesthouse"];
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
 if (!(isset($_POST["datefrom"])) || !(isset($_POST["dateto"])) || (empty($_POST["datefrom"])) || (empty($_POST["dateto"]))) 
         {echo "Choose dates";}
         else {
             // check if room is booked for selected dates
             $value2pass["datefrom"] = date('Y-m-d', strtotime($_POST["datefrom"]));
             $value2pass["dateto"] = date('Y-m-d', strtotime($_POST["dateto"]));
             if (isset($_POST["textarea"]))
            {
                $value2pass['comment'] = mysql_real_escape_string($_POST["textarea"]);
            }
            else {
                $value2pass['comment'] = '';
            }
                if ($value2pass["datefrom"] >= $value2pass["dateto"] || $value2pass["datefrom"] < date('Y-m-d'))
                {
                    echo "Wrong dates";
                    echo "</td>";
                    echo "</tr>"."\n";
                    return;
                }
             $username = 'root';
             $password = '';
        try
        {
            $unavailable = 0;
            $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmttxt = 'SELECT count(*) as booked FROM `bookings` WHERE (cast("'.$value2pass["datefrom"].'" as date) <= datefrom and cast("'.$value2pass["dateto"] .'" as date) >= dateto or cast("'.$value2pass["datefrom"].'" as date) >= datefrom and cast("'.$value2pass["datefrom"].'" as date) < dateto or cast("'.$value2pass["dateto"].'" as date) > datefrom and cast("'.
$value2pass["dateto"].'" as date) <= dateto) and roomnumber = '.$value2pass["room"].' and guesthouseID in (select guesthouseID from guesthouses where city = "'.$value2pass["guesthouse"].'" )';
            $stmt = $conn->prepare($stmttxt);
            // echo $stmttxt;
            $stmt->execute();
            // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt-> fetch();
            $unavailable = $result['booked'];
        }
       catch (PDOException $e)
{
    echo 'ERROR: ' . $e->getMessage();
}
$conn = null;      
             
       if ($unavailable)
       {
             echo "Unavailable";}
       else {
            echo '<form name="form" method="POST" action="__bookingConfirm.php">';
        // echo '<input value="'; 
         
        
        $result = base64_encode(serialize($value2pass));
        echo '<input value="';
        echo $result;
        echo '" type="hidden" name="booking">';
        echo '<input type="submit" value="Book">';
        echo '</form>';
}
        }

            
            
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
<p><b> Date from: </b> </p>
<form name = "formdf" method="POST" 
              action="__roomSelect.php"> <input id="datepicker_from" Name = "datefrom" value = "<?php echo $datefrom; ?>" onchange = "this.form.submit();" /> 
<p><b> Date to: </b> </p>    
    
<input id="datepicker_to" Name = "dateto" value = "<?php echo $dateto; ?>" onchange = "this.form.submit();"  /> 
    <br><br>
<p><b>Enter your comments below:</b> </p>  
<!--<textarea name="textarea" rows="10" cols="50">Write something here</textarea>-->
    <textarea name="textarea" rows="10" cols="50" onchange = "this.form.submit();"><?PHP print $comment ; ?></textarea> </form>
<!--<INPUT TYPE = "TEXT" size="50" VALUE ="" NAME="comment">-->
<?php
    // $value2pass["datefrom"] = $datepicker_from;
    // $value2pass["dateto"] = $datepicker_to;
?>
    <br><br>
    <a href = "__connect2db_btn.php">Back</a>
</body>
</html>


