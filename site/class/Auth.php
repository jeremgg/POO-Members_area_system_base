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
    }

?>
