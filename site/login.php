<?php

    //load autoloader
    require 'inc/autoloader.php';

    //Include file containing site functions
    require_once 'inc/functions.php';


    //Automatically reconnect the user if the login cookie exists
    reconnect_from_cookie();


    //if the user is already logged in, he is redirected to his personal page
    if(isset($_SESSION['auth'])){
        header('location: account.php');
        exit();
    }


    //verify that the user is trying to connect and that no form fields are empty
    if(!empty($_POST)){
        if(!empty($_POST['username']) && !empty($_POST['password'])){
            ///Save the user according to the email or pseudo entered in the form
            //and that the user has a validated account
            require_once 'inc/db.php';
            $req = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
            $req->execute(['username' => $_POST['username']]);
            $user = $req->fetch();


            //If the password entered by the user matches the password entered in the database
            //redirect the user to his personal page
            if(password_verify($_POST['password'], $user->password)){
                $_SESSION['auth'] = $user;
                $_SESSION['flash']['success'] = "Bienvenue, vous êtes connecté à votre compte";

                //If the user checks the checkbox remember
                if($_POST['remember']){
                    $remember_token = str_random(255);
                    $req = $pdo->prepare('UPDATE users SET remember_token = ? WHERE id = ?');
                    $req->execute([$remember_token, $user->id]);

                    //The result of the request is saved in a relatively complex cookie
                    setcookie('remember', $user->id . '==' . $remember_token . sha1($user->id . 'phone'), time() + 60 * 60 * 24 * 7);
                }

                header('location: account.php');
                exit();
            }
            else{
                $_SESSION['flash']['danger'] = "Identifiant ou mot de passe incorrect";
            }
        }

        else{
            $_SESSION['flash']['danger'] = "Tous les champs doivent être renseignés";
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
