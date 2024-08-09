<?php
    
    require_once('includes/dbh.inc.php');
    
    // Query to group data by day and floor
    $sql = "SELECT DATE(date) as date, requestedFloor, COUNT(*) as count FROM elevatorNetwork GROUP BY DATE(date), requestedFloor ORDER BY date ASC, requestedFloor ASC";
    $stmt = $pdo->query($sql);
    
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php
      include 'head_info.php';
    ?>
    <title>Charts</title>
</head>

<body>
    <?php
        include 'navmenu.php';
    ?>

    <!-- Prepare the data -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var chartData = <?php echo json_encode($data); ?>;
        
        // Initialize arrays and objects
        var dates = [];
        var floors = {};
        chartData.forEach(function(record) {
            if (!dates.includes(record.date)) {
                dates.push(record.date);  // Collect unique dates
            }
            if (!floors[record.requestedFloor]) {
                floors[record.requestedFloor] = Array(dates.length).fill(0);  // Initialize floor data array with zeros
            }
        });

        // Populate floor data arrays with counts, aligning with the correct date index
        chartData.forEach(function(record) {
            var dateIndex = dates.indexOf(record.date);
            floors[record.requestedFloor][dateIndex] = record.count;
        });

        // Floor colors (pre-defined for consistency)
        var floorColors = {
            1: 'rgba(249, 200, 14,  0.8)', 
            2: 'rgba(45,  226, 230, 0.8)', 
            3: 'rgba(247, 6,   207, 0.8)', 
        };

        var datasets = [];
        for (var floor in floors) {
            datasets.push({
                label: 'Floor ' + floor,
                data: floors[floor],
                backgroundColor: floorColors[floor] || 'rgba(0, 0, 0, 0.8)' // Default color if not specified
            });
        }
    </script>

    

    <!-- Display the data -->
    <div id="chartDiv">
        <canvas id="myChart"></canvas>
    </div>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: datasets
            },
            options: {
                responsive: true, 
                maintainAspectRatio: true,
                scales: {
                    x: {
                            stacked: false, // Disable stacking on the x-axis
                            ticks: {
                                color: '#00f2ff', // Change the font color for the x-axis labels
                                font: {
                                    size: 20 // Change the font size for the x-axis labels
                                }
                            },
                            title: {
                                display: true,
                                text: 'Date', // Label for the x-axis
                                color: '#00f2ff', // Change the font color for the x-axis title
                                font: {
                                    size: 22 // Change the font size for the x-axis title
                                }
                            }
                        },
                    y: {
                        beginAtZero: true,
                        stacked: false, // Ensure the y-axis is not stacked
                        ticks: {
                            color: '#00f2ff', // Change the font color for the y-axis labels
                            font: {
                                size: 20 // Change the font size for the y-axis labels
                            }
                        },
                        title: {
                            display: true,
                            text: 'Number of Requests', // Label for the y-axis
                            color: '#00f2ff', // Change the font color for the y-axis title
                            font: {
                                size: 22 //font size for the y-axis title
                            }
                        }
                    }
                },

                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 16 // Legend font size
                            },
                            color: '#00f2ff',
                            boxWidth: 30,
                            padding: 30
                        },
                        
                        
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: true 
                    },
                    title: {
                        display: true,
                        text: 'Floor Requests Per Day',
                        font: {
                            size: 40,
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        },
                        color: '#00f2ff',
                    }
                }
            }
        });
    </script>

    
</body>
</html>