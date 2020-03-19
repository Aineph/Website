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
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
    public const EMAIL_FIELD = 'email';

    /**
     * @var string
     */
    public const PASSWORD_FIELD = 'plainPassword';

    /**
     * @var string
     */
    const FIRST_NAME_FIELD = 'firstName';

    /**
     * @var string
     */
    const LAST_NAME_FIELD = 'lastName';

    /**
     * @var string
     */
    const COMPANY_FIELD = 'company';

    /**
     * @var string
     */
    const ADDRESS_FIELD = 'address';

    /**
     * @var string
     */
    const CITY_FIELD = 'city';

    /**
     * @var string
     */
    const ZIP_FIELD = 'zip';

    /**
     * @var string
     */
    const COUNTRY_FIELD = 'country';

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
            ->add(self::FIRST_NAME_FIELD, TextType::class, [
                'label' => 'security.register.first_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::LAST_NAME_FIELD, TextType::class, [
                'label' => 'security.register.last_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::EMAIL_FIELD, EmailType::class, [
                'label' => 'security.register.email',
                'translation_domain' => 'messages'
            ])
            ->add(self::PASSWORD_FIELD, RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'security.register.password',
                    'translation_domain' => 'messages'
                ],
                'second_options' => [
                    'label' => 'security.register.confirm_password',
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
            ])
            ->add(self::COMPANY_FIELD, TextType::class, [
                'label' => 'security.register.company',
                'translation_domain' => 'messages'
            ])
            ->add(self::ADDRESS_FIELD, TextType::class, [
                'label' => 'security.register.address',
                'translation_domain' => 'messages'
            ])
            ->add(self::CITY_FIELD, TextType::class, [
                'label' => 'security.register.city',
                'translation_domain' => 'messages'
            ])
            ->add(self::ZIP_FIELD, NumberType::class, [
                'label' => 'security.register.zip',
                'translation_domain' => 'messages'
            ])
            ->add(self::COUNTRY_FIELD, CountryType::class, [
                'label' => 'security.register.country',
                'translation_domain' => 'messages'
            ])
            ->add(self::PHONE_NUMBER_FIELD, TelType::class, [
                'label' => 'security.register.phone',
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
