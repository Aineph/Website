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
     * The profile first name field.
     * @var string
     */
    const FIRST_NAME_FIELD = 'firstName';

    /**
     * The profile last name field.
     * @var string
     */
    const LAST_NAME_FIELD = 'lastName';

    /**
     * The profile company field.
     * @var string
     */
    const COMPANY_FIELD = 'company';

    /**
     * The profile address field.
     * @var string
     */
    const ADDRESS_FIELD = 'address';

    /**
     * The profile city field.
     * @var string
     */
    const CITY_FIELD = 'city';

    /**
     * The profile country field.
     * @var string
     */
    const COUNTRY_FIELD = 'country';

    /**
     * The profile zip field.
     * @var string
     */
    const ZIP_FIELD = 'zip';

    /**
     * The profile phone field.
     * @var string
     */
    const PHONE_NUMBER_FIELD = 'phoneNumber';

    /**
     * Builds the profile form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIRST_NAME_FIELD, TextType::class, [
                'label' => 'profile.edit.first_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::LAST_NAME_FIELD, TextType::class, [
                'label' => 'profile.edit.last_name',
                'translation_domain' => 'messages'
            ])
            ->add(self::COMPANY_FIELD, TextType::class, [
                'label' => 'profile.edit.company',
                'translation_domain' => 'messages'
            ])
            ->add(self::ADDRESS_FIELD, TextType::class, [
                'label' => 'profile.edit.address',
                'translation_domain' => 'messages'
            ])
            ->add(self::CITY_FIELD, TextType::class, [
                'label' => 'profile.edit.city',
                'translation_domain' => 'messages'
            ])
            ->add(self::ZIP_FIELD, NumberType::class, [
                'label' => 'profile.edit.zip',
                'translation_domain' => 'messages'
            ])
            ->add(self::COUNTRY_FIELD, CountryType::class, [
                'label' => 'profile.edit.country',
                'translation_domain' => 'messages'
            ])
            ->add(self::PHONE_NUMBER_FIELD, TelType::class, [
                'label' => 'profile.edit.phone',
                'translation_domain' => 'messages'
            ]);
    }

    /**
     * Sets the profile form's default attributes.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
