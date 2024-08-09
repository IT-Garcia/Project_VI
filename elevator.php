<!-- ================================= FINAL CODE ============================================= -->
<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        echo "<h1>User not logged in</h1>";
        header("Location: ./login.php");
        exit();
    }
    require_once('includes/config_session.inc.php');
    require_once('includes/dbh.inc.php');
    require_once('includes/elevator_handler.inc.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Saira:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/elevator_terminal.css">
    
    <script src="scripts/scripts.js"></script>    
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
    <!-- <meta http-equiv="refresh" content="5"> -->
    <title>Elevator Interface</title>
</head>

<body>
    <?php
      include 'navmenu.php';
    ?>

    <?php    
    
        try {
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
            $curFlr = get_currentFloor($pdo);
            $reqFlr = get_requestedFloor($pdo);

            // If Sabbath mode is active
            if ($_SESSION['sabbathMode']){
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
                            requestFloor($pdo, $curFlr, 1);
                        } elseif ($nextFloor == 2 || $nextFloor == 0) {
                            requestFloor($pdo, $curFlr, 2);
                        } elseif ($nextFloor == 3) {
                            requestFloor($pdo, $curFlr, 3);
                        }

                        // Reset the refresh counter
                        $_SESSION['refreshCounter'] = 0;
                    }
                }
            } 
            else{
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
            } catch (Exception $e) {
                die("Query failed: " . $e->getMessage());
            }
        ?>


    <?php
                // Handle new floor requests
        if (isset($_POST['newfloor'])) {
            $newFlr = requestFloor($pdo, $curFlr, $_POST['newfloor']);
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

    
    <div id="terminal">
    <h1>Elevator 2 Terminal</h1>
        <?php
             // Display current and requested floor, and the Sabbath mode floor counter
            echo "<h2>Current floor # $curFlr </h2>";
            echo "<h2>Requested floor # $reqFlr </h2>";
            echo "<h2>Sabbath mode floor counter: " . $_SESSION['floorCounter'] . "</h2>";
        ?>
    
    
        <form action="./elevator.php" method="POST">
            
                <h2>Elevator Call Buttons</h2>
                <div id="elevator_buttons">
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
                </div>
                    
                <h2>Sabbath Mode</h2>
                <div class="sabbath_buttons">
                    <button class="sabbath_button" type="submit" name="sabbathMode" value="on">On</button>
                    <button class="sabbath_button" type="submit" name="sabbathMode" value="off">Off</button>
                </div>
                
                <?php

                    echo "<h2>Elevator Command Status</h2>";
                    showStatus($pdo);
                ?>
            </form>
            <form action="includes/logout.inc.php" method="POST">
                        <button>Logout</button>
            </form>
        </div>
    


    <?php
      include 'footer.php';
    ?>
    
    
</body>
</html>