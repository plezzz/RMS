<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Form\CompanyType;
use AppBundle\Service\Company\CompanyServiceInterface;
use AppBundle\Service\Printers\PrinterService;
use AppBundle\Service\Printers\PrinterServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends Controller
{
    /**
     * @var CompanyServiceInterface
     */
    private $companyService;

    /**
     * @var PrinterServiceInterface
     */
    private $printerService;

    public function __construct(CompanyServiceInterface $companyService,
                                PrinterServiceInterface $printerService)
    {
        $this->companyService = $companyService;
        $this->printerService=$printerService;
    }


    /**
     * @param Request $request
     *
     * @Route("company/add", name="add_company", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $company = new Company();
        $form = $this->createFormAndHandler($request, $company);

        return $this->render('users/company/add.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }


    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @Route("company/add", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function createProcess(Request $request)
    {
        $company = new Company();
        $form = $this->createFormAndHandler($request, $company);
        $this->companyService->add($company, $form);

        return $this->redirectToRoute(
            'company_view', ['id' => $company->getId()]
        );
    }

    /**
     * @Route("/company/edit/{id}", name="company_edit", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function editCompany($id, Request $request)
    {
        $company = $this->companyService->findOneById($id);

        if ($company === null) {
            $this->redirectToRoute("homepage");
        }

        $form = $this->createFormAndHandler($request, $company);

        return $this->render('users/Company/edit.html.twig',
            [
                'company' => $company,
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/company/edit/{id}", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function editCompanyProcess($id, Request $request)
    {
        $company = $this->companyService->findOneByID($id);

        $form = $this->createFormAndHandler($request, $company);

        $this->companyService->edit($company, $form);

        return $this->redirectToRoute("company_view",
            [
                'id' => $company->getId()
            ]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     * @Route("company/delete/{id}",name="company_delete", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */

    public function delete(int $id)
    {
        $this->companyService->delete($id);

        return $this->redirectToRoute(
            'homepage', []
        );
    }

    /**
     * @Route("/company/{id}",name="company_view" , methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Security("is_granted(['ROLE_ADMIN','ROLE_TECHNICIAN'] )")
     *
     * @param $id
     * @return Response
     */
    public function viewCompany($id)
    {
        $company = $this->companyService->findOneByID($id);
        if (null === $company) {
            return $this->redirectToRoute("homepage");
        }
        $printers=$this->printerService->findAllByCompanyId($id);

        return $this->render('users/Company/view.html.twig',
            [
                'company' => $company,
                'printers' => $printers
            ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return FormInterface
     */
    public function createFormAndHandler(Request $request, Company $company)
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        return $form;
    }


}
