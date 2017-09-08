<?php

    class Auth{

        //Initialize options property
        private $options = [
            "restrict_msg" => "error message"
        ];


        //Initialize session property
        private $session;



        /**
         * Merge property options and options in parameter
         * And initialize the session
         * @param $db The connection to the database
         */
         public function __construct($session, $options = []){
             $this->options = array_merge($this->options, $options);
             $this->session = $session;
         }



        /**
         * Register a new user
         * @param  $username
         * @param  $email
         * @param  $password
         */
        public function register($db, $username, $email, $password){
            //Encrypt the user's password and execute the query
            $password = password_hash($password, PASSWORD_BCRYPT);

            //define a random number of 60 digits
            $token = Str::random(60);

            //Execute the query
            $db->query("INSERT INTO users SET username = ?, email = ?, password = ?, confirmation_token = ?", [
                $username,
                $email,
                $password,
                $token
            ]);

            //Retrieve the last generated id and send an email confirmation of the mail address
            $user_id = $db->lastInsertId();
            mail($email, "Confirmation de votre compte", "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost:8888/tutoriaux/php/POO/member_area/confirm.php?id=$user_id&token=$token");
        }



        /**
         * Validate user registration
         * @param  $user_id  User ID
         * @param  $token    The token of the mail
         * @return bool
         */
        public function confirm($db, $user_id, $token){
            //Execute the query and save the result
            //Retrieve the user whose ID is passed as a parameter
            $user = $db->query("SELECT * FROM users WHERE id = ?", [$user_id])->fetch();

            //If the token of the query corresponds to the one passed as a parameter
            //set the confirmation date and delete the token in the Database
            if($user && $user->confirmation_token == $token){
                $db->query("UPDATE users SET confirmation_token = null, confirmed_at = NOW() WHERE id = ?", [$user_id]);

                //Save the user in the session
                $this->session->write('auth', $user);
                return true;
            }
            else{
                return false;
            }
        }



        /**
         * If the user is not logged in, he does not have access to his personal page
         * and he is redirected to the login page
         */
        public function logged_only(){
            if(!$this->session->read('auth')){
                //Send a confirmation message
                $this->session->setFlash('danger', $this->options['restrict_msg']);

                //Redirect the user to the login page
                App::redirect('login.php');
            }
        }



        /**
         * Retrieve current user
         */
        public function user(){
            if(!$this->session->read('auth')){
                return false;
            }
            return $this->session->read('auth');
        }



        /**
         * Connect the current user
         * Write the current user in the session variable 'auth'
         * @param  $user
         */
        public function connect($user){
            $this->session->write('auth', $user);
        }



        /**
         * Automatically reconnect the user if the login cookie exists
         * @param  $db
         */
        public function connectFromCookie($db){
            //Verify the presence of the remember cookie that memorizes the connection
            if(isset($_COOKIE['remember']) && !$this->user()){
                $remember_token = $_COOKIE['remember'];

                //Retrieve the id of the user
                $parts = explode('==', $remember_token);
                $user_id = $parts[0];

                //Retrieve the user corresponding to the id of the user
                $user = $db->query('SELECT * FROM users WHERE id = ?', [$user_id]) ->fetch();

                //If the query returns a result, the user is automatically logged in
                if($user){
                    $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'phone');
                    if($expected == $remember_token){
                        $this->connect($user);
                        setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                    }
                    else{
                        setcookie('remember', null, -1);
                    }
                }
                else{
                    setcookie('remember', null, -1);
                }
            }
        }



        /**
         * Allows user to log into account
         * @param  $db  Connecting to the database
         * @param  $username   Username sent from form
         * @param  $password   Password sent from form
         * @param  bool $remember   The 'remember me' button is checked or not
         * @return array
         */
        public function login($db, $username, $password, $remember = false){
            //Save the user according to the email or pseudo entered in the form
            //and that the user has a validated account
            $user = $db->query('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL', ['username' => $username])->fetch();

            //If the password entered by the user matches the password entered in the database
            //on connecte l'utilisateur et redirect the user to his personal page
            if(password_verify($password, $user->password)){
                $this->connect($user);

                //If the user checks the checkbox remember
                if($remember){
                    $this->remember($db, $user->id);
                }
                return $user;
            }
            else{
                return false;
            }
        }



        /**
         * If the user checks the checkbox remember
         * Create a cookie 'remember'
         * @param  $db       Connecting to the database
         * @param  $user_id  the User ID
         */
        public function remember($db, $user_id){
            $remember_token = Str:: random(255);
            $db->query('UPDATE users SET remember_token = ? WHERE id = ?', [$remember_token, $user_id]);

            //The result of the request is saved in a relatively complex cookie
            setcookie('remember', $user_id . '==' . $remember_token . sha1($user_id . 'phone'), time() + 60 * 60 * 24 * 7);

        }



        /**
         * disconnect the user
         */
        public function logout(){
            //Deleting the remember cookie that memorizes the connection
            setcookie('remember', null, -1);

            //Delete the authentication session
            $this->session->delete('auth');
        }



        /**
         * make a request to generate a new password
         * @param $db    Connecting to the database
         * @param $email User's email
         */
        public function resetPassword($db, $email){
            //Save the user according to the email or pseudo entered in the form
            //and that the user has a validated account
            $user = $db->query('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL', [$email])->fetch();

            //If the user forgets his password, we generate a new password token
            //Send an email confirmation and display a confirmation message
            if($user){
                $reset_token = Str::random(60);
                $db->query('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?', [$reset_token, $user->id]);
                mail($_POST['email'], "Réinitialisation de votre mot de passe", "Afin de réinitialiser votre mot de passe merci de cliquer sur ce lien\n\nhttp://localhost:8888/php/POO/member_area/reset.php?id={$user->id}&token=$reset_token");
                return $user;
            }
            else{
                return false;
            }
        }
    }

?>
