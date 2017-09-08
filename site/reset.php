<?php
    //Include file containing site functions
    require_once 'inc/functions.php';


    //Check that we have url id and token parameters
    //and retrieving the corresponding user
    if(isset($_GET['id']) && isset($_GET['token'])){
        require_once 'inc/db.php';
        $req = $pdo->prepare('SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
        $req->execute([$_GET['id'], $_GET['token']]);
        $user = $req->fetch();

        //If the user wants to regenerate his password
        if($user){
            //Verify that the password change form sent data
            //And that the password fields have the same value
            if(!empty($_POST)){
                if(!empty($_POST['password']) || $_POST['password'] == $_POST['password-confirm']){
                    //Encrypt the user's password
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                    //Update the password in the database
                    $req = $pdo->prepare('UPDATE users SET password = ?, reset_token = null, reset_at = null');
                    $req->execute([$password]);

                    //Save the user's login and redirect it to his personal page
                    session_start();
                    $_SESSION['flash']['success'] = "Votre mot de passe a été changé";
                    $_SESSION['auth'] = $user;
                    header('location: account.php');
                    exit();
                }
            }
        }


        //If the token no longer exists or has expired
        else{
            session_start();
            $_SESSION['flash']['danger'] = "Ce token n'est pas valide";
            header('location: login.php');
            exit();
        }
    }
    else{
        header('location: login.php');
        exit();
    }
?>



<!-- Include the page header file -->
<?php require 'inc/header.php'; ?>



<h1>Réinitialiser mon mot de passe</h1>

<!-- Display the register form -->
<form action="" method="post">
    <div class="form-group">
        <label for="">Mot de passe</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="form-group">
        <label for="">Confirmation du Mot de passe</label>
        <input type="password" name="password-confirm" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Réinitialiser mon mot de passe</button>
</form>



<!-- Include the page footer file -->
<?php require 'inc/footer.php'; ?>
