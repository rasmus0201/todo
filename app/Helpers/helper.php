<?php

//Adds in "Laravel dd"-function - for debugging
if (!function_exists('dd')) {
    function dd()
    {
        foreach (func_get_args() as $arr) {
            dump($arr);
        }
        die;
    }
}
