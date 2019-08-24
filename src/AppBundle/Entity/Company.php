<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 */
class Company
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="companyName")
     */
    private $users;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Printer", mappedBy="companyName")
     */
    private $printers;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="companiesAdded")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id" )
     */
    private $userAdded;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->printers = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers(): ArrayCollection
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     */
    public function setUsers(ArrayCollection $users)
    {
        $this->users = $users;
    }

    /**
     * @return ArrayCollection
     */
    public function getPrinters(): ArrayCollection
    {
        return $this->printers;
    }

    /**
     * @param ArrayCollection $printers
     */
    public function setPrinters(ArrayCollection $printers)
    {
        $this->printers = $printers;
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image)
    {
        $this->image = $image;
    }

    /**
     * @return DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param DateTime $dateAdded
     */
    public function setDateAdded(DateTime $dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return User
     */
    public function getUserAdded()
    {
        return $this->userAdded;
    }

    /**
     * @param User $userAdded
     */
    public function setUserAdded(User $userAdded)
    {
        $this->userAdded = $userAdded;
    }

    public function __toString()
    {
       return $this->getName();
    }
}
