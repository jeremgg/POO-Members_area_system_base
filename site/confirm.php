<?php

    //load autoloader
    require 'inc/autoloader.php';


    //Connection to the database
    $db = App::getDatabase();


    //Initialize User Authentication
    $auth = new Auth($db);


    //If the token of the query corresponds to the one passed as a parameter
    //set the confirmation date and delete the token in the Database
    if($auth->confirm($_GET['id'], $_GET['token'], Session::getInstance())){
        //Send a confirmation message
        Session::getInstance()->setFlash("success", "Votre compte a bien été validé");
        App::redirect('account.php');
    }
    else{
        Session::getInstance()->setFlash("danger", "Ce token n'est plus valide");
        App::redirect('login.php');
    }

?>
