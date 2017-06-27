<?php
require_once("config.php");
require_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isGetSet("objet")) {
        $obj = Object::loadObjectFromId(test_input($_GET['objet']));
        if (is_a($obj, "Object")) {
            echo test_input(json_encode($obj));
        } else {
            echo '{"Code" : "' . $CODE['CODE_9']['Code'] . '", "Message" : "' . $CODE['CODE_9']['Message'] . '"}';
        }
    }
}