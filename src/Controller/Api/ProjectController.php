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
     * @Route("/", methods="GET", name="project_index")
     */
    public function index(): Response
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $projectList = $projectRepository->findAll();
        $response = $this->json($projectList);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @param int $id
     * @return Response
     * @Route("/{id}", methods="GET", name="project_show")
     */
    public function show(int $id): Response
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->find($id);
        $response = $this->json($project);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
