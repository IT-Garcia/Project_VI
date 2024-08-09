<?php

declare(strict_types=1);

//Insert new content
function insertContent(object $pdo, $status, $currentFloor, $requestedFloor, $otherInfo) {
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
 function updateContent(object $pdo, $nodeID, $status, $currentFloor, $requestedFloor, $otherInfo) {
    try {   
        // Utilize Transactions to avoid incomplete data transfer and potential corruption 
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
 function deleteContent($pdo, $nodeID){
    try{   
        // Utilize Transactions to avoid incomplete data transfer and potential corruption 
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
function showContent(object $pdo){
    try{
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

//request a new floor
function requestFloor(object $pdo, $currentFloor, int $new_floor = 1): int {
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

//Display the current status
function showStatus(object $pdo) {
    try {
        $query = "SELECT * FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1";
        $stmt = $pdo->query($query);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <table border="1" cellpadding="10">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Node ID</th>
                    <th>Status</th>
                    <th>Current Floor</th>
                    <th>Requested Floor</th>
                    <th>Other Info</th>
                </tr>
                <tr>
                    <td><?php echo $row["date"]; ?></td>
                    <td><?php echo $row["time"]; ?></td>
                    <td><?php echo $row["nodeID"]; ?></td>
                    <td><?php echo $row["status"]; ?></td>
                    <td><?php echo $row["currentFloor"]; ?></td>
                    <td><?php echo $row["requestedFloor"]; ?></td>
                    <td><?php echo $row["otherInfo"]; ?></td>
                </tr>
            </table>
            <?php
        } else {
            echo "No results found.";
        }

    } catch (Exception $e) {
        echo 'Failed to display status: ' . $e->getMessage();
    }
}


function get_currentFloor(object $pdo): int {
    $query = 'SELECT currentFloor FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1';
    $statement = $pdo->query($query);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result['currentFloor'] ?? -1;
}

// Get the requested floor
function get_requestedFloor(object $pdo): int {
    // Query the database to get the requested floor from the most recent entry
    $query = 'SELECT requestedFloor FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1';
    $statement = $pdo->query($query);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    // Return the requested floor or -1 if the query fails
    return $result['requestedFloor'] ?? -1;
}



