<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
header("Content-Type: application/json; charset=UTF-8");
$servername = "localhost";
$username = "stargazers";
$password = "esp32";
$dbname = "RandomProject";

// Create connection using mysql_pconnect
$conn = mysql_pconnect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysql_error());
}

// Select the database
$db_selected = mysql_select_db($dbname, $conn);
if (!$db_selected) {
    die("Can't use $dbname : " . mysql_error());
}

$data = json_decode(file_get_contents('php://input'), true);
$score = $data['score'];
$name = $data['name'];
$timestamp = date('Y-m-d H:i:s');
$time = $data['time'];

$sql = "INSERT INTO table1 (score, name, timestamp, time) VALUES ('$score', '$name', '$timestamp','$time')";

// Execute the query
$result = mysql_query($sql, $conn);
if ($result) {
    echo json_encode($score);
} else {
    echo "Error: " . $sql . "<br>" . mysql_error();
}

// Close the connection
mysql_close($conn);
?>
