<?php
/**
 * ProjectService.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 18, 2020 at 21:56:27
 */

namespace App\Service;

use App\Entity\Project;
use App\Form\ProjectFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Security;

/**
 * Class ProjectService
 * @package App\Service
 */
class ProjectService extends AbstractService implements ServiceInterface
{
    /**
     * The current project.
     * @var Project
     */
    private $project;

    /**
     * ProjectService constructor.
     * @param string $uploadDirectory
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $uploadDirectory, Security $security, EntityManagerInterface $entityManager)
    {
        parent::__construct($uploadDirectory, $security, $entityManager);
        $this->setObjectRepository($this->getEntityManager()->getRepository(Project::class));
        $this->setProject(new Project());
    }

    /**
     * Creates the current project.
     * @param FormInterface $projectForm
     */
    public function create(FormInterface $projectForm): void
    {
        $file = $projectForm->get(ProjectFormType::FILE_FIELD)->getData();
        $image = $projectForm->get(ProjectFormType::IMAGE_FIELD)->getData();

        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                $originalFilename
            );
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
            $file->move($this->getUploadDirectory(), $newFilename);
            $this->getProject()->setFile($newFilename);
        }
        if ($image) {
            $originalImageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeImageName = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                $originalImageName
            );
            $newImageName = $safeImageName . '-' . uniqid() . '.' . $image->guessExtension();
            $image->move($this->getUploadDirectory(), $newImageName);
            $this->getProject()->setImage($newImageName);
        }
        $this->getEntityManager()->persist($this->getProject());
        $this->getEntityManager()->flush();
    }

    /**
     * Deletes the current project.
     */
    public function delete(): void
    {
        if ($this->getProject()) {
            $fileSystem = new Filesystem();
            $filePath = $this->getUploadDirectory() . '/' . $this->getProject()->getFile();
            $imagePath = $this->getUploadDirectory() . '/' . $this->getProject()->getImage();

            if ($this->getProject()->getFile() && $fileSystem->exists($filePath)) {
                $fileSystem->remove($this->getUploadDirectory() . '/' . $this->getProject()->getFile());
            }
            if ($this->getProject()->getImage() && $fileSystem->exists($imagePath)) {
                $fileSystem->remove($this->getUploadDirectory() . '/' . $this->getProject()->getImage());
            }
            $this->getEntityManager()->remove($this->getProject());
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Gets the current project.
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * Sets the current project.
     * @param Project|null $project
     */
    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }
}
