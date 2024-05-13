<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class UserManager extends Manager
{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\User";
    protected $tableName = "user";

    public function __construct(){
        parent::connect();
    }

    public function findOneByEmail($email)
    {
        $sql = "SELECT * 
        FROM $this->tableName
        WHERE user.email = :email";
        // la requête renvoie un ou enregistrements --> getOneOrNullResult

        return $this->getOneOrNullResult(
            DAO::select($sql, ['email' => $email], false),
            $this->className
        );

    }

    public function findOneByUsername($username)
    {
        $sql = "SELECT *
                FROM $this->tableName
                WHERE user.username = :username";

        // la requête renvoie un ou enregistrements --> getOneOrNullResult

        return $this->getOneOrNullResult(
            DAO::select($sql, ['username' => $username], false),
            $this->className
        );
    }

    public function findHashByUsername($username)
    {
        $sql = "SELECT password
                FROM $this->tableName
                WHERE user.username = :username";
        // la requête renvoie un ou enregistrements --> getOneOrNullResult

        return $this->getOneOrNullResult(
            DAO::select($sql, ['username' => $username], false),
            $this->className
        );
    }

    public function banUser($data, $id)
    {
        $sql = "UPDATE user
                SET status = '" . $data . "'
                WHERE user.id_user = :id";
        return DAO::update($sql, ['id' => $id]);
    }

    public function updateUser($data, $id)
    {
        $sql = "UPDATE user
                SET " . $data . "
                WHERE user.id_user = :id";

        return DAO::update($sql, ["id" => $id]);

    }

    public function findAllByUsername($username, $order = null)
    {
        $orderQuery = ($order) ?
            "ORDER BY " . $order[0] . " " . $order[1] :
            "";

        $sql = "SELECT *
            FROM $this->tableName
            WHERE username LIKE '%" . $username . "%'
            " . $orderQuery;

        return $this->getMultipleResults(
            DAO::select($sql),
            $this->className
        );

    }

}



