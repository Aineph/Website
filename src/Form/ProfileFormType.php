<?php
/**
 * ProfileFormType.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 09, 2020 at 16:33:17
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProfileFormType
 * @package App\Form
 */
class ProfileFormType extends AbstractType
{
    /**
     * @var string
     */
    public const EMAIL_FIELD = 'email';

    /**
     * @var string
     */
    public const FIRST_NAME_FIELD = 'firstName';

    /**
     * @var string
     */
    public const LAST_NAME_FIELD = 'lastName';

    /**
     * @var string
     */
    public const COUNTRY_FIELD = 'country';

    /**
     * @var string
     */
    public const PHONE_NUMBER_FIELD = 'phoneNumber';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::EMAIL_FIELD, EmailType::class, [
                'label' => 'security.profile.email',
                'translation_domain' => 'messages'
            ])
            ->add(self::FIRST_NAME_FIELD, TextType::class, [
                'label' => 'security.profile.first_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::LAST_NAME_FIELD, TextType::class, [
                'label' => 'security.profile.last_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::COUNTRY_FIELD, ChoiceType::class, [
                'choices' => [
                    'France' => 'France',
                    'Germany' => 'Germany',
                    'United Kingdom' => 'United Kingdom',
                    'United States' => 'United States'
                ],
                'label' => 'security.profile.country',
                'translation_domain' => 'messages'
            ])
            ->add(self::PHONE_NUMBER_FIELD, TelType::class, [
                'label' => 'security.profile.phone',
                'translation_domain' => 'messages'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
