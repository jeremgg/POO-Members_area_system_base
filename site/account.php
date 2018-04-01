<?php
    //load autoloader
    require 'inc/autoloader.php';

    //Connection to the database
    //Initialize User Authentication
    //create session
    $db = App::getDatabase();
    $auth = App::getAuth();
    $session = Session::getInstance();


    //Initialize User Authentication
    //And if the user is not logged in, he does not have access to his personal page
    $auth->logged_only();


    //Verify that the password change form sent data and that the password fields have the same value
    //And change the password in the database
    if(!empty($_POST)){
        if(empty($_POST['password']) || empty($_POST['password-confirm'])){
            $session->setFlash("danger", "Les champs du formulaire doivent être renseignés");
        }
        else if($_POST['password'] != $_POST['password-confirm']){
            $session->setFlash("danger", "Les mots de passes ne correspondent pas");
        }
        else{
            $auth->modifyPassword($db, $_POST['password']);
            $session->setFlash("success", "Votre mot de passe a été changé avec succès");
        }
    }
?>



<!-- Include the page header file -->
<?php require 'inc/header.php'; ?>



<!-- Display user's nickname -->
<h1>bonjour <?= $_SESSION['auth']->username; ?> et bienvenue !!!</h1>



<!-- Display the form to change the password -->
<form action="" method="post">
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Changer votre mot de passe">
    </div>
    <div class="form-group">
        <input type="password" name="password-confirm" class="form-control" placeholder="Confirmation de votre mot de passe">
    </div>
    <button class="btn btn-primary">Changer mon  mot de passe</button>
</form>



<!-- Include the page footer file -->
<?php require 'inc/footer.php'; ?>
