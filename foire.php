<?php
require_once("config.php");
require_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isGetSet("foire")) {
        $foire = Foire::loadFromDb(test_input($_GET['foire']));
        if (is_a($foire, "Foire")) {
            echo test_input(json_encode($foire));
        } else {
            echo '{"Code" : "' . $CODE['CODE_10']['Code'] . '", "Message" : "' . $CODE['CODE_10']['Message'] . '"}';
        }
    }
}