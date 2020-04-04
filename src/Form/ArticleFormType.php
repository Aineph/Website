<?php
/**
 * ArticleFormType.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 13, 2020 at 07:11:12
 */

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArticleFormType
 * @package App\Form
 */
class ArticleFormType extends AbstractType
{
    /**
     * The article title field.
     * @var string
     */
    public const TITLE_FIELD = 'title';

    /**
     * The article content field.
     * @var string
     */
    public const CONTENT_FIELD = 'content';

    /**
     * The article video field.
     * @var string
     */
    public const VIDEO_FIELD = 'video';

    /**
     * Builds the article form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::TITLE_FIELD, TextType::class, [
                'label' => 'admin.article.title',
                'translation_domain' => 'messages'
            ])
            ->add(self::CONTENT_FIELD, TextareaType::class, [
                'label' => 'admin.article.content',
                'translation_domain' => 'messages',
            ])->add(self::VIDEO_FIELD, UrlType::class, [
                'label' => 'admin.article.video',
                'translation_domain' => 'messages',
                'required' => false
            ]);
    }

    /**
     * Sets the article form's default attributes.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
