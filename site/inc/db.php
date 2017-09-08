<?php

    //Login to the database
    $pdo = new PDO('mysql:dbname=members;host=localhost', 'root', 'root');

    //Display sql errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Retrieve information as objects
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

?>
