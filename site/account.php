<?php
    //load autoloader
    require 'inc/autoloader.php';


    //Initialize User Authentication
    //And if the user is not logged in, he does not have access to his personal page
    App::getAuth()->logged_only();


    //Verify that the password change form sent data
    //And that the password fields have the same value
    if(!empty($_POST)){
        if(empty($_POST['password']) || $_POST['password'] != $_POST['password-confirm']){
            $_SESSION['flash']['danger'] = "Les mots de passes ne correspondent pas";
        }
        else{
            $user_id = $_SESSION['auth']->id;

            //Encrypt the user's password
            $password= password_hash($_POST['password'], PASSWORD_BCRYPT);

            //Update the password in the database
            require_once 'inc/db.php';
            $req = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $req->execute([$password, $user_id]);

            $_SESSION['flash']['success'] = "Votre mot de passe a été changé avec succès";
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
