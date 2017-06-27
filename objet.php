<?php
require_once("config.php");
require_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isGetSet("objet") && isGetSet("action") && $_GET['action'] == "info") {
        $obj = Object::loadObjectFromId(test_input($_GET['objet']));
        if (is_a($obj, "Object")) {
            echo test_input(json($obj));
        } else {
            echo '{"Code" : "' . $CODE['CODE_9']['Code'] . '", "Message" : "' . $CODE['CODE_9']['Message'] . '"}';
        }
    } else if (isGetSet("objet") && isGetSet("action") && $_GET['action'] == "delete") {
        $obj = Object::loadObjectFromId(test_input($_GET['objet']));
        if (is_a($obj, "Object")) {
            if (ObjectManager::deleteObject($obj->idObjet())) {
                echo '{"Code" : "' . $CODE['CODE_0']['Code'] . '", "Message" : "' . $CODE['CODE_0']['Message'] . '"}';
            } else {
                echo '{"Code" : "' . $CODE['CODE_11']['Code'] . '", "Message" : "' . $CODE['CODE_11']['Message'] . '"}';

            }
        } else {
            echo '{"Code" : "' . $CODE['CODE_9']['Code'] . '", "Message" : "' . $CODE['CODE_9']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : "' . $CODE['CODE_12']['Code'] . '", "Message" : "' . $CODE['CODE_12']['Message'] . '"}';
    }
} else if ($_SERVER['REQUEST_METHOD']) {
    if (isGetSet("action") && $_GET['action'] == "insert") {
        $idFoire = $idUser = $desc = $baisse = $prix = $vendu = $taille = $nbItem = $verrou = "";
        $missingFields = array();

        if (isPostSet("idfoire")) {
            $idFoire = test_input($_POST['idfoire']);
        } else {
            array_push($missingFields, "idfoire");
        }
        if (isPostSet("iduser")) {
            $idUser = test_input($_POST['iduser']);
        } else {
            array_push($missingFields, "iduser");
        }
        if (isPostSet("description")) {
            $desc = test_input($_POST['description']);
        } else {
            array_push($missingFields, "description");
        }
        if (isPostSet("alloweddrop")) {
            $baisse = test_input($_POST['alloweddrop']);
            $baisse = ($baisse == 'true' || $baisse == 1);
        } else {
            $baisse = false;
        }
        if (isPostSet("price")) {
            $price = test_input($_POST['price']);
        } else {
            array_push($missingFields, "price");
        }
        if (isPostSet("sold")) {
            $vendu = test_input($_POST['sold']);
            $vendu = ($vendu == 'true' || $vendu == 1);
        } else {
            $vendu = false;
        }
        if (isPostSet("size")) {
            $taille = test_input($_POST['size']);
        } else {
            array_push($missingFields, "size");
        }
        if (isPostSet("nbitems")) {
            $nbItem = test_input($_POST['nbitems']);
        } else {
            array_push($missingFields, "nbitems");
        }
        if (isPostSet("lock")) {
            $verrou = test_input($_POST['lock']);
            $verrou = ($verrou == 'true' || $verrou == 1);
        } else {
            $verrou = false;
        }

        if (count($missingFields) == 0) {
            $objet = Object::createObject($idUser, $idFoire, $desc, $baisse, $prix, $vendu, $taille, $nbItem, $verrou);
            if (is_a($objet, "Object")) {
                $result = $objet->insertObjectIntoDb();
                switch ($result) {
                    case 0:
                        echo '{"Code" : "' . $CODE['CODE_0']['Code'] . '", "Message" : "' . $CODE['CODE_0']['Message'] . '"}';
                        break;
                    case 1:
                        echo '{"Code" : "' . $CODE['CODE_13']['Code'] . '", "Message" : "' . $CODE['CODE_13']['Message'] . '"}';
                        break;
                    case 2:
                        echo '{"Code" : "' . $CODE['CODE_14']['Code'] . '", "Message" : "' . $CODE['CODE_14']['Message'] . '"}';
                        break;
                }
            } else {
                echo '{"Code" : "' . $CODE['CODE_15']['Code'] . '", "Message" : "' . $CODE['CODE_15']['Message'] . '"}';
            }
        } else if (count($missingFields) == 1) {
            echo '{"Code" : "' . $CODE['CODE_16']['Code'] . '", "Message" : "' . $CODE['CODE_16']['Message'] . '", "champs" : ["' . $missingFields[0] . '"]}';
        } else {
            $string = "";
            foreach ($missingFields as $field) {
                $string .= '"' . $field . '", ';
            }

            $nb = strlen($string);
            $string[$nb - 2] = "\0";

            echo '{"Code" : "' . $CODE['CODE_16']['Code'] . '", "Message" : "' . $CODE['CODE_16']['Message'] . '", "champs" : [' . $string . ']}';

        }

    } else if (isGetSet("action") && $_GET['action'] == "update") {
        if (isPostSet("idobjet")) {
            $objet = Object::loadObjectFromId(test_input($_POST['idobjet']));
            if (is_a($objet, "Object")) {
                $nbModif = 0;
                if (isPostSet("description")) {
                    $objet->setDesc(test_input($_POST['description']));
                    $nbModif++;
                }

                if (isPostSet("alloweddrop")) {
                    $baisse = test_input($_POST['alloweddrop']);
                    $baisse = ($baisse == 'true' || $baisse == 1);
                    $objet->setBaisse($baisse);
                    $nbModif++;
                }

                if (isPostSet("price")) {
                    $objet->setPrix(test_input($_POST['price']));
                    $nbModif++;
                }

                if (isPostSet("size")) {
                    $objet->setTaille(test_input($_POST['size']));
                    $nbModif++;
                }

                if (isPostSet("nbitems")) {
                    $objet->setNbItems(test_input($_POST['nbitems']));
                    $nbModif++;
                }

                if ($nbModif > 0) {
                    if ($objet->updateObject()) {
                        echo '{"Code" : "' . $CODE['CODE_0']['Code'] . '", "Message" : "' . $CODE['CODE_0']['Message'] . '"}';
                    } else {
                        echo '{"Code" : "' . $CODE['CODE_18']['Code'] . '", "Message" : "' . $CODE['CODE_17']['Message'] . '"}';
                    }
                } else {
                    echo '{"Code" : "' . $CODE['CODE_17']['Code'] . '", "Message" : "' . $CODE['CODE_17']['Message'] . '"}';
                }
            } else {
                echo '{"Code" : "' . $CODE['CODE_9']['Code'] . '", "Message" : "' . $CODE['CODE_9']['Message'] . '"}';
            }
        } else {
            echo '{"Code" : "' . $CODE['CODE_12']['Code'] . '", "Message" : "' . $CODE['CODE_12']['Message'] . '"}';
        }
    }
}