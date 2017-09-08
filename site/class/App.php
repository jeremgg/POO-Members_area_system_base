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
          * Redirect the user to another page
          * @param  $page Redirect page
          */
         static function redirect($page){
             header("Location: $page");
             exit();
         }
    }

?>