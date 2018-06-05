<?php

//Bootstrap file
//Loads necessary components
require '../bootstrap.php';

//Register Webroutes
require '../app/routes.php';

//Run Slim Application
$app->run();
