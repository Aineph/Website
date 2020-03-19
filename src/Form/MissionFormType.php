<?php
/**
 * MissionFormType.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 15, 2020 at 20:57:33
 */

namespace App\Form;

use App\Entity\Mission;
use App\Service\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MissionFormType
 * @package App\Form
 */
class MissionFormType extends AbstractType
{
    /**
     * @var string
     */
    const TITLE_FIELD = 'title';

    /**
     * @var string
     */
    const DESCRIPTION_FIELD = 'description';

    /**
     * @var string
     */
    const USER_FIELD = 'user';

    /**
     * @var string
     */
    const LINK_FIELD = 'link';

    /**
     * @var array
     */
    private $userChoices;

    public function __construct(UserService $userService)
    {
        $users = $userService->getAll();
        $userChoices = [];

        foreach ($users as $user) {
            $userChoices[$user->getFirstName() . ' ' . $user->getLastName()] = $user;
        }
        $this->setUserChoices($userChoices);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::TITLE_FIELD, TextType::class, [
                'label' => 'admin.missions.title',
                'translation_domain' => 'messages'
            ])
            ->add(self::DESCRIPTION_FIELD, TextType::class, [
                'label' => 'admin.missions.description',
                'translation_domain' => 'messages'
            ])
            ->add(self::USER_FIELD, ChoiceType::class, [
                'choices' => $this->getUserChoices(),
                'choice_translation_domain' => false,
                'label' => 'admin.missions.user',
                'translation_domain' => 'messages'
            ])
            ->add(self::LINK_FIELD, UrlType::class, [
                'label' => 'admin.missions.link',
                'translation_domain' => 'messages'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }

    /**
     * @return array|null
     */
    public function getUserChoices(): ?array
    {
        return $this->userChoices;
    }

    /**
     * @param array|null $userChoices
     */
    public function setUserChoices(?array $userChoices): void
    {
        $this->userChoices = $userChoices;
    }
}
