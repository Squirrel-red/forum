<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class TopicManager extends Manager
{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Topic";
    protected $tableName = "topic";

    public function __construct()
    {
        parent::connect();
    }

    // récupérer tous les topics d'une catégorie spécifique (par son id)
    public function findTopicsByCategory($id) 
    {

        $sql = "SELECT * 
                FROM ".$this->tableName." t 
                WHERE t.category_id = :id";
       
        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return  $this->getMultipleResults(
            DAO::select($sql, ['id' => $id]), 
            $this->className
        );
    }

    public function findTopicsByUser($id)
    {

        $sql = "SELECT * 
                FROM " . $this->tableName . " u 
                WHERE u.user_id = :id";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getMultipleResults(
            DAO::select($sql, ['id' => $id]),
            $this->className
        );
    }

    public function findCategoryByTopic($id)
    {
        $sql = "SELECT *
                FROM category
                INNER JOIN topic ON category.id_category = topic.category_id
                WHERE id_topic = :id";

        // la requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getMultipleResults(
            DAO::select($sql, ['id' => $id]),
            $this->className
        );

    }

    public function updateTopic($data, $id)
    {
        $sql = "UPDATE topic
                SET " . $data . "
                WHERE topic.id_topic = :id";

        return DAO::update($sql, ["id" => $id]);

    }    
}