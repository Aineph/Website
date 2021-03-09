<?php

namespace App\Controller\Api;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectController
 * @package App\Controller
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @return Response
     * @Route("/", name="project_index")
     */
    public function index(): Response
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $projectList = $projectRepository->findAll();
        $response = $this->json($projectList);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers',
            'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
        $response->headers->set('Allow', 'GET, POST, OPTIONS, PUT, DELETE');
        return $response;
    }
}
