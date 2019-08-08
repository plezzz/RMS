<?php


namespace AppBundle\Service\Common;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method redirect(string $string)
 */
class VerificationsService extends controller implements VerificationsServiceInterface
{
    /**
     * @return RedirectResponse
     */
    public function checkElement()
    {
        return $this->redirectToRoute('homepage');
    }


}
