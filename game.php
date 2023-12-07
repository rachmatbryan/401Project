<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
date_default_timezone_set('America/Los_Angeles'); 

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
if (!empty($data)) {
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
}

$query = "SELECT * FROM table1"; // Replace 'table1' with your actual table name
$result = mysql_query($query, $conn);

if (!$result) {
    die("Error in query: " . mysql_error());
}

?>

<!DOCTYPE html>
<html lang="en">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<head>
    <meta charset="UTF-8">
    <title>Table Data</title>
    <style>
        :root {
            --kls-primary-color: #d2e2d1;
            --kls-secondary-color: #9BB899;
            --kls-tertiary-color: #576656;
            --kls-highlight-color: #f2ffb6;
            --kls-dark-color: #353433;
            --kls-darker-color: #1f1e1e;
            --kls-light-color: #F0F0F0;
            --kls-background-color: #fbfcf8;
            --kls-title-font: 'Poiret One', sans-serif;
            --kls-subtitle-font: 'Aboreto', sans-serif;
            --kls-heading-font: 'Aboreto', sans-serif;
            --kls-body-font: 'Quicksand', sans-serif;
            --kls-font-size: 1.3rem;
            --kls-font-weight: 400;
            --kls-line-height: 1.5rem;
            --kls-title-color: var(--kls-dark-color);
            --kls-page-background: #ffffff; /* Page Background */
            --kls-accent-color: #7189bf; /* Lighter blue for accents */
        }

        body {
            font-family: var(--kls-body-font);
            font-size: var(--kls-font-size);
            font-weight: var(--kls-font-weight);
            line-height: var(--kls-line-height);
            color: var(--kls-darker-color);
            background: var(--kls-page-background);
            margin: 0;
            padding: 0;
        }

        h2 {
            font-family: var(--kls-heading-font);
            color: var(--kls-accent-color);
            text-align: center;
            margin: 0;
        }
        
        h3 {
            margin-top: 80px;
        }

        table, th, td {
            border: 1px solid var(--kls-accent-color);
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            background-color: var(--kls-light-color);
            color: var(--kls-dark-color);
        }

        th {
            background-color: var(--kls-secondary-color);
            color: var(--kls-light-color);
        }
        
         #sortLabel {
        font-size: 42px; 
    }

        .sort-select {
            margin-bottom: 10px;
            color: var(--kls-dark-color);
            background-color: var(--kls-light-color);
            padding: 5px;
            border: 1px solid var(--kls-dark-color);
            border-radius: 5px;
            cursor: pointer;
            font-size: 30px;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: var(--kls-tertiary-color);
            padding: 40px 0;
        }

        .logo {
            height: 105px;
            width: auto;
            position: absolute;
            right: 10px;
            top: -3px;
        }

        .centered-container {
            text-align: center;
            width: 100%;
            padding-top: 54px;
        }

        .centered-container table {
            margin-left: auto;
            margin-right: auto;
            margin-top: 25px;
            font-size: 35px;
            width: 90%; 
            border-spacing: 10px; 
            border-collapse: separate; 
}

        .centered-container th, .centered-container td {
            padding: 15px; 
            text-align: center; 
            border: 1px solid #ccc; 
        }
        
        .chart-container {
        position: relative;
        height: 40vh;
        width: 80vw;
        margin-top: 70px; 
        margin-left: auto;
        margin-right: auto;
    }

    </style>
</head>
<body>
<div class="header">
    <h2>Cognitive Games</h2>
    <img src="stargazers.png" alt="Logo" class="logo">
</div>

<div class="centered-container">
    <div>
        <label id="sortLabel" for="sortOrder">Sort Scores:</label>
        <select id="sortOrder" class="sort-select" onchange="sortTable()">
            <option value="default">Default</option>
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select>
    </div>

<table id="scoresTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Timestamp</th>
            <th>Time</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
        
        <?php
        // PHP code to fetch data from the database
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['timestamp'] . "</td>";
            echo "<td>" . $row['time'] . "</td>";
            echo "<td>" . $row['score'] . "</td>";
            echo "</tr>";
        }
        mysql_close($conn);
        ?>
        
    </tbody>
</table>

<h3> Score Vs Time </h3>
<div class="chart-container" style="position: relative; height:40vh; width:80vw; margin-bottom: 30px;">
    <canvas id="scoreTimeChart"></canvas>
</div>

<div class="chart-container" style="position: relative; height:40vh; width:80vw; margin-top: 50px;">
    <canvas id="timestampTimeChart"></canvas>
</div>

<h3> Scores By Game </h3>
<div class="chart-container" style="position: relative; height:40vh; width:80vw; margin-top: 50px;">
    <canvas id="nameScoreDistributionChart"></canvas>
</div>



<script>
    var originalRows = [];

    window.onload = function() {
        var table = document.getElementById("scoresTable");
        var rows = table.getElementsByTagName("tr");
        for (var i = 1; i < rows.length; i++) {
            originalRows.push(rows[i].outerHTML);
        }
    }

    function sortTable() {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("scoresTable");
        switching = true;
        dir = document.getElementById("sortOrder").value;

        if (dir === "default") {
            table.tBodies[0].innerHTML = originalRows.join("");
            return;
        }

        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[3];
                y = rows[i + 1].getElementsByTagName("TD")[3];
                if (dir == "asc") {
                    if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            }
        }
    }
    
    
function drawBarChart() {
    var table = document.getElementById('scoresTable');
    var scores = [];
    var times = [];

    // Loop through table rows and extract data
    for (var i = 1; i < table.rows.length; i++) {
        scores.push(table.rows[i].cells[0].innerHTML); 
        times.push(table.rows[i].cells[3].innerHTML); 
    }

    // Setup the chart data
    var data = {
        labels: scores,
        datasets: [{
            label: 'Time',
            data: times,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    // Configurations for the chart
    var config = {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Time',
                        fontSize: 16
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Score',
                        fontSize: 16
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y + ' seconds';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    };


    var myChart = new Chart(
        document.getElementById('scoreTimeChart'),
        config
    );
}


function drawLineChart() {
    var table = document.getElementById('scoresTable');
    var timestamps = [];
    var times = [];

  
    for (var i = 1; i < table.rows.length; i++) {
        timestamps.push(table.rows[i].cells[2].innerHTML); 
        times.push(parseFloat(table.rows[i].cells[3].innerHTML)); 
    }

    // Setup the chart data
    var lineData = {
        labels: timestamps,
        datasets: [{
            label: 'Time over Timestamp',
            data: times,
            fill: false,
            borderColor: 'rgba(255, 99, 132, 1)',
            tension: 0.1,
        }]
    };


    var lineConfig = {
        type: 'line',
        data: lineData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Time',
                        fontSize: 16
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Timestamp',
                        fontSize: 16
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    };

    var myLineChart = new Chart(
        document.getElementById('timestampTimeChart'),
        lineConfig
    );
}

function drawPieChart() {
    var table = document.getElementById('scoresTable');
    var names = [];
    var scores = [];
    var nameScoreMap = {};

    var backgroundColors = [
        'rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'
    ];
    var borderColors = [
        'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
    ];

    for (var i = 1; i < table.rows.length; i++) {
        var name = table.rows[i].cells[0].innerHTML; 
        var score = parseFloat(table.rows[i].cells[3].innerHTML); 
        if (!nameScoreMap[name]) {
            nameScoreMap[name] = 0;
        }
        nameScoreMap[name] += score;
    }

    // Convert the nameScoreMap into arrays for chart
    var dataSetColors = [];
    var dataSetBorderColors = [];
    var colorIndex = 0;
    for (var name in nameScoreMap) {
        names.push(name);
        scores.push(nameScoreMap[name]);
        dataSetColors.push(backgroundColors[colorIndex % backgroundColors.length]);
        dataSetBorderColors.push(borderColors[colorIndex % borderColors.length]);
        colorIndex++;
    }

    // Setup the chart data
    var pieData = {
        labels: names,
        datasets: [{
            label: 'Score',
            data: scores,
            backgroundColor: dataSetColors,
            borderColor: dataSetBorderColors,
            borderWidth: 1
        }]
    };

    var pieConfig = {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    };

    var myPieChart = new Chart(
        document.getElementById('nameScoreDistributionChart'),
        pieConfig
    );
}

    
// Call chart functions
drawBarChart();
drawLineChart();
drawPieChart();

</script>

</body>
</html>
