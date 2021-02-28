<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MessageType
 * @package App\Form
 */
class MessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sender',
                TextType::class,
                [
                    'label' => 'message.sender'
                ])
            ->add('sender_email',
                EmailType::class,
                [
                    'label' => 'message.sender_email'
                ])
            ->add('object',
                TextType::class,
                [
                    'label' => 'message.object'
                ])
            ->add('content',
                TextareaType::class,
                [
                    'label' => 'message.content'
                ])
            ->add('honeypot',
                TextType:: class,
                [
                    'required' => false
                ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
