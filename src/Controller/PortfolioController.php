<?php
/**
 * PortfolioController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 01, 2020 at 12:20:32
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PortfolioController
 * @package App\Controller
 * @Route("/portfolio")
 */
class PortfolioController extends AbstractController
{
    /**
     * @var string
     */
    const ROUTE_PORTFOLIO_INDEX = 'portfolio_index';

    /**
     * @var string
     */
    const TEMPLATE_PORTFOLIO_INDEX = 'portfolio/index.html.twig';

    /**
     * @param Request $request
     * @return Response
     * @Route("/", methods="GET", name="portfolio_index")
     */
    public function index(Request $request): Response
    {
        return $this->render(self::TEMPLATE_PORTFOLIO_INDEX);
    }
}
