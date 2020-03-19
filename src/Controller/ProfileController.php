<?php
/**
 * ProfileController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 09, 2020 at 11:08:41
 */

namespace App\Controller;

use App\Service\MissionService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @package App\Controller
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @param UserService $userService
     * @param MissionService $missionService
     * @return Response
     * @Route("/", methods="GET", name="profile_index")
     */
    public function index(UserService $userService, MissionService $missionService)
    {
        return $this->render('profile/index.html.twig', [
            'user' => $userService->getUser(),
            'missions' => $missionService->getLatestMissions()
        ]);
    }

    /**
     * @return Response
     * @Route("/edit", methods="GET", name="profile_edit")
     */
    public function edit()
    {
        return $this->render('profile/edit.html.twig');
    }
}
