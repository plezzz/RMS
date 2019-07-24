<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login()
    {
        return $this->render("security/login.html.twig");
    }

}
