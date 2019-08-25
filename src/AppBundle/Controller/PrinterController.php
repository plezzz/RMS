<?php

namespace AppBundle\Controller;

use AppBundle\Form\PrinterType;
use AppBundle\Service\Common\VerificationsServiceInterface;
use AppBundle\Service\Printers\PrinterService;
use AppBundle\Service\Printers\PrinterServiceInterface;
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
     * @var PrinterService
     */
    private $printerService;

    /**
     * @var VerificationsServiceInterface
     */
    private $verificationsService;

    /**
     * PrinterController constructor.
     * @param PrinterServiceInterface $printerService
     * @param VerificationsServiceInterface $verificationsService
     */
    public function __construct(PrinterServiceInterface $printerService,
                                VerificationsServiceInterface $verificationsService)
    {
        $this->printerService = $printerService;
        $this->verificationsService = $verificationsService;
    }

    /**
     * @param Request $request
     *
     * @Route("printer/add", name="add_printer", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Security("has_role('ROLE_EMPLOYEE')")
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        /** @var Printer $printerLast * */
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
     * @Security("has_role('ROLE_EMPLOYEE')")
     *
     */
    public function createProcess(Request $request)
    {
        $printer = new Printer();
        $form = $this->createFormAndHandler($request, $printer);

        $this->printerService->add($printer, $form);

        return $this->redirectToRoute(
            'printer_view', ['id' => $printer->getId()]
        );
    }

    /**
     * @Route("/printer/edit/{id}", name="printer_edit", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Security("has_role('ROLE_EMPLOYEE')")
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
            $this->redirectToRoute("homepage");
        }

        $form = $this->createFormAndHandler($request, $printer);

        return $this->render('printer/edit.html.twig',
            [
                'printer' => $printer,
                'form' => $form->createView(),
                'models' => $models,
                'status' => $status,
                'companies' => $companies,
                'technicians' => $technicians
            ]);
    }

    /**
     * @Route("/printer/edit/{id}", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Security("has_role('ROLE_EMPLOYEE')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function editPrinterProcess($id, Request $request)
    {
        $printer = $this->printerService->findOneByID($id);

        $form = $this->createFormAndHandler($request, $printer);

        $this->printerService->edit($printer, $form);

        return $this->redirectToRoute("printer_view",
            [
                'id' => $printer->getId()
            ]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     * @throws ORMException
     * @Route("printer/delete/{id}",name="printer_delete", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Security("has_role('ROLE_EMPLOYEE')")
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
     * @Security("has_role('ROLE_EMPLOYEE')")
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
     * @Security("has_role('ROLE_EMPLOYEE')")
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
     * @Security("has_role('ROLE_EMPLOYEE')")
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
     * @Route("/printers/all",name="printer_view_all")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Security("has_role('ROLE_EMPLOYEE')")
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
