<?php

//Adds in "Laravel dd"-function - for debugging
//dump n' die
if (!function_exists('dd')) {
    function dd()
    {
        //Get each function arg dynamically
        //And call the dump method
        foreach (func_get_args() as $arr) {
            dump($arr);
        }

        //die
        die;
    }
}
