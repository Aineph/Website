<?php
/**
 * PasswordResetForm.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 22, 2020 at 15:30:38
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PasswordResetFormType
 * @package App\Form
 */
class PasswordResetFormType extends AbstractType
{
    /**
     * The password reset password field.
     * @var string
     */
    const PASSWORD_FIELD = 'plainPassword';

    /**
     * Builds the password reset form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::PASSWORD_FIELD, RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'security.reset.password',
                    'translation_domain' => 'messages'
                ],
                'second_options' => [
                    'label' => 'security.reset.confirm_password',
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
     * Sets the password reset form's default attributes.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
