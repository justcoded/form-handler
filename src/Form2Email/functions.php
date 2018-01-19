<?php

if (!function_exists('template')) {
    function template ($path, $data){
        extract($data);
        ob_start();
        require $path;

        return ob_get_clean();
    }
}