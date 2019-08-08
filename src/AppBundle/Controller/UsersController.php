<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\Company\CompanyServiceInterface;
use AppBundle\Service\Users\UserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller
{
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var CompanyServiceInterface
     */
    private $companyService;

    public function __construct(UserServiceInterface $userService,
                                CompanyServiceInterface $companyService)
    {
        $this->userService = $userService;
        $this->companyService = $companyService;
    }

    /**
     * @Route("/user/register", name="user_register", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function register()
    {
        $accounts = $this->userService->findAllUsersByRole('Account');
        $companies = $this->companyService->findAll();
        $form = $this->createForm(UserType::class)->createView();

        return $this->render("users/register.html.twig",
            [
                'form' => $form,
                'companies' => $companies,
                'accounts' => $accounts

            ]);
    }

    /**
     * @Route("/user/register", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function registerProcess(Request $request)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $this->userService->save($user);
        return $this->redirectToRoute("security_login");
    }

    /**
     * @Route("/profile",name="user_profile")
     */
    public function profile()
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $currentUser = $userRepository->find($this->getUser());

        return $this->render("users/profile.html.twig",
            ['user' => $currentUser]
        );
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/statistic", name="site_statistic")
     */
    public function statistics()
    {

        return $this->render('users/statistic.html.twig', [

        ]);
    }
}
