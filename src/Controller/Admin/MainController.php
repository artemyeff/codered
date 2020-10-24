<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/{url<.*>}", methods={"GET"})
 *
 * Class MainController
 * @package App\Controller\Admin
 */
class MainController extends AbstractController
{
    public function __invoke()
    {
        return $this->render('admin/main.html.twig');
    }
}
