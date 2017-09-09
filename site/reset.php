<?php
    //load autoloader
    require 'inc/autoloader.php';


    //Check that we have url id and token parameters
    //and retrieving the corresponding user
    if(isset($_GET['id']) && isset($_GET['token'])){
        //Initialize User Authentication
        //Connection to the database
        //et vérifier que le token correspond avec celui de la base de données
        $auth = App::getAuth();
        $db = App::getDatabase();
        $user = $auth->checkResetToken($db, $_GET['id'], $_GET['token']);

        //If the user wants to regenerate his password
        if($user){
            //Verify that the password change form sent data
            //And that the password fields have the same value
            if(!empty($_POST)){
                $db->query('UPDATE users SET password = ? WHERE id = ?', [$password, $user->id]);

                //validate the form data
                $validateForm = new ValidateForm($_POST);

                // Verify that the password fields are not empty
                $validateForm->isConfirmed('password');

                if($validateForm->isValid()){
                    //Encrypt the user's password
                    $password = $auth->hashPassword($_POST['password']);

                    //Update the password in the database
                    $db->query('UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL WHERE id = ?', [$password, $_GET['id']]);

                    //Connecter l'utilisateur
                    $auth->connect($user);

                    Session::getInstance()->setFlash('success','Votre mot de passe a bien été modifié');
                    App::redirect('account.php');
                }
            }
        }

        //If the token no longer exists or has expired
        //Create session and send a confirmation message
        else{
            Session::getInstance()->setFlash('danger',"Ce token n'est pas valide");
            App::redirect('login.php');
        }
    }
    else{
        App::redirect('login.php');
    }
?>



<?php require 'inc/header.php'; ?>



<h1>Réinitialiser mon mot de passe</h1>

<form action="" method="POST">
    <div class="form-group">
        <label for="">Mot de passe</label>
        <input type="password" name="password" class="form-control"/>
    </div>

    <div class="form-group">
        <label for="">Confirmation du mot de passe</label>
        <input type="password" name="password_confirm" class="form-control"/>
    </div>

    <button type="submit" class="btn btn-primary">Réinitialiser votre mot de passe</button>
</form>



<?php require 'inc/footer.php'; ?>
