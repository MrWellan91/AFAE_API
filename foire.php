<?php
require_once("config.php");
require_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isGetSet("foire") && isGetSet("user")) {
        $foire = Foire::loadFromDb(test_input($_GET['foire']));
        $user = User::loadUserWithId(test_input($_GET['user']));

        if(!is_a($foire, "Foire")){
            echo '{"Code" : "' . $CODE['CODE_10']['Code'] . '", "Message" : "' . $CODE['CODE_10']['Message'] . '"}';

        } else if(!is_a($user, "User")){
            echo '{"Code" : "' . $CODE['CODE_2']['Code'] . '", "Message" : "' . $CODE['CODE_2']['Message'] . '"}';
        } else {
            $objMan = new ObjectManager();
            $result = $objMan->loadObjectsFromUserFoire($user, $foire->idFoire());
            switch($result){
                case 0:{
                    echo test_input(json($objMan));
                } break;
                case 1:
                    echo "{}";
                    break;
                case 2:
                    echo '{"Code" : "' . $CODE['CODE_19']['Code'] . '", "Message" : "' . $CODE['CODE_19']['Message'] . '"}';
                    break;
            }
        }

    } else if(isGetSet("foire") && isGetSet("objets")){
        $foire = Foire::loadFromDb(test_input($_GET['foire']));

        if(!is_a($foire, "Foire")){
            echo '{"Code" : "' . $CODE['CODE_10']['Code'] . '", "Message" : "' . $CODE['CODE_10']['Message'] . '"}';

        }  else {
            $objMan = new ObjectManager();
            $result = $objMan->loadObjectsFromFoire($foire->idFoire());
            switch($result){
                case 0:{
                    echo test_input(json($objMan));
                } break;
                case 1:
                    echo "{}";
                    break;
                case 2:
                    echo '{"Code" : "' . $CODE['CODE_19']['Code'] . '", "Message" : "' . $CODE['CODE_19']['Message'] . '"}';
                    break;
            }
        }
    } else if(isGetSet("foire")){
        $foire = Foire::loadFromDb(test_input($_GET['foire']));
        if (is_a($foire, "Foire")) {
            echo test_input(json($foire));
        } else {
            echo '{"Code" : "' . $CODE['CODE_10']['Code'] . '", "Message" : "' . $CODE['CODE_10']['Message'] . '"}';
        }
    }
}