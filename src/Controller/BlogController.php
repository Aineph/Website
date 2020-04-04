<?php
/**
 * BlogController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 09, 2020 at 15:39:50
 */

namespace App\Controller;

use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @var string
     */
    const TEMPLATE_BLOG_INDEX = 'blog/index.html.twig';

    /**
     * @var string
     */
    const TEMPLATE_BLOG_ARTICLE = 'blog/article.html.twig';

    /**
     * The control for the blog index.
     * @param int $page
     * @param Request $request
     * @param ArticleService $articleService
     * @return Response
     * @Route("/{page?0}", name="blog_index", requirements={"page"="\d+"})
     */
    public function index(int $page, Request $request, ArticleService $articleService)
    {
        $search = $request->get('search');

        return $this->render(self::TEMPLATE_BLOG_INDEX, [
            'articlePaginator' => $articleService->getPage($page, $search)
        ]);
    }

    /**
     * The control for the blog article.
     * @param int $article
     * @param ArticleService $articleService
     * @return Response
     * @Route("/article/{article?0}", name="blog_article", requirements={"article"="\d+"})
     */
    public function article(int $article, ArticleService $articleService)
    {
        return $this->render(self::TEMPLATE_BLOG_ARTICLE, [
            'article' => $articleService->get($article)
        ]);
    }
}
