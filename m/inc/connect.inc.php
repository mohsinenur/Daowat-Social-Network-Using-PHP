<?php 
mysql_connect("localhost","daowatco_td","sinEmi4334222") or die("Couldn't connet to SQL server");
mysql_select_db("daowatco_td") or die("Couldn't select DB");

//time formate
function formatDate($date){
	return date('g:i a', strtotime($date));
}

?>