<?php
/**
 * PasswordFormType.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 09, 2020 at 16:32:24
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PasswordFormType
 * @package App\Form
 */
class PasswordFormType extends AbstractType
{
    /**
     * The password old password field.
     * @var string
     */
    public const OLD_PASSWORD_FIELD = 'oldPassword';

    /**
     * The password password field.
     * @var string
     */
    public const PASSWORD_FIELD = 'plainPassword';

    /**
     * Builds the password form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Add Translation.
        $builder
            ->add(self::OLD_PASSWORD_FIELD, PasswordType::class, [
                'label' => 'profile.edit.old_password',
                'translation_domain' => 'messages',
                'mapped' => false,
                'constraints' => [
                    new UserPassword()
                ]
            ])
            ->add(self::PASSWORD_FIELD, RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'profile.edit.new_password',
                    'translation_domain' => 'messages'
                ],
                'second_options' => [
                    'label' => 'profile.edit.confirm_password',
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
     * Sets the password form's default attributes.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
