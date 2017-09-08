<?php

    class App{

        //initialize the connection property to the database
        static $db = null;



        /**
         * initialize the connection configuration to the database only once
         * @return obj
         */
        static function getDatabase(){
            if(!self::$db){
                self::$db = new Database('members', 'localhost', 'root', 'root');
            }
            return self::$db;
        }



         /**
          * Create an instance of the auth class by initializing its properties
          * @return obj
          */
         static function getAuth(){
             return new Auth(Session::getInstance(), ["restrict_msg" => "Vous n'avez pas le droit d'accéder à cette page"]);
         }



         /**
          * Redirect the user to another page
          * @param  $page Redirect page
          */
         static function redirect($page){
             header("Location: $page");
             exit();
         }
    }

?>
