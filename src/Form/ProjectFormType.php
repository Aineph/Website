<?php
/**
 * ProjectFormType.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 18, 2020 at 22:00:55
 */

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class ProjectFormType
 * @package App\Form
 */
class ProjectFormType extends AbstractType
{
    /**
     * The project name field.
     * @var string
     */
    const NAME_FIELD = 'name';

    /**
     * The project description field.
     * @var string
     */
    const DESCRIPTION_FIELD = 'description';

    /**
     * The project link field.
     * @var string
     */
    const LINK_FIELD = 'link';

    /**
     * The project type field.
     * @var string
     */
    const TYPE_FIELD = 'type';

    /**
     * The project file field.
     * @var string
     */
    const FILE_FIELD = 'file';

    /**
     * The project image field.
     * @var string
     */
    const IMAGE_FIELD = 'image';

    /**
     * Builds the project form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::NAME_FIELD, TextType::class, [
                'label' => 'admin.projects.name',
                'translation_domain' => 'messages'
            ])
            ->add(self::DESCRIPTION_FIELD, TextareaType::class, [
                'label' => 'admin.projects.description',
                'translation_domain' => 'messages'
            ])
            ->add(self::TYPE_FIELD, ChoiceType::class, [
                'choices' => [
                    'admin.projects.music' => 0,
                    'admin.projects.programming' => 1
                ],
                'label' => 'admin.projects.type',
                'translation_domain' => 'messages'
            ])
            ->add(self::LINK_FIELD, UrlType::class, [
                'label' => 'admin.projects.link',
                'translation_domain' => 'messages'
            ])
            ->add(self::IMAGE_FIELD, FileType::class, [
                'label' => 'admin.projects.image',
                'translation_domain' => 'messages',
                'constraints' => new File([
                    'maxSize' => '10M'
                ])
            ])
            ->add(self::FILE_FIELD, FileType::class, [
                'label' => 'admin.projects.file',
                'translation_domain' => 'messages',
                'required' => false,
                'constraints' => new File([
                    'maxSize' => '10M'
                ])
            ]);
    }

    /**
     * Sets the project form's default attributes.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
