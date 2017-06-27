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

define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));
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
        "Message" => "Cet id ne correspond à aucune foire")
);