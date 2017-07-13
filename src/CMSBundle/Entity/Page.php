<?php

namespace CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="CMSBundle\Repository\PageRepository")
 * @UniqueEntity(fields="lien", message="Une page avec ce lien existe déjà")
 */
class Page
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text", nullable=true)
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=255)
     *
     * @Assert\Regex(
     *     pattern="/\s/",
     *     match=false,
     *     message="pas d\'espaces"
     * )
     * @Assert\Regex(
     *     pattern="#^\d+$#",
     *     match=false,
     *     message="pas que des chiffres"
     * )
     */

    private $lien;

    /**
     * @var bool
     *
     * @ORM\Column(name="topPublic", type="boolean")
     */
    private $topPublic;

    /**
     * @var bool
     *
     * @ORM\Column(name="topPrivate", type="boolean")
     */
    private $topPrivate;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;


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
     * Set user
     *
     * @param User $user
     *
     * @return Page
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Page
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Page
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set lien
     *
     * @param string $lien
     *
     * @return Page
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * Set topPublic
     *
     * @param boolean $topPublic
     *
     * @return Page
     */
    public function setTopPublic($topPublic)
    {
        $this->topPublic = $topPublic;

        return $this;
    }

    /**
     * Get topPublic
     *
     * @return bool
     */
    public function getTopPublic()
    {
        return $this->topPublic;
    }

    /**
     * Set topPrivate
     *
     * @param boolean $topPrivate
     *
     * @return Page
     */
    public function setTopPrivate($topPrivate)
    {
        $this->topPrivate = $topPrivate;

        return $this;
    }

    /**
     * Get topPrivate
     *
     * @return bool
     */
    public function getTopPrivate()
    {
        return $this->topPrivate;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Page
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}

