<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

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
if (!$result) {
    echo "Error: " . $sql . "<br>" . mysql_error();
}

$query = "SELECT * FROM table1"; // Replace 'table1' with your actual table name
$result = mysql_query($query, $conn);

if (!$result) {
    die("Error in query: " . mysql_error());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table Data</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
        }
    </style>
</head>
<body>

<h2>Data from the Table</h2>

<table>
    <tr>
        <th>Score</th>
        <th>Name</th>
        <th>Timestamp</th>
        <th>Time</th>
    </tr>

    <?php
    // Loop through each row and display it
    while ($row = mysql_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['score'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['timestamp'] . "</td>";
        echo "<td>" . $row['time'] . "</td>";
        echo "</tr>";
    }
    mysql_close($conn); // Close the connection
    ?>

</table>

</body>
</html>
