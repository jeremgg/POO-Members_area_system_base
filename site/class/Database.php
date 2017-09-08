<?php

    class Database{

        //initialize the connection property to the database
        private $pdo;



        /**
         * Define the connection to the database
         * Show sql error messages
         * And return the information as objects
         * @param string $db_login
         * @param string $db_password
         * @param string $db_name
         * @param string $db_host
         */
        public function __construct($db_name, $db_host = 'localhost', $db_login, $db_password){
            $this->pdo = new PDO("mysql:dbname=$db_name;host=$db_host", $db_login, $db_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        }



        /**
         * Define a query to the database
         * if we have parameters we make a request prepared otherwise we do a classical query
         * @param  string $query  Save the request
         * @param  bool|array $params The parameters of the query
         * @return PDOStatement  the result of the query
        */
        public function query($query, $params = false){
            if($params){
                $req = $this->pdo->prepare($query);
                $req->execute($params);
            }
            else{
                $req = $this->pdo->query($query);
            }
            return $req;
        }
    }

?>
