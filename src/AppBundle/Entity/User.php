<?php

namespace AppBundle\Entity;

use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="users")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id" )
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255)
     */
    private $fullName;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Printer", mappedBy="technician")
     */
    private $printers;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone", type="string", length=255, nullable=true)
     */
    private $mobilePhone;

    /**
     * @var string
     *
     * @ORM\Column(name="external_phone", type="string", length=255, nullable=true)
     */
    private $externalPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="extension_phone", type="string", length=255, nullable=true)
     */
    private $extensionPhone;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->printers = new ArrayCollection();
        $this->dateAdded = $this->dateAdded();
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
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $stringRoles = [];
        foreach ($this->roles as $role) {
            /** @var Role $role */
            $stringRoles[] = $role->getName();
        }
        return $stringRoles;
    }

    /**
     * @param Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
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



    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isTechnician()
    {
        return in_array("ROLE_TECHNICIAN", $this->getRoles());
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return in_array("ROLE_ADMIN", $this->getRoles());
    }

    /**
     * @return bool
     */
    public function isAccount()
    {
        return in_array("ROLE_ACCOUNT", $this->getRoles());
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function __toString()
    {
       return $this->fullName;
    }

    /**
     * @return Company
     */
    public function getCompanyName(): Company
    {
        return $this->companyName;
    }

    /**
     * @param Company $companyName
     */
    public function setCompanyName(Company $companyName): void
    {
        $this->companyName = $companyName;
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
     * @return User
     */
    public function setPrinters(ArrayCollection $printers)
    {
        $this->printers = $printers;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateAdded(): DateTime
    {
        return $this->dateAdded;
    }

    /**
     * @param DateTime $dateAdded
     */
    public function setDateAdded(DateTime $dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }
    /**
     * @return DateTime
     * @throws \Exception
     */
    public function dateAdded()
    {
        $timeZone = new DateTimeZone('Europe/Sofia');
        $datetime = new DateTime('now');
        $datetime->setTimezone($timeZone);
        return $datetime;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getMobilePhone(): string
    {
        return $this->mobilePhone;
    }

    /**
     * @param string $mobilePhone
     */
    public function setMobilePhone(string $mobilePhone): void
    {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * @return string
     */
    public function getExternalPhone(): string
    {
        return $this->externalPhone;
    }

    /**
     * @param string $externalPhone
     */
    public function setExternalPhone(string $externalPhone): void
    {
        $this->externalPhone = $externalPhone;
    }

    /**
     * @return string
     */
    public function getExtensionPhone(): string
    {
        return $this->extensionPhone;
    }

    /**
     * @param string $extensionPhone
     */
    public function setExtensionPhone(string $extensionPhone): void
    {
        $this->extensionPhone = $extensionPhone;
    }
}
