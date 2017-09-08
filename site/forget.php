<?php
    //load autoloader
    require 'inc/autoloader.php';


    //Check that no form fields are empty
    if(!empty($_POST) && !empty($_POST['email'])){
        //Connection to the database
        //Initialize User Authentication
        //create session
        //And send an email to regenerate the user's password
        $db = App::getDatabase();
        $auth = App::getAuth();
        $session = Session::getInstance();
        if($auth->resetPassword($db, $_POST['email'])){
            $session->setFlash("success", "Les instructions du rappel de mot mot de passe vous ont été renvoyées par email.");
            App::redirect('login.php');
        }
        else{
            $session->setFlash("danger", "Aucun compte ne correspond à cet email");
        }
    }
?>



<!-- Include the page header file -->
<?php require 'inc/header.php'; ?>



<h1>Mot de passe oublié</h1>

<!-- Display the register form -->
<form action="" method="post">
    <div class="form-group">
        <label for="">Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>



<!-- Include the page footer file -->
<?php require 'inc/footer.php'; ?>
