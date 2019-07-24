<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\Users\UserServiceInterface;
use Exception;
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

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("register", name="user_register", methods={"GET"})
     * @return Response
     */
    public function register()
    {
        return $this->render("users/register.html.twig",
            ['form' => $this->createForm(UserType::class)->createView()]);
    }

    /**
     * @Route("register", methods={"POST"})
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
     * @Route("/logout",name="security_logout")
     *
     * @throws Exception
     */
    public function logout()
    {
        throw new Exception("Logout failed!");
    }
}
