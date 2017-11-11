
<?php
echo "<table>";
$conn = new PDO("mysql:host=localhost;dbname=daowatco_td","daowatco_td","sinEmi4334222");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql="select users.first_name,users.email,users.gender from users where users.chatOnlineTime>=now()-5";
$result=$conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $v){
echo "<tr><td>".$v['first_name']."</td><td><img id=online src='online.png'></td></tr>";
}

$conn=null;
echo "</table>";
?>