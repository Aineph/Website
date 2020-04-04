<?php
/**
 * PasswordForgottenType.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 15, 2020 at 11:52:00
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PasswordForgottenFormType
 * @package App\Form
 */
class PasswordForgottenFormType extends AbstractType
{
    /**
     * The password forgotten e-mail field.
     * @var string
     */
    const EMAIL_FIELD = 'email';

    /**
     * Builds the password forgotten form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::EMAIL_FIELD, EmailType::class, [
                'label' => 'security.login.email',
                'translation_domain' => 'messages'
            ]);
    }

    /**
     * Sets the password forgotten form's default attributes.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
