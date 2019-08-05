<?php

namespace AppBundle\Controller;

use AppBundle\Form\PrinterType;
use AppBundle\Service\Common\FileUploaderService;
use AppBundle\Service\Company\CompanyServiceInterface;
use AppBundle\Service\Printers\ModelServiceInterface;
use AppBundle\Service\Printers\PrinterService;
use AppBundle\Service\Printers\PrinterServiceInterface;
use AppBundle\Service\Printers\StatusServiceInterface;
use AppBundle\Service\Users\UserServiceInterface;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Printer;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
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
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * PrinterController constructor.
     * @param ModelServiceInterface $modelService
     * @param StatusServiceInterface $statusService
     * @param PrinterServiceInterface $printerService
     * @param CompanyServiceInterface $companyService
     * @param FileUploaderService $fileUploadService
     * @param UserServiceInterface $userService
     */
    public function __construct(ModelServiceInterface $modelService,
                                StatusServiceInterface $statusService,
                                PrinterServiceInterface $printerService,
                                CompanyServiceInterface $companyService,
                                FileUploaderService $fileUploadService,
                                UserServiceInterface $userService)
    {
        $this->modelService = $modelService;
        $this->statusService = $statusService;
        $this->printerService = $printerService;
        $this->companyService = $companyService;
        $this->fileUploadService = $fileUploadService;
        $this->userService = $userService;
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
        /** @var Printer $printerLast**/
        list($models, $status, $companies, $technicians, $printerLast) = $this->printerService->getData();
        $newBatch = intval($printerLast->getBatch()) + 1;

        $printer = new Printer();
        $form = $this->createFormAndHandler($request, $printer);

        return $this->render('printer/add.html.twig',
            [
                'form' => $form->createView(),
                'models' => $models,
                'status' => $status,
                'companies' => $companies,
                'newBatch' => $newBatch,
                'technicians' => $technicians
            ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @throws ORMException
     * @Route("printer/add", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     */
    public function createProcess(Request $request)
    {
        $printer = new Printer();
        $form = $this->createFormAndHandler($request, $printer);

        $imageFile = $form['image']->getData();
        if ($imageFile) {
            $imageFile = $this->fileUploadService->upload($imageFile);
            $printer->setImage($imageFile);
        }

        $this->printerService->add($printer);

        return $this->redirectToRoute(
            'printer_view', ['id' => $printer->getId()]
        );
    }

    /**
     * @Route("/printer/edit/{id}", name="printer_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function editPrinter($id, Request $request)
    {
        $printer = $this->printerService->findOneByID($id);
        list($models, $status, $companies, $technicians) = $this->printerService->getData();

        if ($printer === null) {
            return $this->redirectToRoute("homepage");
        }
        $form = $this->createFormAndHandler($request, $printer);

        if ($form->isSubmitted()) {

            $imageFile = $form['image']->getData();
            if ($imageFile) {
                $imageFile = $this->fileUploadService->upload($imageFile);
                $printer->setImage($imageFile);
            }

            if ($printer->getPrinterStatus()=='Ready')
                $printer->setDateFinish($printer->setFirstDateAdded());

            $em = $this->getDoctrine()->getManager();
            $em->persist($printer);
            $em->flush();

            return $this->redirectToRoute("printer_view",
                [
                    'id' => $printer->getId()
                ]);
        }

        return $this->render('printer/edit.html.twig',
            [
                'printer' => $printer,
                'form' => $form->createView(),
                'models' => $models,
                'status' => $status,
                'companies' => $companies,
                'technicians'=>$technicians
            ]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     * @throws ORMException
     * @Route("printer/delete/{id}",name="printer_delete", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */

    public function delete(int $id)
    {
        $this->printerService->delete($id);

        return $this->redirectToRoute(
            'homepage', []
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
        $printers = $this->printerService->findAllBySerialNumber($printer->getSerialNumber());

        return $this->render('printer/view.html.twig',
            [
                'printer' => $printer,
                'printers' => $printers
            ]);
    }

    /**
     * @Route("/printers/last",name="printer_viewLast")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function viewLastPrinter()
    {
        $printer = $this->printerService->findLastAdded();
        return $this->viewPrinter($printer->getId());
    }

    /**
     * @Route("/printers/edit",name="printer_editLast")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function editLastPrinter(Request $request)
    {
        $printer = $this->printerService->findLastAdded();
        return $this->editPrinter($printer->getId(), $request);
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
     * @param Request $request
     * @param Printer $printer
     * @return FormInterface
     */
    public function createFormAndHandler(Request $request, Printer $printer)
    {
        $form = $this->createForm(PrinterType::class, $printer);
        $form->handleRequest($request);
        return $form;
    }
}
