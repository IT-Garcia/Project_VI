<?php 
session_start(); // Starts a session and creates a session variable

// Hardcoded GLOBAL username and password for demonstration purposes
$validUsername = "brandon";
$validPassword = "1234";

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $username = $_POST['username'];  // Retrieves the username from the POST request
    $password = $_POST['password'];  // Retrieves the password from the POST request

    // Hard coded authentication
    if ($username === $validUsername && $password === $validPassword) 
    {
        // Stores the username in a session variable
        $_SESSION['username'] = $username;
        // Stores the password in a session variable (Note: storing passwords in plain text is not secure, **DEAL WITH THIS LATER**)
        $_SESSION['password'] = $password;
            
        // Redirect to the members-only page
        header('Location: member.php');
        exit();
    } 
    else 
    {
        // Outputs an error message if username or password is incorrect
        echo "<p>Failed to confirm credentials, invalid username or password. <a href=\"login.html\">Try again</a></p>";
    }    
} 
// Check if the user is already logged in
elseif (isset($_SESSION['username']) && isset($_SESSION['password']) && $_SESSION['username'] === $validUsername && $_SESSION['password'] === $validPassword)  
{
    // Outputs an access message
    echo "<p>You're already logged into the Elevator Project!.</p>";
    // Provides a link to the members-only page
    echo "<p>Click <a href=\"member.php\">here</a> to be taken to our super secret members only page</p>"; 
}
// Redirect to login page if accessed directly
else
{
    header('Location: login.html');
    exit();
}
?>

