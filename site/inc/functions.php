<?php

    /**
     * Debug the code and display the value of a variable
     * @param  string  $variable  The variable to be debugged
     * @return string
     */
    function debug($variable){
        echo '<pre>' . print_r($variable, true) . '</pre>';
    }



    /**
     * Generate a random string with letters and numbers
     * Which are mixed and can be used several times
     * @param int  The number of characters
     * @return string
     */
    function str_random($length){
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }



    /**
     * If the user is not logged in, he does not have access to his personal page
     * and he is redirected to the login page
     */
    function logged_only(){
        if(!isset($_SESSION['auth'])){
            //If we do not have a session we create a
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page, vous devez être connecté";
            header('location: login.php');
            exit();
        }
    }



    /**
     * Automatically reconnect the user if the login cookie exists
     */
    function reconnect_from_cookie(){
        //If we do not have a session we create a
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        //Verify the presence of the remember cookie that memorizes the connection
        if(isset($_COOKIE['remember']) && !isset($_SESSION['auth'])){
            require_once 'db.php';

            if(!isset($pdo)){
                global $pdo;
            }

            $remember_token = $_COOKIE['remember'];

            //Retrieve the id of the user
            $parts = explode('==', $remember_token);
            $user_id = $parts[0];

            //Retrieve the user corresponding to the id of the user
            $req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
            $req->execute([$user_id]);
            $user = $req->fetch();

            //If the query returns a result, the user is automatically logged in
            if($user){
                $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'phone');
                if($expected == $remember_token){
                    $_SESSION['auth'] = $user;
                    setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                    unset($_SESSION['flash']);
                }
                else{
                    setcookie('remember', null, -1);
                }
            }
            else{
                setcookie('remember', null, -1);
            }
        }
    }



?>
