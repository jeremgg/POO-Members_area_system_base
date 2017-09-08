<?php

    /**
     * Debug the code and display the value of a variable
     * @param  string  $variable  The variable to be debugged
     * @return string
     */
    function debug($variable){
        echo '<pre>' . print_r($variable, true) . '</pre>';
    }



?>
