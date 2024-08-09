<!-- ================================= FINAL CODE ============================================= -->
<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        echo "<h1>User not logged in</h1>";
        header("Location: ./login.php");
        exit();
    }

    require_once('includes/dbh.inc.php');
    require_once('includes/config_session.inc.php');
    require_once('includes/elevator_handler.inc.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include 'head_forms.php';
    ?>
    <script>
           
            
        </script>
        <!-- Automatically refresh the page every second -->
        <!-- <meta http-equiv="refresh" content="1"> -->

    <title>Elevator Interface</title>
</head>
<body>
    <?php
      include 'navmenu.php';
    ?>

    <?php
        
        try {
            function insertContent($pdo, $status, $currentFloor, $requestedFloor, $otherInfo) 
            {
                try 
                {   // Utilize Transactions to avoid incomplete data transfer and potential corruption 
                    $pdo->beginTransaction();
                    
                    // Query for current time
                    $queryTime = "SELECT CURRENT_TIME()";
                    $result = $pdo->query($queryTime);
                    $curtime = $result->fetch()['CURRENT_TIME()'];
    
                    // Query for current date
                    $queryDate = "SELECT CURRENT_DATE()";
                    $result = $pdo->query($queryDate);
                    $curdate = $result->fetch()['CURRENT_DATE()'];
    
                    // Prepared statement with additional date and time fields
                    $query = 'INSERT INTO elevatorNetwork (status, currentFloor, requestedFloor, otherInfo, date, time) VALUES (:status, :currentFloor, :requestedFloor, :otherInfo, :curdate, :curtime)';
                    $statement = $pdo->prepare($query);
                    // Bind the values for each respective field
                    $statement->bindValue(':status', $status);
                    $statement->bindValue(':currentFloor', $currentFloor);
                    $statement->bindValue(':requestedFloor', $requestedFloor);
                    $statement->bindValue(':otherInfo', $otherInfo);
                    $statement->bindValue(':curdate', $curdate);
                    $statement->bindValue(':curtime', $curtime);
                    $statement->execute();
    
                    // Commit the transaction
                    $pdo->commit();
                } 
                catch (Exception $e) 
                {
                    // Rollback the transaction if something failed
                    $pdo->rollBack();
                    throw new Exception('Transaction failed: ' . $e->getMessage());
                }
            }
    
            // Function to update content in elevatorNetwork
            function updateContent($pdo, $nodeID, $status, $currentFloor, $requestedFloor, $otherInfo) 
            {
                try 
                {   // Utilize Transactions to avoid incomplete data transfer and potential corruption 
                    $pdo->beginTransaction();
                    
                    // Query for current time
                    $queryTime = "SELECT CURRENT_TIME()";
                    $result = $pdo->query($queryTime);
                    $curtime = $result->fetch()['CURRENT_TIME()'];
    
                    // Query for current date
                    $queryDate = "SELECT CURRENT_DATE()";
                    $result = $pdo->query($queryDate);
                    $curdate = $result->fetch()['CURRENT_DATE()'];
    
                    // Prepared statement with additional date and time fields
                    $query = 'UPDATE elevatorNetwork SET status = :status, currentFloor = :currentFloor, requestedFloor = :requestedFloor, otherInfo = :otherInfo, date = :curdate, time = :curtime WHERE nodeID = :nodeID';
                    $statement = $pdo->prepare($query);
                    // Bind the values for each respective field
                    $statement->bindValue(':status', $status);
                    $statement->bindValue(':currentFloor', $currentFloor);
                    $statement->bindValue(':requestedFloor', $requestedFloor);
                    $statement->bindValue(':otherInfo', $otherInfo);
                    $statement->bindValue(':curdate', $curdate);
                    $statement->bindValue(':curtime', $curtime);
                    $statement->bindValue(':nodeID', $nodeID);
                    $statement->execute();
    
                    // Commit the transaction
                    $pdo->commit();
                } 
                catch (Exception $e) 
                {
                    // Rollback the transaction if something failed
                    $pdo->rollBack();
                    throw new Exception('Transaction failed: ' . $e->getMessage());
                }
            }
    
            // Function to delete content from elevatorNetwork
            function deleteContent($pdo, $nodeID) 
            {
                try 
                {   // Utilize Transactions to avoid incomplete data transfer and potential corruption 
                    $pdo->beginTransaction();
    
                    // Prepared statement for deletion
                    $query = 'DELETE FROM elevatorNetwork WHERE nodeID = :nodeID';
                    $statement = $pdo->prepare($query);
                    // Delete based off selected nodeID
                    $statement->bindValue(':nodeID', $nodeID);
                    $statement->execute();
    
                    // Commit the transaction
                    $pdo->commit();
                } 
                catch (Exception $e) 
                {
                    // Rollback the transaction if something failed
                    $pdo->rollBack();
                    throw new Exception('Transaction failed: ' . $e->getMessage());
                }
            }
    
            // Function to display content from elevatorNetwork (Reformats Date and Time for display)
            function showContent($pdo) 
            {
                try 
                {
                    $query = "SELECT * FROM elevatorNetwork";
                    $rows = $pdo->query($query);
                    echo "<table border='1'>";
                    echo "<tr><th>Date</th><th>Time</th><th>NodeID</th><th>Status</th><th>Current Floor</th><th>Requested Floor</th><th>Other Info</th><th>Actions</th></tr>";
                    foreach ($rows as $row) 
                    {
                        echo "<tr>";
                        foreach ($row as $key => $column) 
                        {
                            if ($key == 'date') 
                            {
                                $dateTime = new DateTime($column);
                                $formattedDate = $dateTime->format('M-d-Y'); // Alphanumeric date format
                                echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
                            } 
                            elseif ($key == 'time') 
                            {
                                $dateTime = new DateTime($column);
                                $formattedTime = $dateTime->format('h:i:s A'); // 12-hour time format
                                echo "<td>" . htmlspecialchars($formattedTime) . "</td>";
                            } 
                            else 
                            {
                                echo "<td>" . htmlspecialchars($column) . "</td>";
                            }
                        }
                        echo "<td>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='nodeID' value='" . $row['nodeID'] . "'>
                                    <input type='submit' name='edit' value='Edit'>
                                </form>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='nodeID' value='" . $row['nodeID'] . "'>
                                    <input type='submit' name='delete' value='Delete'>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } 
                catch (Exception $e) 
                {
                    echo 'Failed to display content: ' . $e->getMessage();
                }
            }
    
            function requestFloor($currentFloor, int $new_floor = 1): int {
                $pdo = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'softenguser', '%yTxaZnkAgO8C0am');
                try {
                    $pdo->beginTransaction();
                    
                    $queryTime = "SELECT CURRENT_TIME()";
                    $result = $pdo->query($queryTime);
                    $curtime = $result->fetch()['CURRENT_TIME()'];
    
                    $queryDate = "SELECT CURRENT_DATE()";
                    $result = $pdo->query($queryDate);
                    $curdate = $result->fetch()['CURRENT_DATE()'];
    
                    $query = 'INSERT INTO elevatorNetwork (status, currentFloor, requestedFloor, otherInfo, date, time) VALUES (:status, :currentFloor, :requestedFloor, :otherInfo, :curdate, :curtime)';
                    $statement = $pdo->prepare($query);
                    $statement->bindValue(':status', 2);
                    $statement->bindValue(':currentFloor', $currentFloor);
                    $statement->bindValue(':requestedFloor', $new_floor);
                    $statement->bindValue(':otherInfo', "Processing Remote Command: Floor $new_floor Requested");
                    $statement->bindValue(':curdate', $curdate);
                    $statement->bindValue(':curtime', $curtime);
                    $statement->execute();
    
                    $pdo->commit();
                } catch (Exception $e) {
                    $pdo->rollBack();
                    echo 'Transaction failed: ' . $e->getMessage();
                    return -1;
                }
                return $new_floor;
            }
    
            function showStatus() {
                $pdo = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'softenguser', '%yTxaZnkAgO8C0am');
                try {
                    $query = "SELECT * FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1";
                    $rows = $pdo->query($query);
    
                    if ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
                        echo "<table border='1'>";
                        echo "<tr><th>Date</th><th>Time</th><th>NodeID</th><th>Status</th><th>Current Floor</th><th>Requested Floor</th><th>Other Info</th></tr>";
                        echo "<tr>";
    
                        foreach ($row as $key => $column) {
                            if ($key == 'date') {
                                $dateTime = new DateTime($column);
                                $formattedDate = $dateTime->format('M-d-Y');
                                echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
                            } elseif ($key == 'time') {
                                $dateTime = new DateTime($column);
                                $formattedTime = $dateTime->format('h:i:s A');
                                echo "<td>" . htmlspecialchars($formattedTime) . "</td>";
                            } else {
                                echo "<td>" . htmlspecialchars($column) . "</td>";
                            }
                        }
    
                        echo "</tr>";
                        echo "</table>";
                    } else {
                        echo "No entries found in the database.";
                    }
                } catch (Exception $e) {
                    echo 'Failed to display status: ' . $e->getMessage();
                }
            }
    
            function get_currentFloor(): int {
                try {
                    $pdo = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'softenguser', '%yTxaZnkAgO8C0am');
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return -1;
                }
    
                $query = 'SELECT currentFloor FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1';
                $statement = $pdo->query($query);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result['currentFloor'] ?? -1;
            }
    
            function get_requestedFloor(): int {
                try {
                    // Establish a connection to the database using PDO
                    $pdo = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'softenguser', '%yTxaZnkAgO8C0am');
                } catch (PDOException $e) {
                    // If there is a connection error, display the error message and return -1
                    echo $e->getMessage();
                    return -1;
                }
    
                // Query the database to get the requested floor from the most recent entry
                $query = 'SELECT requestedFloor FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1';
                $statement = $pdo->query($query);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                // Return the requested floor or -1 if the query fails
                return $result['requestedFloor'] ?? -1;
            }
    
            // Initialize session variables if they are not already set
            if (!isset($_SESSION['refreshCounter'])) {
                $_SESSION['refreshCounter'] = 0;
            }
    
            if (!isset($_SESSION['sabbathMode'])) {
                $_SESSION['sabbathMode'] = false;
            }
    
            if (!isset($_SESSION['floorCounter'])) {
                $_SESSION['floorCounter'] = 0;
            }
    
            if (!isset($_SESSION['playAudio'])) {
                $_SESSION['playAudio'] = null;
            }
    
            // Get the current and requested floor values
            $curFlr = get_currentFloor();
            $reqFlr = get_requestedFloor();
    
            // If Sabbath mode is active
            if ($_SESSION['sabbathMode']) 
            {
                // If the current floor matches the requested floor
                if ($curFlr == $reqFlr) 
                {
                    // If this is not the first activation of Sabbath mode
                    if (($_SESSION['refreshCounter'] == 0) && ($_SESSION['floorCounter'] != 0))
                    {
                        // Play the floor audio upon arrival
                        $_SESSION['playAudio'] = $reqFlr;
                    } 
    
                    // Increment the refresh counter
                    $_SESSION['refreshCounter']++;
                    if ($_SESSION['refreshCounter'] >= 10) {
                        // If 10 refreshes (10 seconds) have passed since arriving at the last floor
                        $_SESSION['floorCounter']++;
                        // Determine the next floor in the cycle based on the mod operator
                        $nextFloor = $_SESSION['floorCounter'] % 4;
    
                        // Request the next floor based on the floor counter value and input the current floor for database tracking
                        if ($nextFloor == 1) {
                            requestFloor($curFlr, 1);
                        } elseif ($nextFloor == 2 || $nextFloor == 0) {
                            requestFloor($curFlr, 2);
                        } elseif ($nextFloor == 3) {
                            requestFloor($curFlr, 3);
                        }
    
                        // Reset the refresh counter
                        $_SESSION['refreshCounter'] = 0;
                    }
                }
            } 
            else 
            {
                // If Sabbath mode is not active and the current floor does not match the requested floor
                if ($curFlr != $reqFlr) 
                {   // Increment the refresh counter
                    $_SESSION['refreshCounter']++;
                } 
                else 
                {
                    // If the refresh counter reaches 2, play audio for the requested floor
                    if ($_SESSION['refreshCounter'] >= 1) {
                        $_SESSION['playAudio'] = $reqFlr;
                        $_SESSION['refreshCounter'] = 0;
                    } 
                    else 
                    {
                        $_SESSION['playAudio'] = null;
                    }
                }
            }
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    ?>

    <h1>Elevator 2 Terminal</h1>

    <?php
        // Handle new floor requests
        if (isset($_POST['newfloor'])) {
            $newFlr = requestFloor($curFlr, $_POST['newfloor']);
            $_SESSION['refreshCounter'] = 0; // Reset counter when a new floor is requested
            header('Location: elevator.php'); // Redirect to avoid resubmission on refresh
            exit();
        }

        // Handle Sabbath mode activation/deactivation
        if (isset($_POST['sabbathMode'])) {
            $_SESSION['sabbathMode'] = $_POST['sabbathMode'] === 'on';
            $_SESSION['refreshCounter'] = 0; // Reset refresh counter
            $_SESSION['floorCounter'] = 0; // Reset floor counter
            header('Location: elevator.php'); // Redirect to avoid resubmission on refresh
            exit();
        }       
    ?>

    <div class="text">
        <?php
             // Display current and requested floor, and the Sabbath mode floor counter
            echo "<h2>Current floor # $curFlr </h2>";
            echo "<h2>Requested floor # $reqFlr </h2>";
            echo "<h2>Sabbath mode floor counter: " . $_SESSION['floorCounter'] . "</h2>";
        ?>
    </div>

    <form action="./elevator.php" method="POST">
            <h2>Elevator Call Buttons</h2>
            <!-- Floor 1 -->
            <button class="floor_button" id="floor_1" type="submit" name="newfloor" value="1">
                <img src="icons/number-1.png" alt="button_floor_1">
            </button>
            <!-- Floor 2 -->
            <button class="floor_button" id="floor_2" type="submit" name="newfloor" value="2">
                <img src="icons/number-2.png" alt="button_floor_2">
            </button>
            <!-- Floor 3 -->
            <button class="floor_button" id="floor_3" type="submit" name="newfloor" value="3">
                <img src="icons/number-3.png" alt="button_floor_3">
            </button>
            <h2>Sabbath Mode</h2>
            <button class="floor_button" type="submit" name="sabbathMode" value="on">On</button>
            <button class="floor_button" type="submit" name="sabbathMode" value="off">Off</button>

            <?php
                echo "<h2>Elevator Command Status</h2>";
                showStatus();
            ?>
        </form>


    

    <form action="includes/logout.inc.php" method="POST">
                <button>Logout</button>
    </form>



    <?php
      include 'footer.php';
    ?>
    
</body>
</html>