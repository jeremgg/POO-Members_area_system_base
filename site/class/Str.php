<?php

    class Str{

        /**
         * Generate a random string with letters and numbers
         * Which are mixed and can be used several times
         * @param int  The number of characters
         * @return string
         */
        static function random($length){
            $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
            return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
        }
    }
?>
