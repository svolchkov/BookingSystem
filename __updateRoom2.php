<?php
    session_start();
?>
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
        
        <script> $(function(){
	//acknowledgement message
        
    var message_status = $("#status");
    $("div[contentEditable]").blur(function(){  
        var field_userid = $(this).attr("id") ;
        var value = $(this).text() ;
        $.post('__updateRoom2.php' , field_userid + "=" + value, function(data){
            // alert("Yeah!");
            if(data != '')
			{
				message_status.show();
				message_status.text(data);
				//hide the message
				setTimeout(function(){message_status.hide()},3000);
			}
        });
    });
}); </script>
    </head>
    <body>
        <?php
        
        $value2pass = [];
        $room = 0;
        $username = 'root';
        $password = '';
   
    if(!(isset($_POST["search"])) && !empty($_POST))
    // if( !empty($_POST))
{   echo "OK";
	//database settings
	
	foreach($_POST as $field_name => $val)
	{
		//clean post values
		$field_userid = strip_tags(trim($field_name));
		$val = strip_tags(trim(mysql_real_escape_string($val)));

		//from the fieldname:user_id we need to get user_id
		$split_data = explode(':', $field_userid);
		$user_id = $split_data[1];
		$field_name = $split_data[0];
		if(!empty($user_id) && !empty($field_name) && !empty($val))
		{
                    try
{
    $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $stmttxt = 'SELECT roomnumber, beds, cost, extra '
    //        . 'FROM rooms where guesthouseid in (select guesthouseid from'
    //        . 'guesthouses where city =  "'.$guesthouse.'")';
    $stmttxt = 'UPDATE rooms SET '.$field_name.' = "'.$val.'" WHERE RoomNumber = '.$user_id.' AND guesthouseID in (select guesthouseID from guesthouses where city = "'.$_SESSION["guesthouse"].'" )';
    $stmt = $conn->prepare($stmttxt);
    $stmt->execute();
			//update the values
			
echo "Updated";}
catch (PDOException $e)
{
    echo 'ERROR: ' . $e->getMessage();
}
$conn = null;
		} else {
			echo "Invalid Requests";
		}
	}
} 
//else {
//	echo "Invalid Requests";
//}
    if (isset($_POST["search"]))
    {
        $value2pass = unserialize(base64_decode($_POST["search"]));
        // $value2pass
        $_SESSION["guesthouse"] = $value2pass["guesthouse"];
        unset($_POST["search"]);
        // $_SESSION["guesthouseID"] = $value2pass["guesthouseID"];
    }
// else {
//       $value2pass["guesthouse"] = $_SESSION["guesthouse"];
//       // $value2pass["guestname"] = $_SESSION["guestname"];
//       // $value2pass["guestID"] = $_SESSION["guestID"];
//       // $value2pass["guesthouseID"] = $_SESSION["guesthouseID"];
//}

    // $value2pass["guesthouse"] = $guesthouse;
    // echo "Welcome, ".$value2pass["guestname"]."<br";
    echo "Rooms in ".$_SESSION["guesthouse"];
    echo "<br><br>"; 
//echo '<div contenteditable="true">';    
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Room #</th><th>Beds</th><th>Cost</th><th>Extra Info</th></tr>";
class TableRows extends RecursiveIteratorIterator {
        
    function __construct($it) {
        parent::__construct($it,self::LEAVES_ONLY);
        }
        function current() {
                
                $current = parent::current();
                $key = parent::key();
                global $room;
                if ($key == 'roomnumber') {
                    
                        global $value2pass;
                        $value2pass["room"] = $current;
                        $room = $current;
                    
                }
                // $cell = '<td id="'.$key.':'.$room.'" contenteditable="true" style="width:150px;border:1px solid black;">' . 
                // parent::current().'</td>';
                $cell = '<td  class="editable" style="width:150px;border:1px solid black;"><div id="'.$key.':'.$room.'" contentEditable="true" style="width: 100%; height: 100%;">' . 
                parent::current().'</div></td>';
                return $cell;
        }
        function beginChildren() {
                echo "<tr>";
                
        }
        
        function endChildren() {
//            global $value2pass;
//            echo "<td>";
// 
//            echo '<form name="form" method="POST" action="__updateRoom2.php">';
//        // echo '<input value="'; 
//         
//        
//        $result = base64_encode(serialize($value2pass));
//        echo '<input value="';
//        echo $result;
//        echo '" type="hidden" name="booking">';
//        echo '<input type="submit" value="Update">';
//        echo '</form>';
//}
//        }

            
            
//            echo "</td>";
            echo "</tr>"."\n";
        
        }
}

try
{
    $conn = new PDO('mysql:host=localhost;dbname=guesthouse',$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $stmttxt = 'SELECT roomnumber, beds, cost, extra '
    //        . 'FROM rooms where guesthouseid in (select guesthouseid from'
    //        . 'guesthouses where city =  "'.$guesthouse.'")';
    $stmttxt = 'SELECT roomnumber,beds,cost,extra FROM rooms WHERE guesthouseid in (select guesthouseID from guesthouses where city = "'.$_SESSION["guesthouse"].'" )';
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
// echo "</div>";
echo "<br><br>";
// $thispage = '';
$_SESSION["ok2update"] = "true";
        ?>
        <br><br><a href = "__guesthouseSelect.php">Back to guesthouse selection</a>
    
    </body>
</html>

