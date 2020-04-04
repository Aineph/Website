<?php
/**
 * AdminController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 23, 2020 at 15:46:01
 */

namespace App\Controller;

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
     * @var string
     */
    const TEMPLATE_ADMIN_INDEX = 'admin/index.html.twig';

    /**
     * @var string
     */
    const TEMPLATE_ADMIN_USERS = 'admin/users.html.twig';

    /**
     * @var string
     */
    const ROUTE_ADMIN_USERS = 'admin_users';

    /**
     * @var string
     */
    const TEMPLATE_ADMIN_USER = 'admin/user.html.twig';

    /**
     * @var string
     */
    const TEMPLATE_ADMIN_MESSAGES = 'admin/messages.html.twig';

    /**
     * @var string
     */
    const ROUTE_ADMIN_MESSAGES = 'admin_messages';

    /**
     * @var string
     */
    const TEMPLATE_ADMIN_MESSAGE = 'admin/message.html.twig';

    /**
     * @var string
     */
    const TEMPLATE_ADMIN_ARTICLES = 'admin/articles.html.twig';

    /**
     * @var string
     */
    const ROUTE_ADMIN_ARTICLES = 'admin_articles';

    /**
     * @var string
     */
    const TEMPLATE_ADMIN_ARTICLE = 'admin/article.html.twig';

    /**
     * @var string
     */
    const TEMPLATE_ADMIN_MISSIONS = 'admin/missions.html.twig';

    /**
     * @var string
     */
    const ROUTE_ADMIN_MISSIONS = 'admin_missions';

    /**
     * @var string
     */
    const TEMPLATE_ADMIN_MISSION = 'admin/mission.html.twig';

    /**
     * @var string
     */
    const TEMPLATE_ADMIN_PROJECTS = 'admin/projects.html.twig';

    /**
     * @var string
     */
    const ROUTE_ADMIN_PROJECTS = 'admin_projects';

    /**
     * The control for the admin index.
     * @return Response
     * @Route("/", methods="GET", name="admin_index")
     */
    public function index()
    {
        return $this->render(self::TEMPLATE_ADMIN_INDEX);
    }

    /**
     * The control for the admin users.
     * @param int $page
     * @param UserService $userService
     * @return Response
     * @Route("/users/{page?0}", methods="GET", name="admin_users", requirements={"page"="\d+"})
     */
    public function users(int $page, UserService $userService)
    {
        return $this->render(self::TEMPLATE_ADMIN_USERS, [
            'userPaginator' => $userService->getPage($page)
        ]);
    }

    /**
     * The control for the admin user.
     * @param int $user
     * @param UserService $userService
     * @return Response
     * @Route("/user/{user?0}", methods="GET", name="admin_user", requirements={"user"="\d+"})
     */
    public function user(int $user, UserService $userService)
    {
        return $this->render(self::TEMPLATE_ADMIN_USER, [
            'user' => $userService->get($user)
        ]);
    }

    /**
     * The control for the admin user delete.
     * @param int $user
     * @param UserService $userService
     * @return RedirectResponse
     * @Route("/user/delete/{user?0}", methods="GET", name="admin_user_delete", requirements={"user"="\d+"})
     */
    public function user_delete(int $user, UserService $userService)
    {
        $userService->setUser($userService->get($user));
        $userService->delete();
        return $this->redirectToRoute(self::ROUTE_ADMIN_USERS);
    }

    /**
     * The control for the admin messages.
     * @param int $page
     * @param MessageService $messageService
     * @return Response
     * @Route("/messages/{page?0}", methods="GET", name="admin_messages", requirements={"page"="\d+"})
     */
    public function messages(int $page, MessageService $messageService)
    {
        return $this->render(self::TEMPLATE_ADMIN_MESSAGES, [
            'messagePaginator' => $messageService->getPage($page)
        ]);
    }

    /**
     * The control for the admin message.
     * @param int $message
     * @param MessageService $messageService
     * @return Response
     * @Route("/message/{message?0}", methods="GET", name="admin_message", requirements={"message"="\d+"})
     */
    public function message(int $message, MessageService $messageService)
    {
        return $this->render(self::TEMPLATE_ADMIN_MESSAGE, [
            'message' => $messageService->get($message)
        ]);
    }

    /**
     * The control for the admin message delete.
     * @param int $message
     * @param MessageService $messageService
     * @return RedirectResponse
     * @Route("/message/delete/{message?0}", methods="GET", name="admin_message_delete", requirements={"message"="\d+"})
     */
    public function message_delete(int $message, MessageService $messageService)
    {
        $messageService->setMessage($messageService->get($message));
        $messageService->delete();
        return $this->redirectToRoute(self::ROUTE_ADMIN_MESSAGES);
    }

    /**
     * The control for the admin articles.
     * @param int $page
     * @param ArticleService $articleService
     * @return Response
     * @Route("/articles/{page?0}", methods="GET", name="admin_articles", requirements={"page"="\d+"})
     */
    public function articles(int $page, ArticleService $articleService)
    {
        return $this->render(self::TEMPLATE_ADMIN_ARTICLES, [
            'articlePaginator' => $articleService->getPage($page)
        ]);
    }

    /**
     * The control for the admin article.
     * @param int $article
     * @param ArticleService $articleService
     * @return Response
     * @Route("/article/{article?0}", methods="GET", name="admin_article", requirements={"article"="\d+"})
     */
    public function article(int $article, ArticleService $articleService)
    {
        $articleEntity = $articleService->get($article);

        if ($articleEntity) {
            $articleService->setArticle($articleEntity);
        }
        $articleForm = $this->createForm(ArticleFormType::class, $articleService->getArticle());

        return $this->render(self::TEMPLATE_ADMIN_ARTICLE, [
            'articleForm' => $articleForm->createView(),
            'article' => $articleService->getArticle()
        ]);
    }

    /**
     * The control for the admin article create.
     * @param int $article
     * @param Request $request
     * @param ArticleService $articleService
     * @return RedirectResponse
     * @Route("/article/create/{article?0}", methods="POST", name="admin_article_create", requirements={"article"="\d+"})
     */
    public function article_create(int $article, Request $request, ArticleService $articleService)
    {
        $articleEntity = $articleService->get($article);

        if ($articleEntity) {
            $articleService->setArticle($articleEntity);
        }
        $articleForm = $this->createForm(ArticleFormType::class, $articleService->getArticle());

        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $articleService->update();
        }
        return $this->redirectToRoute(self::ROUTE_ADMIN_ARTICLES);
    }

    /**
     * The control for the admin article delete.
     * @param int $article
     * @param ArticleService $articleService
     * @return RedirectResponse
     * @Route("/article/delete/{article?0}", methods="GET", name="admin_article_delete", requirements={"article"="\d+"})
     */
    public function article_delete(int $article, ArticleService $articleService)
    {
        $articleService->setArticle($articleService->get($article));
        $articleService->delete();
        return $this->redirectToRoute(self::ROUTE_ADMIN_ARTICLES);
    }

    /**
     * The control for the admin missions.
     * @param int $page
     * @param MissionService $missionService
     * @return Response
     * @Route("/missions/{page?0}", methods="GET", name="admin_missions", requirements={"page"="\d+"})
     */
    public function missions(int $page, MissionService $missionService)
    {
        $missionForm = $this->createForm(MissionFormType::class, $missionService->getMission());

        return $this->render(self::TEMPLATE_ADMIN_MISSIONS, [
            'missionPaginator' => $missionService->getPage($page),
            'missionForm' => $missionForm->createView()
        ]);
    }

    /**
     * The control for the admin mission.
     * @param int $mission
     * @param MissionService $missionService
     * @return Response
     * @Route("/mission/{mission?0}", methods="GET", name="admin_mission", requirements={"mission"="\d+"})
     */
    public function mission(int $mission, MissionService $missionService)
    {
        $missionEntity = $missionService->get($mission);

        if ($missionEntity) {
            $missionService->setMission($missionEntity);
        }
        $missionForm = $this->createForm(MissionFormType::class, $missionService->getMission());

        return $this->render(self::TEMPLATE_ADMIN_MISSION, [
            'missionForm' => $missionForm->createView(),
            'mission' => $missionService->getMission()
        ]);
    }

    /**
     * The control for the admin mission create.
     * @param int $mission
     * @param Request $request
     * @param MissionService $missionService
     * @return RedirectResponse
     * @Route("/missions/create/{mission?0}", methods="POST", name="admin_mission_create", requirements={"mission"="\d+"})
     */
    public function mission_create(int $mission, Request $request, MissionService $missionService)
    {
        $missionEntity = $missionService->get($mission);

        if ($missionEntity) {
            $missionService->setMission($missionEntity);
        }
        $missionForm = $this->createForm(MissionFormType::class, $missionService->getMission());

        $missionForm->handleRequest($request);
        if ($missionForm->isSubmitted() && $missionForm->isValid()) {
            $missionService->update();
        }
        return $this->redirectToRoute(self::ROUTE_ADMIN_MISSIONS);
    }

    /**
     * The control for the admin mission delete.
     * @param int $mission
     * @param MissionService $missionService
     * @return Response
     * @Route("/mission/delete/{mission?0}", methods="GET", name="admin_mission_delete", requirements={"mission"="\d+"})
     */
    public function mission_delete(int $mission, MissionService $missionService)
    {
        $missionService->setMission($missionService->get($mission));
        $missionService->delete();
        return $this->redirectToRoute(self::ROUTE_ADMIN_MISSIONS);
    }

    /**
     * The control for the admin projects.
     * @param int $page
     * @param ProjectService $projectService
     * @return Response
     * @Route("/projects/{page?0}", methods="GET", name="admin_projects", requirements={"page"="\d+"})
     */
    public function projects(int $page, ProjectService $projectService)
    {
        $projectForm = $this->createForm(ProjectFormType::class, $projectService->getProject());

        return $this->render(self::TEMPLATE_ADMIN_PROJECTS, [
            'projectPaginator' => $projectService->getPage($page),
            'projectForm' => $projectForm->createView()
        ]);
    }

    /**
     * The control for the admin project create.
     * @param int $project
     * @param Request $request
     * @param ProjectService $projectService
     * @return RedirectResponse
     * @Route("/projects/create/{project?0}", methods="POST", name="admin_project_create", requirements={"project"="\d+"})
     */
    public function project_create(int $project, Request $request, ProjectService $projectService)
    {
        $projectEntity = $projectService->get($project);

        if ($projectEntity) {
            $projectService->setProject($projectEntity);
        }
        $projectForm = $this->createForm(ProjectFormType::class, $projectService->getProject());

        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $projectService->create($projectForm);
        }
        return $this->redirectToRoute(self::ROUTE_ADMIN_PROJECTS);
    }

    /**
     * The control for the admin project delete.
     * @param int $project
     * @param ProjectService $projectService
     * @return RedirectResponse
     * @Route("/project_delete/{project?0}", methods="GET", name="admin_project_delete", requirements={"project"="\d+"})
     */
    public function project_delete(int $project, ProjectService $projectService)
    {
        $projectService->setProject($projectService->get($project));
        $projectService->delete();
        return $this->redirectToRoute(self::ROUTE_ADMIN_PROJECTS);
    }
}
