<?php
    //Include file containing site functions
    require_once 'inc/functions.php';


    session_start();


    //Verify that data has been send via the registration form
    if(!empty($_POST)){

        //If there are errors, they are saves in an array
        $errors = array();


        //Connection to the database
        require_once 'inc/db.php';


        //Check that the username and email fields are not empty and that they are in the correct format
        if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])){
            $errors['username'] = "Votre pseudo n'est pas valide";
        }
        else{
            //Verify that the nickname does not exist in the database
            $req = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $req->execute([$_POST['username']]);
            $user = $req->fetch();

            //If the nickname is already taken from the database, an error message is displayed
            if($user){
                $errors['username'] = "Ce pseudo est déja prit";
            }
        }

        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "Votre email n'est pas valide";
        }
        else{
            //Verify that the email does not exist in the database
            $req = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $req->execute([$_POST['email']]);
            $user = $req->fetch();

            //If the email is already taken from the database, an error message is displayed
            if($user){
                $errors['email'] = "Cet email est déja utilisé";
            }
        }


        //Check that the password field is not empty
        //and that it is the same as the password confirmation field
        if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
            $errors['password'] = "Votre mot de passe n'est pas valide";
        }


        //If there are no errors, the user is register in the database
        if(empty($errors)){
            $req = $pdo->prepare("INSERT INTO users SET username = ?, email = ?, password = ?, confirmation_token = ?");

            //Encrypt the user's password and execute the request
            $password = password_hash ($_POST['password'], PASSWORD_BCRYPT);
            $token = str_random(60);  //define a random number of 60 digits
            $req->execute([$_POST['username'], $_POST['email'], $password, $token]);

            //Retrieve the last generated id and send an email confirmation of the mail address
            $user_id = $pdo->lastInsertId();
            mail($_POST['email'], "Confirmation de votre compte", "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost:8888/php/POO/member_area/confirm.php?id=$user_id&token=$token");

            //Send a confirmation message
            $_SESSION['flash']['success'] = "Un email de confirmation vous a été envoyé pour valider votre compte";

            //Redirect the user to the login page
            header('Location:login.php');
            exit();
        }
    }
?>




<!-- Include the page header file -->
<?php require 'inc/header.php'; ?>




<h1>S'inscrire</h1>


<!-- Display an error message if the form contains errors -->
<?php  if(!empty($errors)) : ?>
    <div class="alert alert-danger">
        <p>Vous n'avez pas rempli le formulaire correctement</p>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<!-- Display the register form -->
<form action="" method="post">
    <div class="form-group">
        <label for="">Pseudo</label>
        <input type="text" name="username" class="form-control">
    </div>

    <div class="form-group">
        <label for="">Email</label>
        <input type="text" name="email" class="form-control">
    </div>

    <div class="form-group">
        <label for="">Mot de passe</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="form-group">
        <label for="">Confirmer votre mot de passe</label>
        <input type="password" name="password_confirm" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">M'inscrire</button>
</form>



<!-- Include the page footer file -->
<?php require 'inc/footer.php'; ?>
