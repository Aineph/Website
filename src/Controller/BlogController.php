<?php
/**
 * BlogController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 09, 2020 at 15:39:50
 */

namespace App\Controller;

use App\Entity\Article;
use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @param int $page
     * @param ArticleService $articleService
     * @return Response
     * @Route("/{page?0}", name="blog_index")
     */
    public function index(int $page, ArticleService $articleService)
    {
        return $this->render('blog/index.html.twig', [
            'articlePaginator' => $articleService->getPage($page)
        ]);
    }

    /**
     * @Route("/comment/{postSlug}/new", name="blog_comment")
     */
    public function comment()
    {
        return $this->render('blog/index.html.twig');
    }
}
