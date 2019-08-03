<?php

namespace AppBundle\Controller;

use AppBundle\Form\PrinterType;
use AppBundle\Service\Common\FileUploaderService;
use AppBundle\Service\Company\CompanyServiceInterface;
use AppBundle\Service\Printers\ModelServiceInterface;
use AppBundle\Service\Printers\PrinterService;
use AppBundle\Service\Printers\PrinterServiceInterface;
use AppBundle\Service\Printers\StatusServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Printer;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrinterController extends Controller
{

    /**
     * @var ModelServiceInterface
     */
    private $modelService;

    /**
     * @var StatusServiceInterface
     */
    private $statusService;

    /**
     * @var PrinterService
     */
    private $printerService;
    /**
     * @var PrinterService
     */
    private $companyService;

    /**
     * @var FileUploaderService
     */
    private $fileUploadService;

    /**
     * CarController constructor.
     * @param ModelServiceInterface $modelService
     * @param StatusServiceInterface $statusService
     * @param PrinterServiceInterface $printerService
     * @param CompanyServiceInterface $companyService
     * @param FileUploaderService $fileUploadService
     */
    public function __construct(ModelServiceInterface $modelService,
                                StatusServiceInterface $statusService,
                                PrinterServiceInterface $printerService,
                                CompanyServiceInterface $companyService,
                                FileUploaderService $fileUploadService)
    {
        $this->modelService = $modelService;
        $this->statusService = $statusService;
        $this->printerService = $printerService;
        $this->companyService = $companyService;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * @param Request $request
     *
     * @Route("printer/add", name="add_printer", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $models = $this->modelService->findAll();
        $status = $this->statusService->findAll();
        $companies = $this->companyService->findAll();
        $printerOne = $this->printerService->findLastAdded();
        $printerOne = intval($printerOne[0]->getBatch()) + 1;

        $printer = new Printer();
        $form = $this->createForm(PrinterType::class, $printer);
        $form->handleRequest($request);

        return $this->render('printer/add.html.twig',
            [
                'form' => $form->createView(),
                'models' => $models,
                'status' => $status,
                'companies' => $companies,
                'printerOne' => $printerOne
            ]);
    }

    /**
     * @param Request $request
     *
     * @Route("printer/add", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return RedirectResponse|Response
     */
    public function createProcess(Request $request)
    {
        $printer = new Printer();
        $form = $this->createForm(PrinterType::class, $printer);
        $form->handleRequest($request);

        $imageFile = $form['image']->getData();
        if ($imageFile) {
            $imageFile = $this->fileUploadService->upload($imageFile);
            $printer->setImage($imageFile);
        }

        $technician = $this->getUser();
        $printer->setTechnician($technician);

        $status = $this->statusService->findOneBy("Diagnostic");
        $printer->setPrinterStatus($status);

        if ($printer->getSerialNumber() === null)
            $printer->setSerialNumber(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($printer);
        $em->flush();

        $this->addFlash("info", "Added printer successfully");
        return $this->redirectToRoute(
            'printer_view', ['id' => $printer->getId()]
        );
    }


    /**
     * @Route("/printer/{id}",name="printer_view" , methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @return Response
     */
    public function viewPrinter($id)
    {
        $printer = $this->printerService->findOneByID($id);

        if (null === $printer) {
            return $this->redirectToRoute("homepage");
        }
        $printers = $this->printerService->findAllBySerialNumber($printer[0]->getSerialNumber());

        return $this->render('printer/view.html.twig',
            [
                'printer' => $printer[0],
                'printers' => $printers
            ]);
    }

    /**
     * @Route("/printers/edit/{id}",name="printer_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editPrinter($id, Request $request)
    {
        $printer = $this->printerService->findOneByID($id);
        $models = $this->modelService->findAll();
        $status = $this->statusService->findAll();
        $companies = $this->companyService->findAll();

        if ($printer === null) {
            return $this->redirectToRoute("homepage");
        }


        $form = $this->createForm(PrinterType::class, $printer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($printer[0]);
            $em->flush();

            return $this->redirectToRoute("printer_view",
                [
                    'id' => $printer[0]->getId()
                ]);
        }


        return $this->render('printer/edit.html.twig',
            [
                'printer' => $printer[0],
                'form' => $form->createView(),
                'models' => $models,
                'status' => $status,
                'companies' => $companies,
            ]);
    }


    /**
     * @Route("/printers/delete/{id}",name="printer_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function delete($id, Request $request)
    {
        $printer = $this->printerService->findOneByID($id);

        if ($printer === null) {
            return $this->redirectToRoute("homepage");
        }

        $form = $this->createForm(PrinterType::class, $printer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($printer);
            $em->flush();

            return $this->redirectToRoute("homepage");
        }


        return $this->render('printer/delete.html.twig',
            array('printer' => $printer,
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/printers/last",name="printer_viewLast")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     *
     * @return Response
     */
    public function viewLastPrinter()
    {
        $printer = $this->printerService->findLastAdded();
        return $this->viewPrinter($printer[0]->getId());
    }

    /**
     * @return bool
     */
    private function isTechnicianOrAdmin()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser->isTechnician() && !$currentUser->isAdmin()) {
            return true;
        }
        return false;
    }

    /**
     * @Route("/printers/my_printers",name="my_printers")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function getAllPrintersByUser()
    {

        $printers = $this->getDoctrine()
            ->getRepository(Printer::class)
            ->findBy(
                ['author' => $this->getUser()],
                ['dateAdded' => 'DESC']
            );

        return $this->render("printer/myPrinters.html.twig",
            ["printers" => $printers]
        );
    }

    /**
     * @Route("/printers/all",name="printer_view_all")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     *
     * @return Response
     */
    public function viewPrinters()
    {
        $printers = $this->printerService->findAllDESC();


        return $this->render('printer/viewAll.html.twig',
            [
                'printers' => $printers
            ]);
    }

    /**
     * @Route("/test",name="test")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     *
     * @return Response
     */
    public function test()
    {
        $printers = $this->printerService->findAllDESC();


        return $this->render('printer/test.html.twig',
            [
                'printers' => $printers
            ]);
    }
}
