<?php
// member.php
// this page is protected to members only even when accessed directly
session_start();  // Required for every page where you call or declare a session

// Hardcoded GLOBAL username and password for demonstration purposes
$validUsername = "brandon";
$validPassword = "1234";

// Make sure users that are not logged in do not have access to this page
if (isset($_SESSION['username']) && $_SESSION['username'] === $validUsername && isset($_SESSION['password']) && $_SESSION['password'] === $validPassword)
{
    // Add 'members only' content here
    echo "<p>Greetings, " . $_SESSION['username'] . "! You've successfully infiltrated our secret lair.</p>";
    echo "<p>As a trusted member, you now have access to our top-secret plans for world domination. Shh... don't tell anyone!</p>";
    echo "<p>Remember, with great power comes great responsibility. Use your newfound knowledge wisely.</p>";
    echo "<p>Click to <a href='logout.php'>Logout</a> and disappear without a trace when your mission is complete.</p>";
} 
// Outputs a message when accessed directly without credentials
else 
{   
    echo "<p>You must be logged in to access this classified information!</p>";
    echo "<p>Confirm your credentials <a href=\"login.html\">Here</a></p>";    
}
?>