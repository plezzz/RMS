<?php

namespace AppBundle\Controller;


use AppBundle\Service\Printers\PrinterService;
use AppBundle\Service\Printers\PrinterServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $notFinished =$this->printerService->getNotReady();

        return $this->render('default/index.html.twig', [
            'printers' => $printers,
            "notFinished" => $notFinished
        ]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/rss", name="rss")
     */
    public function viewRSSAction(){
        $weatherUrl = 'http://api.openweathermap.org/data/2.5/weather?id=727011&appid=30d5259cc6973abc960849ee29b751ff&units=metric&lang=bg';
        $weather = json_decode(file_get_contents($weatherUrl), true);

        $newsUrl = "https://www.investor.bg/news/rss/last/260/";
        $news = simplexml_load_file($newsUrl);
        return $this->render('default/rss.html.twig', [
            'news' => $news,
            'weather' => $weather
        ]);
    }
}
