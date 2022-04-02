<?php
// https://cs4640.cs.virginia.edu/eb4ub/hw4/
// Sources used: https://cs4640.cs.virginia.edu (used trivia game as starting point)
// Register the autoloader
spl_autoload_register(function($classname) {
    include "classes/$classname.php";
});

session_start();

// Parse the query string for command
$command = "login";
if (isset($_GET["command"]))
    $command = $_GET["command"];

if (!isset($_SESSION["email"]) || !isset($_SESSION["name"])) {
    // they need to see the login
    $command = "login";
}

// Instantiate the controller and run
$wordgame = new WordGameController($command);
$wordgame->run();