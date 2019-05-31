<?php

// Add "Laravel dd"-function - for debugging
if (!function_exists('dd')) {
    function dd() {
        // Get each function arg dynamically and call the dump method
        foreach (func_get_args() as $arr) {
            dump($arr);
        }

        die;
    }
}
