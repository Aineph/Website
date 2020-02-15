<?php
/**
 * RegistrationFormType.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 09, 2020 at 16:33:55
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RegistrationFormType
 * @package App\Form
 */
class RegistrationFormType extends AbstractType
{
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
     * @var string
     */
    public const EMAIL_FIELD = 'email';

    /**
     * @var string
     */
    public const PASSWORD_FIELD = 'plainPassword';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIRST_NAME_FIELD, TextType::class, [
                'label' => 'account.first_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::LAST_NAME_FIELD, TextType::class, [
                'label' => 'account.last_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::EMAIL_FIELD, EmailType::class, [
                'label' => 'account.email',
                'translation_domain' => 'messages'
            ])
            ->add(self::COUNTRY_FIELD, ChoiceType::class, [
                'choices' => [
                    'France' => 'France',
                    'Germany' => 'Germany',
                    'United Kingdom' => 'United Kingdom',
                    'United States' => 'United States'
                ],
                'label' => 'account.country',
                'translation_domain' => 'messages'
            ])
            ->add(self::PHONE_NUMBER_FIELD, TelType::class, [
                'label' => 'account.phone_number',
                'translation_domain' => 'messages'
            ])
            ->add(self::PASSWORD_FIELD, RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'account.password',
                    'translation_domain' => 'messages'
                ],
                'second_options' => [
                    'label' => 'account.password_confirmation',
                    'translation_domain' => 'messages'
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096
                    ])
                ]
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
