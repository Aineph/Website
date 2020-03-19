<?php
/**
 * MissionService.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 15, 2020 at 17:48:05
 */

namespace App\Service;

use App\Entity\Mission;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

class MissionService extends AbstractService implements ServiceInterface
{
    /**
     * @var Mission
     */
    private $mission;

    /**
     * MissionService constructor.
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $entityManager);
        $this->setObjectRepository($this->getEntityManager()->getRepository(Mission::class));
        $this->setMission(new Mission());
    }

    /**
     *
     */
    public function create()
    {
        $this->getMission()->setDone(false);
        try {
            $this->getMission()->setDate(new DateTime('now'));
        } catch (Exception $e) {
            $this->getMission()->setDate(null);
        }
        $this->getEntityManager()->persist($this->getMission());
        $this->getEntityManager()->flush();
    }

    /**
     * @return array
     */
    public function getLatestMissions()
    {
        $missions = [];

        if ($this->getUser()) {
            $missions = $this->getObjectRepository()->findLatestFor($this->getUser()->getId());
        }
        return $missions;
    }

    /**
     * @return Mission
     */
    public function getMission(): Mission
    {
        return $this->mission;
    }

    /**
     * @param Mission $mission
     */
    public function setMission(Mission $mission): void
    {
        $this->mission = $mission;
    }
}
