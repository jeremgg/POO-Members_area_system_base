<?php
    //Include file containing site functions
    require_once 'inc/functions.php';


    //verify that the user is trying to connect and that no form fields are empty
    if(!empty($_POST) && !empty($_POST['email'])){
        require_once 'inc/db.php';

        session_start();

        ///Save the user according to the email or pseudo entered in the form
        //and that the user has a validated account
        $req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
        $req->execute([$_POST['email']]);
        $user = $req->fetch();


        //If the user forgets his password, we generate a new password token
        if($user){
            $reset_token = str_random(60);
            $req = $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?');
            $req->execute([$reset_token, $user->id]);

            //Send an email confirmation and display a confirmation message
            mail($_POST['email'], "Réinitialisation de votre mot de passe", "Afin de réinitialiser votre mot de passe merci de cliquer sur ce lien\n\nhttp://localhost:8888/php/POO/member_area/reset.php?id={$user->id}&token=$reset_token");
            $_SESSION['flash']['success'] = "Les instructions du rappel de mot mot de passe vous ont été renvoyées par email.";

            header('location: login.php');
            exit();
        }
        else{
            $_SESSION['flash']['danger'] = "Aucun compte ne correspond à cet email";
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
