<?php
class WordGameController {

    private $command;

    public function __construct($command) {
        $this->command = $command;
    }

    public function run() {
        switch($this->command) {
            case "game":
                $this->game();
                break;
            case "newgame":
                $this->newgame();
                break;
            case "gameover":
                $this->gameover();
                break;
            case "logout":
                $this->destroySession();
            case "login":
            default:
                $this->login();
                break;
        }
    }

    private function destroySession() {          
        session_destroy();
    }
    

    // Display the login page (and handle login logic)
    public function login() {
        if (isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["name"]) && !empty($_POST["name"])) {
            $_SESSION["name"] = $_POST["name"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["guesses"] = 0;
            $_SESSION["correct"] = 0;
            $history = (array) null;
            $_SESSION["guessHistory"] = json_encode($history);

            // load the word
            $word = $this->loadWord();
            if ($word == null) {
                die("No words available");
            }
            $_SESSION["word"] = $word;
            header("Location: ?command=game");
            return;
        }

        include "templates/login.php";
    }

    // Load a word from the API
    private function loadWord() {
        $wordData = file(
            "https://www.cs.virginia.edu/~jh2jf/courses/cs4640/spring2022/wordlist.txt", FILE_IGNORE_NEW_LINES);
        // Return the word
        $k = array_rand($wordData);
        return $wordData[$k];
    }

    // Display the game template (and handle game logic)
    public function game() {
        // set user information for the page from the session
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "guesses" => $_SESSION["guesses"],
            "correct" => $_SESSION["correct"], 
            "guessHistory" => json_decode($_SESSION["guessHistory"], true),
            "word" => $_SESSION["word"],
        ];


        $message = "";

        // if the user submitted an guess, check it
        if (isset($_POST["guess"])) {
            $guess = strtolower($_POST["guess"]);
            $user["guesses"] += 1;
            $_SESSION["guesses"] = $user["guesses"];
            $numP = 0;
            $numIW = 0;
            for ($i=0; $i<strlen($guess); $i++) {
                if ($i < strlen($user["word"])) {
                    if ($guess[$i] == $user["word"][$i]) {
                        $numP++;
                    }
                }
                if (strpos($user["word"], $guess[$i]) !== false) {
                    $numIW++;
                }
            }
            if (strlen($guess) < strlen($user["word"])) {
                $length = "too short";
            } elseif (strlen($guess) > strlen($user["word"])) {
                $length = "too long";
            } else {
                $length = "same length";
            }
            $arr = array("guess"=>$guess, "numPosition"=>$numP, "numInWord"=>$numIW, "length"=>$length);
            array_push($user["guessHistory"], $arr);
            $_SESSION["guessHistory"] = json_encode($user["guessHistory"]);
            if ($user["word"] == $guess) {
                $_SESSION["correct"] = 1;
                header("Location: ?command=gameover");
            } else { 
                $message = "<div class='alert alert-danger'><b>Previous Guesses: (letters in correct position, letters in the correct word, length)</b><br><hr>";
                foreach ($user["guessHistory"] as $num => $arr) {
                    $message .= $arr["guess"] . ": (";
                    $message .= $arr["numPosition"] . ",  ";
                    $message .= $arr["numInWord"] . ", ";
                    $message .= $arr["length"] . ")";
                    $message .= "<br>";
                }
                $message .= "</div>";
            }
        }

        include("templates/game.php");
    }

    public function newgame() {
        $_SESSION["guesses"] = 0;
        $_SESSION["correct"] = 0;
        $history = (array) null;
        $_SESSION["guessHistory"] = json_encode($history);
        // load the word
        $word = $this->loadWord();
        if ($word == null) {
            die("No words available");
        }
        $_SESSION["word"] = $word;
        header("Location: ?command=game");
    }

    public function gameover() {
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "guesses" => $_SESSION["guesses"],
            "correct" => $_SESSION["correct"],
            "guessHistory" => $_SESSION["guessHistory"],
            "word" => $_SESSION["word"]
        ];
        if ($user["correct"]) {
            $message = "Congrats";
        } else {
            $message = "Sorry";
        }
        if ($user["guesses"] === "1") {
            $guessmsg = "1 guess";
        } else {
            $guessmsg = $user["guesses"] . " guesses";
        }
        include("templates/gameover.php");
    }
}