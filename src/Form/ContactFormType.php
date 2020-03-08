<?php
/**
 * ContactFormType.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 23, 2020 at 13:17:09
 */

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

/**
 * Class ContactFormType
 * @package App\Form
 */
class ContactFormType extends AbstractType
{
    /**
     * @var string
     */
    public const NAME_FIELD = "name";

    /**
     * @var string
     */
    public const EMAIL_FIELD = "email";

    /**
     * @var string
     */
    public const OBJECT_FIELD = "object";

    /**
     * @var string
     */
    public const CONTENT_FIELD = "content";

    /**
     * @var bool
     */
    private $isAuthenticated;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * ContactFormType constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $user = $security->getUser();

        $this->setIsAuthenticated(false);
        $this->setName('');
        $this->setEmail('');
        if ($user) {
            $this->setIsAuthenticated(true);
            $this->setName($user->getFirstName() . ' ' . $user->getLastName());
            $this->setEmail($user->getEmail());
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::NAME_FIELD, TextType::class, [
                'label' => 'website.contact.name',
                'translation_domain' => 'messages',
                'disabled' => $this->isAuthenticated(),
                'data' => $this->getName(),
                'empty_data' => $this->getName()
            ])
            ->add(self::EMAIL_FIELD, EmailType::class, [
                'label' => 'website.contact.email',
                'translation_domain' => 'messages',
                'disabled' => $this->isAuthenticated(),
                'data' => $this->getEmail(),
                'empty_data' => $this->getEmail()
            ])
            ->add(self::OBJECT_FIELD, TextType::class, [
                'label' => 'website.contact.object',
                'translation_domain' => 'messages'
            ])
            ->add(self::CONTENT_FIELD, TextareaType::class, [
                'label' => 'website.contact.content',
                'translation_domain' => 'messages'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    /**
     * @param bool $isAuthenticated
     */
    public function setIsAuthenticated(bool $isAuthenticated): void
    {
        $this->isAuthenticated = $isAuthenticated;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
