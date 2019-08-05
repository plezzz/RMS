<?php

namespace AppBundle\Entity;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;


/**
 * Printer
 *
 * @ORM\Table(name="printers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrinterRepository")
 */
class Printer
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
     * @ORM\Column(name="batch", type="string", length=255, nullable=true)
     */
    private $batch;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Model", inversedBy="printers")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id" )
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="problem_description", type="text", nullable=true)
     */
    private $problemDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="cartridge", type="text", nullable=true)
     */
    private $cartridge;

    /**
     * @var string
     *
     * @ORM\Column(name="serial_number", type="string", length=255, nullable=true)
     */
    private $serialNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="diagnostic", type="text", nullable=true)
     */
    private $diagnostic;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_description", type="text", nullable=true)
     */
    private $invoiceDescription;

    /**
     * @var bool
     *
     * @ORM\Column(name="warranty_sticker", type="boolean", nullable=true)
     */
    private $warrantySticker = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_comment", type="boolean", nullable=true)
     */
    private $showComment = false;

    /**
     * @var string
     *
     * @ORM\Column(name="counter", type="text", nullable=true)
     */
    private $counter;

    /**
     * @var string
     *
     * @ORM\Column(name="add_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $addPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $customerPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="add_protocol", type="string", length=255, nullable=true)
     */
    private $addProtocol;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="text", nullable=true)
     */
    private $comments;

    /**
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Status", inversedBy="printers")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id" )
     */
    private $printerStatus;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="printers")
     * @ORM\JoinColumn(name="technician", referencedColumnName="id" )
     *
     */
    private $technician;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="printers")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id" )
     */
    private $companyName;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_finish", type="datetime")
     */
    private $dateFinish;

    /**
     * @var string
     *
     * @ORM\Column(name="images", type="text", nullable=true)
     */
    private $image;

    public function __construct()
    {
            $this->dateAdded = $this->setFirstDateAdded();
            $this->dateFinish = $this->setFirstDateFinished();
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
     * Set batch
     *
     * @param string $batch
     *
     * @return Printer
     */
    public function setBatch($batch)
    {
        $this->batch = $batch;

        return $this;
    }

    /**
     * Get batch
     *
     * @return string
     */
    public function getBatch()
    {
        return $this->batch;
    }

    /**
     * @param Model $model
     * @return Model
     */
    public function setModel(Model $model)
    {
        return $this->model = $model;
    }

    /**
     * Get model.
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set problemDescription
     *
     * @param string $problemDescription
     *
     * @return Printer
     */
    public function setProblemDescription($problemDescription)
    {
        $this->problemDescription = $problemDescription;
        return $this;
    }

    /**
     * Get problemDescription
     *
     * @return string
     */
    public function getProblemDescription()
    {
        return $this->problemDescription;
    }

    /**
     * Set cartridge
     *
     * @param integer $cartridge
     *
     * @return Printer
     */
    public function setCartridge($cartridge)
    {
        $this->cartridge = $cartridge;

        return $this;
    }

    /**
     * Get cartridge
     *
     * @return int
     */
    public function getCartridge()
    {
        return $this->cartridge;
    }

    /**
     * Set serialNumber
     *
     * @param string $serialNumber
     *
     * @return Printer
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * Get serialNumber
     *
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * Set diagnostic
     *
     * @param string $diagnostic
     *
     * @return Printer
     */
    public function setDiagnostic($diagnostic)
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    /**
     * Get diagnostic
     *
     * @return string
     */
    public function getDiagnostic()
    {
        return $this->diagnostic;
    }

    /**
     * Set invoiceDescription
     *
     * @param string $invoiceDescription
     *
     * @return Printer
     */
    public function setInvoiceDescription($invoiceDescription)
    {
        $this->invoiceDescription = $invoiceDescription;

        return $this;
    }

    /**
     * Get invoiceDescription
     *
     * @return string
     */
    public function getInvoiceDescription()
    {
        return $this->invoiceDescription;
    }

    /**
     * Set warrantySticker
     *
     * @param boolean $warrantySticker
     *
     * @return bool
     */
    public function setWarrantySticker($warrantySticker)
    {
        return $this->warrantySticker = $warrantySticker;

    }

    /**
     * Get warrantySticker
     *
     * @return bool
     */
    public function getWarrantySticker()
    {
        return $this->warrantySticker;
    }

    /**
     * Set counter
     *
     * @param string $counter
     *
     * @return Printer
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * Get counter
     *
     * @return string
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * Set addPrice
     *
     * @param string $addPrice
     *
     * @return Printer
     */
    public function setAddPrice($addPrice)
    {
        $this->addPrice = $addPrice;

        return $this;
    }

    /**
     * Get addPrice
     *
     * @return string
     */
    public function getAddPrice()
    {
        return $this->addPrice;
    }

    /**
     * Set customerPrice
     *
     * @param string $customerPrice
     *
     * @return Printer
     */
    public function setCustomerPrice($customerPrice)
    {
        $this->customerPrice = $customerPrice;

        return $this;
    }

    /**
     * Get customerPrice
     *
     * @return string
     */
    public function getCustomerPrice()
    {
        return $this->customerPrice;
    }

    /**
     * Set addProtocol
     *
     * @param string $addProtocol
     *
     * @return Printer
     */
    public function setAddProtocol($addProtocol)
    {
        $this->addProtocol = $addProtocol;

        return $this;
    }

    /**
     * Get addProtocol
     *
     * @return string
     */
    public function getAddProtocol()
    {
        return $this->addProtocol;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Printer
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
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
     * @param Status $status
     * @return Status
     */
    public function setPrinterStatus(Status $status)
    {
        return $this->printerStatus = $status;
    }

    /**
     * Get status.
     *
     * @return Status
     */
    public function getPrinterStatus()
    {
        return $this->printerStatus;
    }

    /**
     * Get Company.
     *
     * @return Company
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param Company $companyName
     * @return void
     */
    public function setCompanyName(Company $companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return DateTime
     */
    public function getDateFinish(): DateTime
    {
        return $this->dateFinish;
    }

    /**
     * @param DateTime $dateFinish
     */
    public function setDateFinish(DateTime $dateFinish)
    {
        $this->dateFinish = $dateFinish;
    }

    /**
     * @return
     */
    public function getTechnician()
    {
        return $this->technician;
    }

    /**
     * @param User $technician
     */
    public function setTechnician(User $technician)
    {
        $this->technician = $technician;
    }


    /**
     * @return DateTime
     * @throws \Exception
     */
    public function setFirstDateAdded()
    {
        $timeZone = new DateTimeZone('Europe/Sofia');
        $datetime = new DateTime('now');
        $datetime->setTimezone($timeZone);
        return $datetime;
    }

    /**
     * @return DateTime
     * @throws \Exception
     */
    public function setFirstDateFinished()
    {
        $dateFinish = new DateTime('0000-00-00 00:00:00');
        return $dateFinish;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image)
    {
        $this->image = $image;
    }

    /**
     * @return bool
     */
    public function isShowComment(): bool
    {
        return $this->showComment;
    }

    /**
     * @param bool $showComment
     */
    public function setShowComment(bool $showComment): void
    {
        $this->showComment = $showComment;
    }
}
