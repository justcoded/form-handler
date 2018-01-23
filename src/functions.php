<?php

if (!function_exists('template')) {
    function template ($templateName, $data) {
        $wholePath = __DIR__ . '/../examples/' . $templateName;
        ob_start();
        require $wholePath;
        $templateString = ob_get_clean();

//        return preg_replace("|{(\w*)}|e", '$data["$1"]', $templateString);
        foreach ($data as $key => $field) {
            $templateString = str_replace('{' . $key . '}', $field,$templateString);
        }

        return $templateString;
    }
}