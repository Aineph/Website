<?php
/**
 * ProjectService.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 18, 2020 at 21:56:27
 */

namespace App\Service;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ProjectService extends AbstractService implements ServiceInterface
{
    /**
     * @var Project
     */
    private $project;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $entityManager);
        $this->setObjectRepository($this->getEntityManager()->getRepository(Project::class));
        $this->setProject(new Project());
    }

    /**
     *
     */
    public function create()
    {
        $this->getEntityManager()->persist($this->getProject());
        $this->getEntityManager()->flush();
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project): void
    {
        $this->project = $project;
    }
}
