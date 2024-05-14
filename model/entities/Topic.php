<?php
namespace Model\Entities;

use App\Entity;

/*
    En programmation orientée objet, une classe finale (final class) est une classe que vous ne pouvez pas étendre, c'est-à-dire qu'aucune autre classe ne peut hériter de cette classe. En d'autres termes, une classe finale ne peut pas être utilisée comme classe parente.
*/

final class Topic extends Entity
{

    private  int $id;
    private string $title;
    private User $user;
    private Category $category;
    private \DateTime $creationDate;
    private int $closed;

    public function __construct($data)
    {         
        $this->hydrate($data);        
    }

    /**
     * Get the value of id
     */ 
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle(): string 
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the value of user
     */ 
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

        /**
     * Get the value of category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of dateCreation
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set the value of dateCreation
     *
     * @return  self
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = new \DateTime($dateCreation);

        return $this;
    }

    /**
     * Get the value of closed
     */
    public function getClosed(): int
    {
        return $this->closed;
    }

    /**
     * Set the value of closed
     *
     * @return  self
     */
    public function setClosed(int $closed)
    {
        $this->closed = $closed;

        return $this;
    }

    public function displayDateCreation()
    {
        $date = $this->getDateCreation();
        return $date->format('d/m/Y');
    }

    public function displayHeureCreation()
    {
        $date = $this->getDateCreation();
        return $date->format('H:i');
    }

    public function __toString()
        {
        return $this->title;
    }
}