<?php
namespace Model\Entities;

use App\Entity;

/*
    En programmation orientée objet, une classe finale (final class) est une classe que vous ne pouvez pas étendre, c'est-à-dire qu'aucune autre classe ne peut hériter de cette classe. En d'autres termes, une classe finale ne peut pas être utilisée comme classe parente.
*/

final class Post extends Entity
{

    private int $id;
    private string $contenu;
    private \DateTime $dateMessage;
    private User $user;
    private Topic $topic;
    // private Post $post;

    // chaque entité aura le même constructeur grâce à la méthode hydrate (issue de App\Entity)
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
     * Get the value of contenu
     */
    public function getContenu(): string
    {
        return $this->contenu;
    }

    /**
     * Set the value of contenu
     *
     * @return  self
     */
    public function setContenu(string $contenu)
    {
        $this->contenu = $contenu;
        return $this;
    }

    /**
     * Get the value of dateMessage
     */
    public function getDateMessage()
    {
        return $this->dateMessage;
    }

    /**
     * Set the value of dateMessage
     *
     * @return  self
     */
    public function setDateMessage($dateMessage)
    {
        $this->dateMessage = new \DateTime($dateMessage);

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set the value of topic
     *
     * @return  self
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    // /**
    //  * Get the value of post
    //  */
    // public function getPost(): Post
    // {
    //     return $this->post;
    // }

    // /**
    //  * Set the value of post
    //  *
    //  * @return  self
    //  */
    // public function setPost(Post $post)
    // {
    //     $this->post = $post;

    //     return $this;
    // }


    public function displayDateMessage()
    {
        $date = $this->getDateMessage();
        return $date->format('d/m/Y');
    }

    public function displayHeureMessage()
    {
        $date = $this->getDateMessage();
        return $date->format('H:i');
    }


    public function __toString()
    {
        return $this->contenu;
    }

}