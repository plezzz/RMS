<?php

namespace AppBundle\Controller;

use AppBundle\Form\PrinterType;
use AppBundle\Service\Printers\ModelServiceInterface;
use AppBundle\Service\Printers\StatusServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Printer;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * CarController constructor.
     * @param ModelServiceInterface $modelService
     * @param StatusServiceInterface $statusService
     */
    public function __construct(ModelServiceInterface $modelService,
                                StatusServiceInterface $statusService)
    {
        $this->modelService = $modelService;
        $this->statusService = $statusService;
    }

    /**
     * @param Request $request
     *
     * @Route("printer/add", name="add_printer")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $models = $this->modelService->findAll();
        $status = $this->statusService->findAll();

        $printer = new Printer();
        $form = $this->createForm(PrinterType::class, $printer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $technician = $this->getUser();
            $printer->setTechnician($technician);

            $status = $this->statusService->findOneBy("Diagnostic");
            $printer->setPrinterStatus($status);

            $em = $this->getDoctrine()->getManager();
            $em->persist($printer);
            $em->flush();

            $this->addFlash("info", "Added printer successfully");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('printer/add.html.twig',
            [
                'form' => $form->createView(),
                'models' => $models,
                'status' => $status
            ]);

    }

    /**
     * @Route("/printer/{id}",name="printer_view")
     *
     * @param $id
     * @return Response
     */
    public function viewPrinter($id)
    {

        $printer = $this->getDoctrine()
            ->getRepository(Printer::class)
            ->find($id);

        if (null === $printer) {
            return $this->redirectToRoute("homepage");
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($printer);
        $em->flush();

        return $this->render('printer/view.html.twig', [
            'printer' => $printer
        ]);
    }

    /**
     * @Route("/printer/edit/{id}",name="printer_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editPrinter($id, Request $request)
    {
        $printer = $this->getDoctrine()->getRepository(Printer::class)->find($id);

        if ($printer === null) {
            return $this->redirectToRoute("homepage");
        }

        if ($this->isTechnicianOrAdmin()) {
            return $this->redirectToRoute("homepage");
        }

        $form = $this->createForm(PrinterType::class, $printer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($printer);
            $em->flush();

            return $this->redirectToRoute("printer_view",
                array('id' => $printer->getId())
            );
        }


        return $this->render('printer/edit.html.twig',
            array('printer' => $printer,
                'form' => $form->createView()
            ));
    }


    /**
     * @Route("/printer/delete/{id}",name="printer_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function delete($id, Request $request)
    {
        $printer = $this->getDoctrine()->getRepository(Printer::class)->find($id);

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
     * @param Printer $printer
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
}
