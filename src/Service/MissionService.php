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

/**
 * Class MissionService
 * @package App\Service
 */
class MissionService extends AbstractService implements ServiceInterface
{
    /**
     * The current mission.
     * @var Mission
     */
    private $mission;

    /**
     * MissionService constructor.
     * @param string $uploadDirectory
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $uploadDirectory, Security $security, EntityManagerInterface $entityManager)
    {
        parent::__construct($uploadDirectory, $security, $entityManager);
        $this->setObjectRepository($this->getEntityManager()->getRepository(Mission::class));
        $this->setMission(new Mission());
    }

    /**
     * Updates the current mission.
     */
    public function update(): void
    {
        try {
            $this->getMission()->setDate(new DateTime('now'));
        } catch (Exception $e) {
            $this->getMission()->setDate(null);
        }
        $this->getEntityManager()->persist($this->getMission());
        $this->getEntityManager()->flush();
    }

    /**
     * Deletes the current mission.
     */
    public function delete(): void
    {
        if ($this->getMission()) {
            $this->getEntityManager()->remove($this->getMission());
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retrieve the latest missions.
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
     * Retrieves the number of missions.
     * @return mixed
     */
    public function getMissionsCount()
    {
        return $this->getObjectRepository()->countFor($this->getUser()->getId());
    }

    /**
     * Returns the availability depending on the current missions.
     * @return mixed
     */
    public function getAvailability()
    {
        return $this->getObjectRepository()->getAvailability();
    }

    /**
     * Gets the current mission.
     * @return Mission|null
     */
    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    /**
     * Sets the current mission.
     * @param Mission|null $mission
     */
    public function setMission(?Mission $mission): void
    {
        $this->mission = $mission;
    }
}
