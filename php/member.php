<?php
session_start();  // Required for every page where you call or declare a session

// File containing usernames and passwords
$jsonFile = __DIR__ . '/../JSON/data.json';

// Make sure users that are not logged in do not have access to this page
if (isset($_SESSION['username']) && isset($_SESSION['password'])) 
{
    // Check if the JSON file exists and is readable
    if (file_exists($jsonFile) && is_readable($jsonFile)) 
    {
        // Read the contents of the JSON file
        $contents = file_get_contents($jsonFile);
        // Decode the JSON data into a PHP array
        $usersArray = json_decode($contents, true);

        // Function to authenticate user
        function authenticateUser($username, $password, $usersArray) 
        {
            foreach ($usersArray['users'] as $user) 
            {
                if ($user['username'] === $username && $user['password'] === $password) 
                {
                    return true;
                }
            }
            return false;
        }

        // Database connection function
        function connectDatabase($path, $user, $password) 
        {
            try 
            {
                $db = new PDO($path, $user, $password);
                $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $db; 
            } 
            catch (PDOException $e) 
            {
                throw new Exception('Failed to connect to database: ' . $e->getMessage());
            }
        }

        // Function to insert new content into elevatorNetwork
        function insertContent($db, $status, $currentFloor, $requestedFloor, $otherInfo) 
        {
            try 
            {   // Utilize Transactions to avoid incomplete data transfer and potential corruption 
                $db->beginTransaction();
                
                // Query for current time
                $queryTime = "SELECT CURRENT_TIME()";
                $result = $db->query($queryTime);
                $curtime = $result->fetch()['CURRENT_TIME()'];

                // Query for current date
                $queryDate = "SELECT CURRENT_DATE()";
                $result = $db->query($queryDate);
                $curdate = $result->fetch()['CURRENT_DATE()'];

                // Prepared statement with additional date and time fields
                $query = 'INSERT INTO elevatorNetwork (status, currentFloor, requestedFloor, otherInfo, date, time) VALUES (:status, :currentFloor, :requestedFloor, :otherInfo, :curdate, :curtime)';
                $statement = $db->prepare($query);
                // Bind the values for each respective field
                $statement->bindValue(':status', $status);
                $statement->bindValue(':currentFloor', $currentFloor);
                $statement->bindValue(':requestedFloor', $requestedFloor);
                $statement->bindValue(':otherInfo', $otherInfo);
                $statement->bindValue(':curdate', $curdate);
                $statement->bindValue(':curtime', $curtime);
                $statement->execute();

                // Commit the transaction
                $db->commit();
            } 
            catch (Exception $e) 
            {
                // Rollback the transaction if something failed
                $db->rollBack();
                throw new Exception('Transaction failed: ' . $e->getMessage());
            }
        }

        // Function to update content in elevatorNetwork
        function updateContent($db, $nodeID, $status, $currentFloor, $requestedFloor, $otherInfo) 
        {
            try 
            {   // Utilize Transactions to avoid incomplete data transfer and potential corruption 
                $db->beginTransaction();
                
                // Query for current time
                $queryTime = "SELECT CURRENT_TIME()";
                $result = $db->query($queryTime);
                $curtime = $result->fetch()['CURRENT_TIME()'];

                // Query for current date
                $queryDate = "SELECT CURRENT_DATE()";
                $result = $db->query($queryDate);
                $curdate = $result->fetch()['CURRENT_DATE()'];

                // Prepared statement with additional date and time fields
                $query = 'UPDATE elevatorNetwork SET status = :status, currentFloor = :currentFloor, requestedFloor = :requestedFloor, otherInfo = :otherInfo, date = :curdate, time = :curtime WHERE nodeID = :nodeID';
                $statement = $db->prepare($query);
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
                $db->commit();
            } 
            catch (Exception $e) 
            {
                // Rollback the transaction if something failed
                $db->rollBack();
                throw new Exception('Transaction failed: ' . $e->getMessage());
            }
        }

        // Function to delete content from elevatorNetwork
        function deleteContent($db, $nodeID) 
        {
            try 
            {   // Utilize Transactions to avoid incomplete data transfer and potential corruption 
                $db->beginTransaction();

                // Prepared statement for deletion
                $query = 'DELETE FROM elevatorNetwork WHERE nodeID = :nodeID';
                $statement = $db->prepare($query);
                // Delete based off selected nodeID
                $statement->bindValue(':nodeID', $nodeID);
                $statement->execute();

                // Commit the transaction
                $db->commit();
            } 
            catch (Exception $e) 
            {
                // Rollback the transaction if something failed
                $db->rollBack();
                throw new Exception('Transaction failed: ' . $e->getMessage());
            }
        }

        // Function to display content from elevatorNetwork (Reformats Date and Time for display)
        function showContent($db) 
        {
            try 
            {
                $query = "SELECT * FROM elevatorNetwork";
                $rows = $db->query($query);
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
            $db = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'ese', 'ese');
            try {
                $db->beginTransaction();
                
                $queryTime = "SELECT CURRENT_TIME()";
                $result = $db->query($queryTime);
                $curtime = $result->fetch()['CURRENT_TIME()'];

                $queryDate = "SELECT CURRENT_DATE()";
                $result = $db->query($queryDate);
                $curdate = $result->fetch()['CURRENT_DATE()'];

                $query = 'INSERT INTO elevatorNetwork (status, currentFloor, requestedFloor, otherInfo, date, time) VALUES (:status, :currentFloor, :requestedFloor, :otherInfo, :curdate, :curtime)';
                $statement = $db->prepare($query);
                $statement->bindValue(':status', 2);
                $statement->bindValue(':currentFloor', $currentFloor);
                $statement->bindValue(':requestedFloor', $new_floor);
                $statement->bindValue(':otherInfo', "Processing Remote Command: Floor $new_floor Requested");
                $statement->bindValue(':curdate', $curdate);
                $statement->bindValue(':curtime', $curtime);
                $statement->execute();

                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                echo 'Transaction failed: ' . $e->getMessage();
                return -1;
            }
            return $new_floor;
        }

        function showStatus() {
            $db = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'ese', 'ese');
            try {
                $query = "SELECT * FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1";
                $rows = $db->query($query);

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
                $db = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'ese', 'ese');
            } catch (PDOException $e) {
                echo $e->getMessage();
                return -1;
            }

            $query = 'SELECT currentFloor FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1';
            $statement = $db->query($query);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result['currentFloor'] ?? -1;
        }

        function get_requestedFloor(): int {
            try {
                // Establish a connection to the database using PDO
                $db = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'ese', 'ese');
            } catch (PDOException $e) {
                // If there is a connection error, display the error message and return -1
                echo $e->getMessage();
                return -1;
            }

            // Query the database to get the requested floor from the most recent entry
            $query = 'SELECT requestedFloor FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1';
            $statement = $db->query($query);
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
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Members Terminal</title>
            <style>
                .button-active {
                    background-color: yellow;
                }
            </style>
            <script>
                // Function to highlight the button corresponding to the requested floor
                function highlightButton(floor) {
                    var buttons = document.querySelectorAll('button[name="newfloor"]');
                    buttons.forEach(function(button) {
                        if (button.value == floor) {
                            button.classList.add('button-active');
                        } else {
                            button.classList.remove('button-active');
                        }
                    });
                }

                // Function to play the audio announcement for the requested floor
                function playAudio(floor) {
                    if (floor !== null) {
                        var audio = new Audio('../audio/Floor' + floor + '.ogg');
                        audio.play();
                    }
                }

                // Function to execute when the page loads
                window.onload = function() {
                    var reqFlr = <?php echo json_encode($reqFlr); ?>;
                    var curFlr = <?php echo json_encode($curFlr); ?>;
                    var playAudioFlag = <?php echo json_encode($_SESSION['playAudio']); ?>;
                    var sabbathMode = <?php echo json_encode($_SESSION['sabbathMode']); ?>;

                    // Highlight the requested floor button if the current floor is different
                    if (curFlr != reqFlr) {
                        highlightButton(reqFlr);
                    } else {
                        highlightButton(null);
                        // Play the audio if the playAudio flag is set and Sabbath mode is not active
                        if (playAudioFlag !== null && !sabbathMode) {
                            playAudio(playAudioFlag);
                            <?php $_SESSION['playAudio'] = null; ?>
                        }
                    }

                    // Play the audio if the playAudio flag is set and Sabbath mode is active
                    if (sabbathMode && playAudioFlag !== null) {
                        playAudio(playAudioFlag);
                        <?php $_SESSION['playAudio'] = null; ?>
                    }
                };       
            </script>
            <!-- Automatically refresh the page every second -->
            <meta http-equiv="refresh" content="1">
        </head>
        <body>
            <h1>Elevator 2 Terminal</h1>

            <?php
                // Handle new floor requests
                if (isset($_POST['newfloor'])) {
                    $newFlr = requestFloor($curFlr, $_POST['newfloor']);
                    $_SESSION['refreshCounter'] = 0; // Reset counter when a new floor is requested
                    header('Location: member.php'); // Redirect to avoid resubmission on refresh
                    exit();
                }

                // Handle Sabbath mode activation/deactivation
                if (isset($_POST['sabbathMode'])) {
                    $_SESSION['sabbathMode'] = $_POST['sabbathMode'] === 'on';
                    $_SESSION['refreshCounter'] = 0; // Reset refresh counter
                    $_SESSION['floorCounter'] = 0; // Reset floor counter
                    header('Location: member.php'); // Redirect to avoid resubmission on refresh
                    exit();
                }

                // Display current and requested floor, and the Sabbath mode floor counter
                echo "<h2>Current floor # $curFlr </h2>";
                echo "<h2>Requested floor # $reqFlr </h2>";
                echo "<h2>Sabbath mode floor counter: " . $_SESSION['floorCounter'] . "</h2>";
                echo "<h2>Elevator Call Buttons</h2>";
            ?>

            <h2>
                <form action="member.php" method="POST">
                    <button type="submit" name="newfloor" value="1" style="width:50px; height:40px;">1</button>
                    <button type="submit" name="newfloor" value="2" style="width:50px; height:40px;">2</button>
                    <button type="submit" name="newfloor" value="3" style="width:50px; height:40px;">3</button>
                    <label style="margin-left: 300px;" for="sabbathMode">Sabbath Mode: </label>
                    <button type="submit" name="sabbathMode" value="on">On</button>
                    <button type="submit" name="sabbathMode" value="off">Off</button>
                </form>
            </h2>
            </body>
        </html>
        <?php
            echo "<h2>Elevator Command Status</h2>";
            showStatus();

            // Check if json decoding was successful and authenticate user
            if ($usersArray !== null && isset($usersArray['users']) && authenticateUser($_SESSION['username'], $_SESSION['password'], $usersArray)) 
            {
                // Add 'members only' content here
                echo "<p>Greetings, " . $_SESSION['username'] . "! You've successfully infiltrated our evil elevator.</p>";
                echo "<p>As a trusted member, you now have access to control our top-secret weapon for world domination. Shh... don't tell anyone!</p>";
                echo "<p>Remember, with great power comes great responsibility. Use your command privileges wisely.</p>";
                echo "<p>Click to <a href='logout.php'>Logout</a> and disappear without a trace when your mission is complete.</p>";

            } 
            else 
            {
                echo "<p>Failed to decode user credentials! Please try again.</p>";
                echo "<p>Confirm your credentials <a href=\"../login.html\">Here</a></p>"; 
            }
        } 
        else 
        {
            echo '<p>Unable access user data. Please try again later.</p>';
        }
    } 
    else 
    {
        echo "<p>You must be logged in to access this classified information!</p>";
        echo "<p>Confirm your credentials <a href=\"../login.html\">Here</a></p>";
        echo "<p>Or else! >:(</p>";     
    }
?>
