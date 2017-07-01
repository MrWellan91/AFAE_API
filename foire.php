<?php
require_once("config.php");
require_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isGetSet("foire") && isGetSet("user")) {
        $foire = Foire::loadFromDb(test_input($_GET['foire']));
        $user = User::loadUserWithId(test_input($_GET['user']));

        if (!is_a($foire, "Foire")) {
            echo '{"Code" : "' . $CODE['CODE_10']['Code'] . '", "Message" : "' . $CODE['CODE_10']['Message'] . '"}';

        } else if (!is_a($user, "User")) {
            echo '{"Code" : "' . $CODE['CODE_2']['Code'] . '", "Message" : "' . $CODE['CODE_2']['Message'] . '"}';
        } else {
            $objMan = new ObjectManager();
            $result = $objMan->loadObjectsFromUserFoire($user, $foire->idFoire());
            switch ($result) {
                case 0: {
                    echo test_input(json($objMan));
                }
                    break;
                case 1:
                    echo "{}";
                    break;
                case 2:
                    echo '{"Code" : "' . $CODE['CODE_19']['Code'] . '", "Message" : "' . $CODE['CODE_19']['Message'] . '"}';
                    break;
            }
        }

    } else if (isGetSet("foire") && isGetSet("objets")) {
        $foire = Foire::loadFromDb(test_input($_GET['foire']));

        if (!is_a($foire, "Foire")) {
            echo '{"Code" : "' . $CODE['CODE_10']['Code'] . '", "Message" : "' . $CODE['CODE_10']['Message'] . '"}';

        } else {
            $objMan = new ObjectManager();
            $result = $objMan->loadObjectsFromFoire($foire->idFoire());
            switch ($result) {
                case 0: {
                    echo test_input(json($objMan));
                }
                    break;
                case 1:
                    echo "{}";
                    break;
                case 2:
                    echo '{"Code" : "' . $CODE['CODE_19']['Code'] . '", "Message" : "' . $CODE['CODE_19']['Message'] . '"}';
                    break;
            }
        }
    } else if (isGetSet("foire" && isGetSet("participant"))) {

    } else if (isGetSet("foire")) {
        $foire = Foire::loadFromDb(test_input($_GET['foire']));
        if (is_a($foire, "Foire")) {
            echo test_input(json($foire));
        } else {
            echo '{"Code" : "' . $CODE['CODE_10']['Code'] . '", "Message" : "' . $CODE['CODE_10']['Message'] . '"}';
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isGetSet("action") && $_GET['action'] == "insert") {
        $nomFoire = $idUser = $idAssoc = $prixbaisse = $retenue = $maxObj = $maxObjAssoc = $dateDebutSaisie = $dateFinSaisie = $dateDebutFoire = $dateFinFoire = "";
        $missingFields = array();
        $badDates = array();

        if (isPostSet("name")) {
            $nomFoire = test_input($_POST['name']);
        } else {
            array_push($missingFields, "name");
        }
        if (isPostSet("iduser")) {
            $idUser = test_input($_POST['iduser']);
            $user = User::loadUserWithId($idUser);
            if (!is_a($user, "User")) {
                echo '{"Code" : "' . $CODE['CODE_2']['Code'] . '", "Message" : "' . $CODE['CODE_2']['Message'] . '"}';
                exit(1);
            }
        } else {
            array_push($missingFields, "iduser");
        }
        if (isPostSet("idassociation")) {
            $idAssoc = test_input($_POST['idassociation']);
            $assoc = Association::loadFromDb($idAssoc);
            if (!is_a($assoc, "Association")) {
                echo '{"Code" : "' . $CODE['CODE_21']['Code'] . '", "Message" : "' . $CODE['CODE_21']['Message'] . '"}';
                exit(1);
            }
        } else {
            array_push($missingFields, "idassociation");
        }
        if (isPostSet("baisse")) {
            $prixbaisse = test_input($_POST['baisse']);
        } else {
            array_push($missingFields, "baisse");
        }
        if (isPostSet("retenue")) {
            $retenue = test_input($_POST['retenue']);
        } else {
            array_push($missingFields, "retenue");
        }
        if (isPostSet("maxobj")) {
            $maxObj = test_input($_POST['maxobj']);
        } else {
            array_push($missingFields, "maxobj");
        }
        if (isPostSet("maxobjassoc")) {
            $maxObjAssoc = test_input($_POST['maxobjassoc']);
        } else {
            array_push($missingFields, "maxobjassoc");
        }
        if (isPostSet("datedebutsaisie")) {
            $dateDebutSaisie = test_input($_POST['dateDebutSaisie']);
            $dateDebutSaisie = testDate($dateDebutSaisie);
            if ($dateDebutSaisie === 1) {
                array_push($badDates, "datedebutsaisie");
            }
        } else {
            array_push($missingFields, "datedebutsaisie");
        }
        if (isPostSet("datefinsaisie")) {
            $dateFinSaisie = test_input($_POST['datefinsaisie']);
            $dateFinSaisie = testDate($dateFinSaisie);
            if ($dateFinSaisie === 1) {
                array_push($badDates, "datefinsaisie");
            }
        } else {
            array_push($missingFields, "datefinsaisie");
        }
        if (isPostSet("datedebutfoire")) {
            $dateDebutFoire = test_input($_POST['datedebutfoire']);
            $dateDebutFoire = testDate($dateDebutFoire);
            if ($dateDebutFoire === 1) {
                array_push($badDates, "datedebutfoire");
            }
        } else {
            array_push($missingFields, "datedebutfoire");
        }
        if (isPostSet("datefinfoire")) {
            $dateFinFoire = test_input($_POST['datefinfoire']);
            $dateFinFoire = testDate($dateFinFoire);
            if ($dateFinFoire === 1) {
                array_push($badDates, "datefinfoire");
            }
        } else {
            array_push($missingFields, "datefinfoire");
        }

        if (count($missingFields) == 1) {
            echo '{"Code" : "' . $CODE['CODE_16']['Code'] . '", "Message" : "' . $CODE['CODE_16']['Message'] . '", "champs" : ["' . $missingFields[0] . '"]}';
        } elseif (count($missingFields) > 1) {
            $string = "";
            foreach ($missingFields as $field) {
                $string .= '"' . $field . '", ';
            }

            $nb = strlen($string);
            $string[$nb - 2] = "\0";

            echo '{"Code" : "' . $CODE['CODE_16']['Code'] . '", "Message" : "' . $CODE['CODE_16']['Message'] . '", "champs" : [' . $string . ']}';

        } else {
            if (count($badDates) == 1) {
                echo '{"Code" : "' . $CODE['CODE_22']['Code'] . '", "Message" : "' . $CODE['CODE_22']['Message'] . '", "champs" : ["' . $badDates[0] . '"]}';
            } elseif (count($badDates) > 1) {
                $string = "";
                foreach ($badDates as $field) {
                    $string .= '"' . $field . '", ';
                }

                $nb = strlen($string);
                $string[$nb - 2] = "\0";

                echo '{"Code" : "' . $CODE['CODE_22']['Code'] . '", "Message" : "' . $CODE['CODE_22']['Message'] . '", "champs" : [' . $string . ']}';

            } else {
                $foire = Foire::createFoire($nomFoire, $idAssoc, $idUser, $dateDebutFoire, $dateFinFoire, $dateDebutSaisie, $dateFinSaisie, $prixbaisse, $maxObj, $maxObjAssoc, $retenue);
                $insert = $foire->insertIntoDb();
                if ($insert['ErrorCode'] > 0) {
                    echo '{"Code" : "' . $CODE['CODE_20']['Code'] . '", "Message" : "' . $CODE['CODE_20']['Message'] . '", "SQLMESSAGE" : [' . $insert["Message"] . ']}';
                } else {
                    echo '{"Code" : "' . $CODE['CODE_0']['Code'] . '", "Message" : "' . $CODE['CODE_0']['Message'] . '"}';
                }
            }

        }

    } elseif (isGetSet("action") && $_GET['action'] == "update") {

    } elseif (isGetSet("action") && $_GET['action'] == "participant") {
        if (isPostSet("foire") && isPostSet("user")) {
            $foire = Foire::loadFromDb(test_input($_POST['foire']));
            $user = User::loadUserWithId(test_input($_POST['user']));

            if (!is_a($foire, "Foire")) {
                echo '{"Code" : "' . $CODE['CODE_10']['Code'] . '", "Message" : "' . $CODE['CODE_10']['Message'] . '"}';

            } else if (!is_a($user, "User")) {
                echo '{"Code" : "' . $CODE['CODE_2']['Code'] . '", "Message" : "' . $CODE['CODE_2']['Message'] . '"}';
            } else {
                $part = Participant::loadFromDb($foire->idFoire(), $user->id());
                if (!is_a($part, "Participant")) {
                    $part = new Participant();
                    $part->setIdUser($user->id());
                    $part->setIdFoire($foire->idFoire());
                    $part->setValide(true);
                    $insert = $part->insertIntoDb();
                    if ($insert['ErrorCode'] > 0) {
                        echo '{"Code" : "' . $CODE['CODE_23']['Code'] . '", "Message" : "' . $CODE['CODE_20']['Message'] . '", "SQLMESSAGE" : [' . $insert["Message"] . ']}';
                    } else {
                        echo '{"Code" : "' . $CODE['CODE_0']['Code'] . '", "Message" : "' . $CODE['CODE_0']['Message'] . '"}';
                    }
                } else {
                    $result = $part->valider();
                    if ($result['ErrorCode'] > 0) {
                        echo '{"Code" : "' . $CODE['CODE_23']['Code'] . '", "Message" : "' . $CODE['CODE_20']['Message'] . '", "SQLMESSAGE" : [' . $result["Message"] . ']}';
                    } else {
                        echo '{"Code" : "' . $CODE['CODE_0']['Code'] . '", "Message" : "' . $CODE['CODE_0']['Message'] . '"}';
                    }
                }
            }
        }
    }
} else {
    echo '{"Code" : "' . $CODE['CODE_501']['Code'] . '", "Message" : "' . $CODE['CODE_501']['Message'] . '"}';
}