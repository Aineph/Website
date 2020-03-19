<?php
/**
 * AdminController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 23, 2020 at 15:46:01
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Message;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Form\MissionFormType;
use App\Form\ProjectFormType;
use App\Service\MissionService;
use App\Service\ProjectService;
use App\Service\UserService;
use App\Service\ArticleService;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @return Response
     * @Route("/", methods="GET", name="admin_index")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @param int $page
     * @param UserService $userService
     * @return Response
     * @Route("/users/{page?0}", methods="GET", name="admin_users", requirements={"page"="\d+"})
     */
    public function users(int $page, UserService $userService)
    {
        return $this->render('admin/users.html.twig', [
            'userPaginator' => $userService->getPage($page)
        ]);
    }

    /**
     * @param int $user
     * @param UserService $userService
     * @return Response
     * @Route("/user/{user?0}", methods="GET", name="admin_user", requirements={"user"="\d+"})
     */
    public function user(int $user, UserService $userService)
    {
        return $this->render('admin/user.html.twig', [
            'user' => $userService->get($user)
        ]);
    }

    /**
     * @param int $user
     * @param UserService $userService
     * @return RedirectResponse
     * @Route("/user_delete/{user?0}", methods="GET", name="admin_user_delete", requirements={"user"="\d+"})
     */
    public function user_delete(int $user, UserService $userService)
    {
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @param int $page
     * @param MessageService $messageService
     * @return Response
     * @Route("/messages/{page?0}", methods="GET", name="admin_messages", requirements={"page"="\d+"})
     */
    public function messages(int $page, MessageService $messageService)
    {
        return $this->render('admin/messages.html.twig', [
            'messagePaginator' => $messageService->getPage($page)
        ]);
    }

    /**
     * @param int $message
     * @param MessageService $messageService
     * @return Response
     * @Route("/message/{message?0}", methods="GET", name="admin_message", requirements={"message"="\d+"})
     */
    public function message(int $message, MessageService $messageService)
    {
        return $this->render('admin/message.html.twig', [
            'message' => $messageService->get($message)
        ]);
    }

    /**
     * @param int $message
     * @param MessageService $messageService
     * @return RedirectResponse
     * @Route("/message_delete/{message?0}", methods="GET", name="admin_message_delete", requirements={"message"="\d+"})
     */
    public function message_delete(int $message, MessageService $messageService)
    {
        $messageService->delete($message);
        return $this->redirectToRoute('admin_messages');
    }

    /**
     * @param int $page
     * @param ArticleService $articleService
     * @return Response
     * @Route("/articles/{page?0}", methods="GET", name="admin_articles", requirements={"page"="\d+"})
     */
    public function articles(int $page, ArticleService $articleService)
    {
        return $this->render('admin/articles.html.twig', [
            'articlePaginator' => $articleService->getPage($page)
        ]);
    }

    /**
     * @param int $article
     * @param ArticleService $articleService
     * @return Response
     * @Route("/article/{article?0}", methods="GET", name="admin_article", requirements={"article"="\d+"})
     */
    public function article(int $article, ArticleService $articleService)
    {
        return $this->render('admin/article.html.twig', [
            'article' => ''
        ]);
    }

    /**
     * @param int $article
     * @param ArticleService $articleService
     * @return RedirectResponse
     * @Route("/article_delete/{article?0}", methods="GET", name="admin_article_delete", requirements={"article"="\d+"})
     */
    public function article_delete(int $article, ArticleService $articleService)
    {
        return $this->redirectToRoute('admin_articles');
    }

    /**
     * @param int $page
     * @param MissionService $missionService
     * @return Response
     * @Route("/missions/{page?0}", methods="GET", name="admin_missions", requirements={"page"="\d+"})
     */
    public function missions(int $page, MissionService $missionService)
    {
        $missionForm = $this->createForm(MissionFormType::class, $missionService->getMission());

        return $this->render('admin/missions.html.twig', [
            'missionPaginator' => $missionService->getPage($page),
            'missionForm' => $missionForm->createView()
        ]);
    }

    /**
     * @param int $mission
     * @return void
     * @Route("/mission/{mission?0}", methods="GET", name="admin_mission", requirements={"mission"="\d+"})
     */
    public function mission(int $mission)
    {
    }

    /**
     * @param Request $request
     * @param MissionService $missionService
     * @return RedirectResponse
     * @Route("/missions/create", methods="POST", name="admin_mission_create")
     */
    public function mission_create(Request $request, MissionService $missionService)
    {
        $missionForm = $this->createForm(MissionFormType::class, $missionService->getMission());

        $missionForm->handleRequest($request);
        if ($missionForm->isSubmitted() && $missionForm->isValid()) {
            $missionService->create();
        }
        return $this->redirectToRoute('admin_missions');
    }

    /**
     * @param int $page
     * @param ProjectService $projectService
     * @return Response
     * @Route("/projects/{page?0}", methods="GET", name="admin_projects", requirements={"page"="\d+"})
     */
    public function projects(int $page, ProjectService $projectService)
    {
        $projectForm = $this->createForm(ProjectFormType::class, $projectService->getProject());

        return $this->render('admin/projects.html.twig', [
            'projectPaginator' => $projectService->getPage($page),
            'projectForm' => $projectForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param ProjectService $projectService
     * @return RedirectResponse
     * @Route("/projects/create", methods="POST", name="admin_project_create", requirements={"page"="\d+"})
     */
    public function project_create(Request $request, ProjectService $projectService)
    {
        $projectForm = $this->createForm(ProjectFormType::class, $projectService->getProject());

        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $projectService->create();
        }
        return $this->redirectToRoute('admin_projects');
    }

    /**
     * @param Request $request
     * @param ArticleService $articleService
     * @return Response
     * @Route("/article/new", methods="GET|POST", name="admin_post")
     */
    public function post(Request $request, ArticleService $articleService)
    {
        $articleForm = $this->createForm(ArticleFormType::class, $articleService->getArticle());

        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $articleService->create();
        }
        return $this->render('admin/post.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);
    }
}
