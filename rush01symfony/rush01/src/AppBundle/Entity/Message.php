<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_from", type="integer")
     */
    private $idFrom;

    /**
     * @var int
     *
     * @ORM\Column(name="id_to", type="integer")
     */
    private $idTo;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime")
     */
    private $creation;


    public function __construct()
    {
        // your own logic
        $this->creation = new \DateTime("now");
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idFrom
     *
     * @param integer $idFrom
     *
     * @return Message
     */
    public function setIdFrom($idFrom)
    {
        $this->idFrom = $idFrom;

        return $this;
    }

    /**
     * Get idFrom
     *
     * @return int
     */
    public function getIdFrom()
    {
        return $this->idFrom;
    }

    /**
     * Set idTo
     *
     * @param integer $idTo
     *
     * @return Message
     */
    public function setIdTo($idTo)
    {
        $this->idTo = $idTo;

        return $this;
    }

    /**
     * Get idTo
     *
     * @return int
     */
    public function getIdTo()
    {
        return $this->idTo;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set creation
     *
     * @param \DateTime $creation
     *
     * @return Message
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * Get creation
     *
     * @return \DateTime
     */
    public function getCreation()
    {
        return $this->creation;
    }
}
