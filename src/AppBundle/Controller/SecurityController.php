<?php

namespace AppBundle\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/", name="security_login")
     * @return Response
     */
    public function login()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        return $this->render("security/login.html.twig");
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
