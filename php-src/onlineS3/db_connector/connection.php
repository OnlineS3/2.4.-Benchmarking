
<?php
function conn_db() {

	$servername = 'localhost';
	$username = 'root';
	$password = '25ye$ando5';
	$dbname = 'benchmarking';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	return $conn;
}
?>