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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
     * The mission title field.
     * @var string
     */
    const TITLE_FIELD = 'title';

    /**
     * The mission description field.
     * @var string
     */
    const DESCRIPTION_FIELD = 'description';

    /**
     * The mission done field.
     * @var string
     */
    const DONE_FIELD = 'done';

    /**
     * The mission user field.
     * @var string
     */
    const USER_FIELD = 'user';

    /**
     * The mission link field.
     * @var string
     */
    const LINK_FIELD = 'link';

    /**
     * The mission user choices.
     * @var array
     */
    private $userChoices;

    /**
     * MissionFormType constructor.
     * @param UserService $userService
     */
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
     * Builds the mission form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::TITLE_FIELD, TextType::class, [
                'label' => 'admin.mission.title',
                'translation_domain' => 'messages'
            ])
            ->add(self::DESCRIPTION_FIELD, TextType::class, [
                'label' => 'admin.mission.description',
                'translation_domain' => 'messages'
            ])
            ->add(self::DONE_FIELD, CheckboxType::class, [
                'label' => 'admin.mission.done',
                'translation_domain' => 'messages',
                'required' => false
            ])
            ->add(self::USER_FIELD, ChoiceType::class, [
                'choices' => $this->getUserChoices(),
                'choice_translation_domain' => false,
                'label' => 'admin.mission.user',
                'translation_domain' => 'messages'
            ])
            ->add(self::LINK_FIELD, UrlType::class, [
                'label' => 'admin.mission.link',
                'translation_domain' => 'messages'
            ]);
    }

    /**
     * Sets the mission form's default attributes.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }

    /**
     * Gets the mission user choices.
     * @return array|null
     */
    public function getUserChoices(): ?array
    {
        return $this->userChoices;
    }

    /**
     * Sets the mission user choices.
     * @param array|null $userChoices
     */
    public function setUserChoices(?array $userChoices): void
    {
        $this->userChoices = $userChoices;
    }
}
