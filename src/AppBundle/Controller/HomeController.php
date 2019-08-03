<?php

namespace AppBundle\Controller;

use AppBundle\Service\Company\CompanyServiceInterface;
use AppBundle\Service\Printers\ModelServiceInterface;
use AppBundle\Service\Printers\PrinterService;
use AppBundle\Service\Printers\PrinterServiceInterface;
use AppBundle\Service\Printers\StatusServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @var PrinterService
     */
    private $printerService;

    /**
     * @var PrinterService
     */
    private $companyService;

    /**
     * @var ModelServiceInterface
     */
    private $modelService;

    /**
     * @var StatusServiceInterface
     */
    private $statusService;

    /**
     * CarController constructor.
     * @param ModelServiceInterface $modelService
     * @param StatusServiceInterface $statusService
     * @param PrinterServiceInterface $printerService
     * @param CompanyServiceInterface $companyService
     */
    public function __construct(ModelServiceInterface $modelService,
                                StatusServiceInterface $statusService,
                                PrinterServiceInterface $printerService,
                                CompanyServiceInterface $companyService)
    {
        $this->modelService = $modelService;
        $this->statusService = $statusService;
        $this->printerService = $printerService;
        $this->companyService = $companyService;
    }
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/homepage", name="homepage")
     */
    public function indexAction()
    {
        $printers = $this->printerService->findAll();

        return $this->render('default/index.html.twig', [
            'printers' => $printers
        ]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/rss", name="rss")
     */
    public function viewRSSAction(){
        $rss = simplexml_load_file('https://www.investor.bg/news/rss/last/260/');
        return $this->render('default/rss.html.twig', array(
            'rss' => $rss,
        ));
    }
}
