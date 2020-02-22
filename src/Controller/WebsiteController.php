<?php
/**
 * WebsiteController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 01, 2020 at 12:24:16
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WebsiteController
 * @package App\Controller
 * @Route("/")
 */
class WebsiteController extends AbstractController
{
    /**
     * @var string
     */
    const ROUTE_WEBSITE_INDEX = 'website_index';

    /**
     * @return Response
     * @Route("/", methods="GET", name="website_index")
     */
    public function index(): Response
    {
        return $this->render('website/index.html.twig');
    }
}
