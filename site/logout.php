<?php

    //load autoloader
    require 'inc/autoloader.php';


    //Initialize User Authentication
    //and disconnect the user
    App::getAuth()->logout();


    //Display a disconnection confirmation message
    Session::getInstance()->setFlash("success", "Vous êtes maintenant déconnecté");


    //Redirect the user to the login page
    App::redirect('login.php');
?>
