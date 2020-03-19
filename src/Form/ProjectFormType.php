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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProjectFormType
 * @package App\Form
 */
class ProjectFormType extends AbstractType
{
    /**
     * @var string
     */
    const NAME_FIELD = 'name';

    /**
     * @var string
     */
    const DESCRIPTION_FIELD = 'description';

    /**
     * @var string
     */
    const LINK_FIELD = 'link';

    /**
     * @var string
     */
    const TYPE_FIELD = 'type';

    /**
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
            ->add(self::LINK_FIELD, UrlType::class, [
                'label' => 'admin.projects.link',
                'translation_domain' => 'messages'
            ])
            ->add(self::TYPE_FIELD, ChoiceType::class, [
                'choices' => [
                    'admin.projects.music' => 0,
                    'admin.projects.programming' => 1
                ],
                'label' => 'admin.projects.type',
                'translation_domain' => 'messages'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
