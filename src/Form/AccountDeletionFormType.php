<?php
/**
 * AccountDeletionFormType.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 09, 2020 at 16:31:45
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Class AccountDeletionFormType
 * @package App\Form
 */
class AccountDeletionFormType extends AbstractType
{
    /**
     * @var string
     */
    public const PASSWORD_FIELD = 'password';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::PASSWORD_FIELD, PasswordType::class, [
                'label' => 'security.profile.password',
                'translation_domain' => 'messages',
                'mapped' => false,
                'constraints' => [
                    new UserPassword()
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
