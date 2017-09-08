<?php
    //load autoloader
    require_once 'inc/autoloader.php';



    //Verify that data has been send via the registration form
    if(!empty($_POST)){

        //If there are errors, they are saves in an array
        $errors = array();


        //Connection to the database
        $db = App::getDatabase();


        //validate the form data
        $validateForm = new ValidateForm($_POST);


        //check that the username field is not empty and that its data is in the correct format
        //Check that the username do not exist in the database
        //and if the nickname is already in the database, an error message is displayed
        $validateForm->isAlphanumeric("username", "Votre pseudo n'est pas valide");
        if($validateForm->isValid()){
            $validateForm->isUniq("username", $db, "users", "Ce pseudo est déja pris");
        }


        //check that the email field is not empty and that its data is in the correct format
        //Check that the username do not exist in the database
        //and if the nickname is already in the database, an error message is displayed
        $validateForm->isEmail("email", "Votre email n'est pas valide");
        if($validateForm->isValid()){
            $validateForm->isUniq("email", $db, "users", "Cet email est déja utilisé");
        }


        // Verify that the password fields are not empty
        // and have the same value
        $validateForm->isConfirmed("password", "Votre mot de passe n'est pas valide");


        //If there are no errors, the user is register in the database
        if($validateForm->isValid()){
            //initialiser l'authentification de l'utilisateur
            //et enregistrer l'utilisateur dans la base de données avec les données du formulaire
            App::getAuth()->register($db,  $_POST['username'], $_POST['email'], $_POST['password']);

            //Send a confirmation message
            Session::getInstance()->setFlash("success", "Un email de confirmation vous a été envoyé pour valider votre compte");

            //Redirect the user to the login page
            App::redirect('login.php');
        }
        else{
            //display error messages
            $errors = $validateForm->getErrors();
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
