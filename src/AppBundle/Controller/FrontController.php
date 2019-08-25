<?php


namespace AppBundle\Controller;

use AppBundle\Service\Roles\RoleServiceInterface;
use AppBundle\Service\Users\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends Controller
{
    private $userService;
    private $roleService;

    /**
     * FrontController constructor.
     * @param UserServiceInterface $userService
     * @param RoleServiceInterface $roleService
     */
    public function __construct(UserServiceInterface $userService,
                                RoleServiceInterface $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * @Route("/contacts", name="user_contacts")
     */
    public function contacts()
    {

        $accounts = $this->userService->getAccountsPhones();
        return $this->render('front/contact.html.twig', [
            "accounts" => $accounts
        ]);
    }

}
