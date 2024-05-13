<?php
namespace App;

use Model\Entities\Post;

abstract class Entity
{

    protected function hydrate($data)
    {

        foreach($data as $field => $value){
            // field = topic_id
            // fieldarray = ['topic','id']
            $fieldArray = explode("_", $field);

            if(isset($fieldArray[1]) && $fieldArray[1] == "id"){
                // $className = "Model\Entities\\" . ucfirst($fieldArray[0]);
                // manName = TopicManager 
                $manName = ucfirst($fieldArray[0])."Manager";
                // FQCName = Model\Managers\TopicManager;
                $FQCName = "Model\Managers\\".$manName;
                
                // man = new Model\Managers\TopicManager
                $man = new $FQCName();
                // value = Model\Managers\TopicManager->findOneById(1)
                $value = $man->findOneById($value);

                // if ($entity instanceof $className) {
                //     throw new \Exception($value);
                // } else {
                //     throw new \Exception(get_class($this));
                // }
                
            }

            // fabrication du nom du setter à appeler (ex: setName)
            $method = "set".ucfirst($fieldArray[0]);
            
            // si setName est une méthode qui existe dans l'entité (this)
            if(method_exists($this, $method)){
                // $this->setName("valeur")
                $this->$method($value);
            }
        }
    }

    public function getClass(){
        return get_class($this);
    }
}