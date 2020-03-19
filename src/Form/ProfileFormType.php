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
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
    const FIRST_NAME_FIELD = 'firstName';

    /**
     * @var string
     */
    const LAST_NAME_FIELD = 'lastName';

    /**
     * @var string
     */
    const EMAIL_FIELD = 'email';

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
    const COUNTRY_FIELD = 'country';

    /**
     * @var string
     */
    const ZIP_FIELD = 'zip';

    /**
     * @var string
     */
    const PHONE_NUMBER_FIELD = 'phoneNumber';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIRST_NAME_FIELD, TextType::class, [
                'label' => 'security.profile.first_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::LAST_NAME_FIELD, TextType::class, [
                'label' => 'security.profile.last_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::EMAIL_FIELD, EmailType::class, [
                'label' => 'security.profile.email',
                'translation_domain' => 'messages'
            ])
            ->add(self::COMPANY_FIELD, TextType::class, [
                'label' => 'security.profile.company',
                'translation_domain' => 'messages'
            ])
            ->add(self::ADDRESS_FIELD, TextType::class, [
                'label' => 'security.profile.address',
                'translation_domain' => 'messages'
            ])
            ->add(self::CITY_FIELD, TextType::class, [
                'label' => 'security.profile.city',
                'translation_domain' => 'messages'
            ])
            ->add(self::ZIP_FIELD, NumberType::class, [
                'label' => 'security.profile.zip',
                'translation_domain' => 'messages'
            ])
            ->add(self::COUNTRY_FIELD, CountryType::class, [
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
