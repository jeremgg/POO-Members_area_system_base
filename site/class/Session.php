<?php

    class Session{

        //define the property that save the session instance
        static $instance;



        /**
         * Set the session instance once with a singleton
         * Check that the session instance does not exist and create it
         * @return $instance
         */
        static function getInstance(){
            if(!self::$instance){
                self::$instance = new Session();
            }
            return self::$instance;
        }



        /**
         * Start the session
         */
        public function __construct(){
            session_start();
        }



        /**
         * Define flash messages
         */
        public function setFlash($key, $message){
             $_SESSION['flash'][$key] = $message;
        }



        /**
         * Check that the session to flash messages in memory
         * @return bool
         */
        public function hasFlash(){
            return isset($_SESSION['flash']);
        }



        /**
         * Returns the session's flash messages
         * @return string
         */
        public function getFlash(){
            $flash =  $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }



        /**
         * Write in the session
         * @param  $key    Session Key
         * @param  $value  The value to be written
         */
        public function write($key, $value){
            $_SESSION[$key] = $value;
        }



        /**
         * Check if the session variable is set, and read it
         * @param  $key    Session Key
         * @return array   Value of session key
         */
        public function read($key){
            return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        }
    }


?>
