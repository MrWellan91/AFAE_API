<?php
class Participant
{
    private $_idFoire;
    private $_idUser;
    private $_valide;

    public function idFoire()
    {
        return $this->_idFoire;
    }

    public function idUser()
    {
        return $this->_idUser;
    }

    public function valide()
    {
        return $this->_valide;
    }

    public function setIdFoire($id)
    {
        $this->_idFoire = (int)$id;
    }

    public function setIdUser($id)
    {
        $this->_idUser = (int)$id;
    }

    public function setValide($b)
    {
        $this->_valide = (bool)$b;
    }

    public static function loadFromDb($idfoire, $iduser)
    {

        $query = connectToDb()->prepare("SELECT * FROM participant WHERE idfoire=:idfoire AND idutilisateur=:iduser");
        $query->bindValue(':idfoire', $idfoire, PDO::PARAM_INT);
        $query->bindValue(':iduser', $iduser, PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return 1;
        }

        if (is_bool($data))
            return 2;

        $part = new self();
        $part->hydrate($data);

        return $part;
    }

    public function createParticipant($idfoire, $iduser)
    {
        $this->setIdFoire($idfoire);
        $this->setIdUser($iduser);
        $this->setValide(false);
    }

    public function insertIntoDb()
    {
        $query = connectToDb()->prepare("INSERT INTO participant(idfoire, idutilisateur, valide) VALUES(:idfoire, :idutilisateur, :valide);");
        $query->bindValue(':idfoire', $this->idFoire(), PDO::PARAM_INT);
        $query->bindValue(':idutilisateur', $this->idUser(), PDO::PARAM_INT);
        $query->bindValue(':valide', $this->valide(), PDO::PARAM_BOOL);
        try {
            $query->execute();
        } catch (PDOException $e) {
            $query->closeCursor();
            if ($e->getCode() == 23000)
                return 1;
        }
        $query->closeCursor();
        return 0;
    }

    public static function validerPart($idUser, $idFoire)
    {
        $query = connectToDb()->prepare("UPDATE participant SET valide=TRUE WHERE idutilisateur=:iduser AND idfoire=:idfoire");
        $query->bindValue(':iduser', $idUser, PDO::PARAM_INT);
        $query->bindValue(':idfoire', $idFoire, PDO::PARAM_INT);
        try {
            $query->execute();
        } catch (PDOException $e) {
            $query->closeCursor();
            return array("ErrorCode" => 1, "Message" => $e->getMessage());
        }
        $query->closeCursor();
        return array("ErrorCode" => 0, "Message" => "No errors");
    }

    public function valider()
    {
        $query = connectToDb()->prepare("UPDATE participant SET valide=TRUE WHERE idutilisateur=:iduser AND idfoire=:idfoire");
        $query->bindValue(':iduser', $this->idUser(), PDO::PARAM_INT);
        $query->bindValue(':idfoire', $this->idFoire(), PDO::PARAM_INT);
        try {
            $query->execute();
        } catch (PDOException $e) {
            $query->closeCursor();
            return array("ErrorCode" => 1, "Message" => $e->getMessage());
        }
        $query->closeCursor();
        return array("ErrorCode" => 0, "Message" => "No errors");
    }

    public function hydrate(array $data)
    {
        if (isset($data['idfoire']))
            $this->setIdFoire($data['idfoire']);
        if (isset($data['idutilisateur']))
            $this->setIdUser($data['idutilisateur']);
        if (isset($data['valide']))
            $this->setValide($data['valide']);
    }
}