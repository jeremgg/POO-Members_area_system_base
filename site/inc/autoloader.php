<?php

    /**
     * Register our autoloader
     */
    spl_autoload_register('autoload');


    /**
     * Include the file corresponding to our class
     * @param string $class The name of the class to be loaded
     */
    function autoload($class){
        require "class/$class.php";
    }
?>
