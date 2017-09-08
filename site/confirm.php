<?php

    //Save url parameters in variables
    $user_id = $_GET['id'];
    $token = $_GET['token'];



    //Connection to the database
    require 'inc/db.php';



    //Execute the query and save the result
    //Retrieve the user whose ID is passed as a parameter
    $req = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $req->execute([$user_id]);
    $user = $req->fetch();

    session_start();


    //If the token of the query corresponds to the one passed as a parameter
    //set the confirmation date and delete the token in the Database
    if($user && $user->confirmation_token == $token){
        $req = $pdo->prepare("UPDATE users SET confirmation_token = null, confirmed_at = NOW() WHERE id = ?");
        $req->execute([$user_id]);

        //Send a confirmation message
        $_SESSION['flash']['success'] = "Votre compte a bien été validé";

        $_SESSION['auth'] = $user;   //Save the user in the session
        header('location: account.php');
    }
    else{
        $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
        header('location: login.php');
    }

?>
