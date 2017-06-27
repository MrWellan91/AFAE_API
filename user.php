<?php
require_once("config.php");
require_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
        if (isGetSet("user")) {
            $user = User::loadUserWithId(test_input($_GET['user']));
            if (is_a($user, "User")) {
                echo test_input(json_encode($user));
            } else {
                echo '{"Code" : "' . $CODE['CODE_2']['Code'] . '", "Message" : "' . $CODE['CODE_2']['Message'] . '"}';
            }
        }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isGetSet("action") && $_GET['action'] == "update") {
        if (isPostSet('iduser')) {
            $user = User::loadUserWithId(test_input($_POST['iduser']));
            if (is_a($user, "User")) {

                if (isPostSet('name')) {
                    $user->setName(test_input($_POST['name']));
                }
                if (isPostSet('firstname')) {
                    $user->setFName(test_input($_POST['firstname']));
                }
                if (isPostSet('codepostal')) {
                    $user->setCodePostal(test_input($_POST['codepostal']));
                }
                if (isPostSet('email')) {
                    $user->setEmail(test_input($_POST['email']));
                }

                if (isPostSet('city')) {
                    $user->setCity(test_input($_POST['city']));
                }
                if (isPostSet('alloweddrop')) {
                    $baisse = test_input($_POST['alloweddrop']);
                    $baisse = $baisse == 'true' || $baisse == 1;
                    $user->setDrop($baisse);
                }
                if (isPostSet('address')) {
                    $user->setAddress(test_input($_POST['address']));
                }

                if (isPostSet('phone')) {
                    $user->setPhone(test_input($_POST['phone']));
                }

                if (isPostSet('rang')) {
                    $user->setRank(test_input($_POST['rang']));
                }

                if ($user->updateUser()) {
                    echo '{"Code" : "' . $CODE['CODE_0']['Code'] . '", "Message" : "' . $CODE['CODE_0']['Message'] . '"}';
                } else {
                    echo '{"Code" : "' . $CODE['CODE_4']['Code'] . '", "Message" : "' . $CODE['CODE_4']['Message'] . '"}';
                }
            } else {
                echo '{"Code" : "' . $CODE['CODE_2']['Code'] . '", "Message" : "' . $CODE['CODE_2']['Message'] . '"}';
            }
        } else {
            echo '{"Code" : "' . $CODE['CODE_1']['Code'] . '", "Message" : "' . $CODE['CODE_1']['Message'] . '"}';
        }
    } else if (isGetSet("action") && $_GET['action'] == "create") {
        //var_dump($_POST);
        if (!isPostSet('name') || !isPostSet('firstname')) {
            echo '{"Code" : "' . $CODE['CODE_3']['Code'] . '", "Message" : "' . $CODE['CODE_3']['Message'] . '"}';
        } else {
            $name = $fName = $cp = $email = $login = $mdp = $address = $ville = $telephone = $baisse = $rang = "";
            if (isPostSet('name')) {
                $name = test_input($_POST['name']);
            }
            if (isPostSet('firstname')) {
                $fName = test_input($_POST['firstname']);
            }
            if (isPostSet('codepostal')) {
                $cp = test_input($_POST['codepostal']);
            }
            if (isPostSet('email')) {
                $email = test_input($_POST['email']);
            }
            if (isPostSet('login')) {
                $login = test_input($_POST['login']);
            } else {
                $login = $name . $fName;
                $test = User::checkUserName($login);
                $cpt = $test;
                $login2 = "";
                while ($test != 0) {
                    $cpt++;
                    $login2 = $login . $cpt;
                    $test = User::checkUserName($login2);
                }
                if (!empty($login2))
                    $login .= $cpt;
            }

            if (isPostSet('password')) {
                $mdp = test_input($_POST['password']);
            } else {
                $mdp = $name . $fName;
            }

            if (isPostSet('city')) {
                $ville = test_input($_POST['city']);
            }
            if (isPostSet('alloweddrop')) {
                $baisse = test_input($_POST['alloweddrop']);
                $baisse = $baisse == 'true' || $baisse == 1;
            }
            if (isPostSet('address')) {
                $address = test_input($_POST['address']);
            }

            if (isPostSet('phone')) {
                $telephone = test_input($_POST['phone']);
            }

            if (isPostSet('rank')) {
                $rang = test_input($_POST['rank']);
            } else {
                $rang = 1;
            }

            $user = User::createUser($name, $fName, $address, $cp, $ville, $telephone, $baisse, $rang, $email);
            if ($user->insertIntoDb($login, $mdp)){
                $user = User::loadFromBd($login, $mdp);
                $id = 0;
                if(is_a($user, "User")){
                    $id = $user->id();
                }
                echo '{"Code" : "' . $CODE['CODE_0']['Code'] . '", "Message" : "' . $CODE['CODE_0']['Message'] . '", "iduser" : "'.$id.'"}';
            }
            else
                echo '{"Code" : "' . $CODE['CODE_5']['Code'] . '", "Message" : "' . $CODE['CODE_5']['Message'] . '"}';
        }
    } else if (isGetSet("action") && $_GET['action'] == "updatepwd") {
        if (isPostSet('iduser')) {
            $user = User::loadUserWithId(test_input($_POST['iduser']));
            if (is_a($user, "User")) {
                if (isPostSet('password')) {
                    $user->setPassword(test_input($_POST['password']));
                    if ($user->updatePassword()) {
                        echo '{"Code" : "' . $CODE['CODE_0']['Code'] . '", "Message" : "' . $CODE['CODE_0']['Message'] . '"}';
                    } else {
                        echo '{"Code" : "' . $CODE['CODE_4']['Code'] . '", "Message" : "' . $CODE['CODE_4']['Message'] . '"}';
                    }
                }else {
                    echo '{"Code" : "' . $CODE['CODE_6']['Code'] . '", "Message" : "' . $CODE['CODE_6']['Message'] . '"}';
                }

            } else {
                echo '{"Code" : "' . $CODE['CODE_2']['Code'] . '", "Message" : "' . $CODE['CODE_2']['Message'] . '"}';
            }
        } else {
            echo '{"Code" : "' . $CODE['CODE_1']['Code'] . '", "Message" : "' . $CODE['CODE_1']['Message'] . '"}';
        }
    }
}