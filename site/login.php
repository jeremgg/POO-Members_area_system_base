<?php

    //load autoloader
    require 'inc/autoloader.php';


    //Initialize User Authentication
    //Connection to the database
    //And automatically reconnect the user if the login cookie exists
    $auth = App::getAuth();
    $db = App::getDatabase();
    $auth->connectFromCookie($db);


    //if the user is already logged in, he is redirected to his personal page
    if($auth->user()){
        App::redirect('account.php');
    }


    //Check that the user is trying to connect and that no form fields are empty
    //If this is the case, the user is connected
    if(!empty($_POST)){
        //create session
        $session = Session::getInstance();

        if(!empty($_POST['username']) && !empty($_POST['password'])){
            $user = $auth->login($db, $_POST['username'], $_POST['password'], isset($_POST['remember']));
            if($user){
                $session->setFlash("success", "Bienvenue, vous êtes connecté à votre compte");
                App::redirect('account.php');
            }
            else{
                $session->setFlash("danger", "Identifiant ou mot de passe incorrect");
            }
        }
        else{
            $session->setFlash("danger", "Tous les champs doivent être renseignés");

        }
    }

?>



<!-- Include the page header file -->
<?php require 'inc/header.php'; ?>



<h1>Se connecter</h1>

<!-- Display the register form -->
<form action="" method="post">
    <div class="form-group">
        <label for="">Pseudo ou Email</label>
        <input type="text" name="username" class="form-control">
    </div>

    <div class="form-group">
        <label for="">Mot de passe<a href="forget.php">(J'ai oublié mon mot de passe)</a></label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="remember" value="1"/>Se souvenir de moi
        </label>
    </div>

    <button type="submit" class="btn btn-primary">Se connecter</button>
</form>



<!-- Include the page footer file -->
<?php require 'inc/footer.php'; ?>
