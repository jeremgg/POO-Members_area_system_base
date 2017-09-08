<?php

    class Auth{

        //initialize the connection property to the database
        private $db;



        /**
         * Define user authentication
         * @param $db The connection to the database
         */
        public function __construct($db){
            $this->db = $db;
        }



        /**
         * Register a new user
         * @param  $username
         * @param  $email
         * @param  $password
         */
        public function register($username, $email, $password){
            //Encrypt the user's password and execute the query
            $password = password_hash($password, PASSWORD_BCRYPT);

            //define a random number of 60 digits
            $token = Str::random(60);

            //Execute the query
            $this->db->query("INSERT INTO users SET username = ?, email = ?, password = ?, confirmation_token = ?", [
                $username,
                $email,
                $password,
                $token
            ]);

            //Retrieve the last generated id and send an email confirmation of the mail address
            $user_id = $this->db->lastInsertId();
            mail($email, "Confirmation de votre compte", "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost:8888/php/POO/member_area/confirm.php?id=$user_id&token=$token");
        }
    }

?>
