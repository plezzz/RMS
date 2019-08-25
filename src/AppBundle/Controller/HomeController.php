<?php

namespace AppBundle\Controller;


use AppBundle\Service\Printers\PrinterService;
use AppBundle\Service\Printers\PrinterServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @var PrinterService
     */
    private $printerService;

    /**
     * HomeController constructor.
     * @param PrinterServiceInterface $printerService
     */
    public function __construct(PrinterServiceInterface $printerService)
    {
        $this->printerService = $printerService;
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
