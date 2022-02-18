<?php

//composer
include_once '/app/lib/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    include '/app/lib/' . str_replace('\\', '/', $class_name) . '.php';
});



?>