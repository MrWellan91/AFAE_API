<?php
$file = realpath(dirname(__FILE__) . "/credentials.json");
$data = array();
if (file_exists($file)) {
    $credentials = file_get_contents($file, FILE_USE_INCLUDE_PATH);
    $data = json_decode($credentials, true);
} else {
    echo '{"error" : "Parametres de connexion a la base de donnee inexistants."}';
    exit(1);
}

global $config;
$config = array(
    "db" => array(
        "dbname" => $data['dbname'],
        "username" => $data['username'],
        "password" => $data['password'],
        "host" => $data['dbhost']
    ),
    "default_rank" => "1",
    "max_object_user" => 25,
    "max_object_assoc" => 200
);

define("CLASS_PATH", realpath(dirname(__FILE__) . '/classes'));
define("ERROR_PATH", realpath(dirname(__FILE__) . '/errors'));

//Autoloader si on instancie une classe non déclarée
function autoloader($class)
{
    include CLASS_PATH . '/' . $class . '.php';
}

spl_autoload_register('autoloader');

$CODE = array(
    "CODE_0" => array("Code" => 0,
        "Message" => "Requête effectuée. Pas d'erreur rencontrée côté serveur."),
    "CODE_1" => array("Code" => 1,
        "Message" => "Champs iduser vide"),
    "CODE_2" => array("Code" => 2,
        "Message" => "Cet id ne correspond à aucun utilisateur"),
    "CODE_3" => array("Code" => 3,
        "Message" => "Champs name ou firstname vide"),
    "CODE_4" => array("Code" => 4,
        "Message" => "Erreur inconnue pendant la mise à jour de cet utilisateur"),
    "CODE_5" => array("Code" => 5,
        "Message" => "Erreur inconnue pendant la création de cet utilisateur"),
    "CODE_6" => array("Code" => 6,
        "Message" => "Champs password vide"),
    "CODE_7" => array("Code" => 7,
        "Message" => "Champs login/password vide(s)"),
    "CODE_8" => array("Code" => 8,
        "Message" => "Login/Mot de passe erroné(s)"),
    "CODE_9" => array("Code" => 9,
        "Message" => "Cet id ne correspond à aucun objet"),
    "CODE_10" => array("Code" => 10,
        "Message" => "Cet id ne correspond à aucune foire"),
    "CODE_11" => array("Code" => 11,
        "Message" => "Cet objet n'a pas pu être supprimé (verrou ou raison inconnue)"),
    "CODE_12" => array("Code" => 12,
        "Message" => "Champs idobjet vide"),
    "CODE_13" => array("Code" => 13,
        "Message" => "Erreur inconnue pendant l'insertion de l'objet dans la base de donnée"),
    "CODE_14" => array("Code" => 14,
        "Message" => "Cet utilisateur a dépassé le nombre d'objets max"),
    "CODE_15" => array("Code" => 15,
        "Message" => "Erreur pendant la création de l'objet"),
    "CODE_16" => array("Code" => 16,
        "Message" => "Il manque 1 ou plusieurs champs nécessaires à la création de votre objet/foire"),
    "CODE_17" => array("Code" => 17,
        "Message" => "Aucun champs mis à jour, vérifiez que vos champs comportent les bons noms"),
    "CODE_18" => array("Code" => 18,
        "Message" => "Erreur pendant la mise à jour de cet objet"),
    "CODE_19" => array("Code" => 19,
        "Message" => "Erreur lors de la requête SQL"),
    "CODE_20" => array("Code" => 20,
        "Message" => "Erreur pendant l'insertion dans la table foire"),
    "CODE_21" => array("Code" => 21,
        "Message" => "Cet idassociation n'existe pas"),
    "CODE_22" => array("Code" => 22,
        "Message" => "Champs dates au mauvais format, bon format : (jj-mm-AAAA)"),
    "CODE_23" => array("Code" => 23,
        "Message" => "Erreur lors de l'insertion dans la table participant"),
    "Code_501" => array("Code" => 501,
        "Message" => "Not implemented")

);