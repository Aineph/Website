<?php
/**
 * UserService.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 13, 2020 at 18:17:22
 */

namespace App\Service;

use App\Entity\User;
use App\Exception\AccountValidationException;
use App\Form\PasswordFormType;
use App\Form\ProfileFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserService
 * @package App\Service
 */
class UserService extends AbstractService implements ServiceInterface
{
    /**
     * The user password encoder.
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * The mailer.
     * @var MailerInterface
     */
    private $mailer;

    /**
     * The session.
     * @var SessionInterface
     */
    private $session;

    /**
     * The token storage.
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * The translator.
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * The default roles for a new user.
     * @var array
     */
    const DEFAULT_ROLES = ['ROLE_USER'];

    /**
     * The e-mail address for accounts.
     * @var string
     */
    const ACCOUNT_EMAIL = 'accounts@feznicolas.com';

    /**
     * The e-mail address for security.
     * @var string
     */
    const SECURITY_EMAIL = 'security@feznicolas.com';

    /**
     * The e-mail activation template.
     * @var string
     */
    const TEMPLATE_EMAIL_ACTIVATION = 'email/activation.html.twig';

    /**
     * The e-mail forgotten template.
     * @var string
     */
    const TEMPLATE_EMAIL_FORGOTTEN = 'email/forgotten.html.twig';

    /**
     * The e-mail security template.
     * @var string
     */
    const TEMPLATE_EMAIL_SECURITY = 'email/security.html.twig';

    /**
     * UserService constructor.
     * @param string $uploadDirectory
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param MailerInterface $mailer
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $uploadDirectory, UserPasswordEncoderInterface $userPasswordEncoder,
                                MailerInterface $mailer, SessionInterface $session,
                                TokenStorageInterface $tokenStorage, TranslatorInterface $translator,
                                Security $security, EntityManagerInterface $entityManager)
    {
        parent::__construct($uploadDirectory, $security, $entityManager);
        if (!$this->getUser()) {
            $this->setUser(new User());
        }
        $this->setObjectRepository($this->getEntityManager()->getRepository(User::class));
        $this->setSession($session);
        $this->setTranslator($translator);
        $this->setMailer($mailer);
        $this->setUserPasswordEncoder($userPasswordEncoder);
        $this->setTokenStorage($tokenStorage);
    }

    /**
     * Creates the current user.
     * @param FormInterface $registerForm
     * @param array $roles
     */
    public function create(FormInterface $registerForm, array $roles = self::DEFAULT_ROLES): void
    {
        $this->getUser()->setFirstName(ucfirst($registerForm->get(RegistrationFormType::FIRST_NAME_FIELD)->getData()));
        $this->getUser()->setLastName(strtoupper($registerForm->get(RegistrationFormType::LAST_NAME_FIELD)->getData()));
        $this->getUser()->setPassword(
            $this->getUserPasswordEncoder()->encodePassword(
                $this->getUser(),
                $registerForm->get(RegistrationFormType::PASSWORD_FIELD)->getData()
            )
        );
        $this->getUser()->setRoles($roles);
        $this->getUser()->setActivationKey(KeyManager::generate());
        $this->getUser()->setIsActivated(false);
        $this->getEntityManager()->persist($this->getUser());
        $this->getEntityManager()->flush();
    }

    /**
     * Sends an activation e-mail to a newly created account.
     * @param $activationUrl
     * @throws TransportExceptionInterface
     */
    public function prepare(string $activationUrl): void
    {
        $activationMail = (new TemplatedEmail())
            ->from(self::ACCOUNT_EMAIL)
            ->to($this->getUser()->getEmail())
            ->subject($this->getTranslator()->trans('email.activation.subject'))
            ->htmlTemplate(self::TEMPLATE_EMAIL_ACTIVATION)
            ->context([
                'firstName' => $this->getUser()->getFirstName(),
                'activationUrl' => $activationUrl,
            ]);
        $this->getMailer()->send($activationMail);
    }

    /**
     * Activates an account.
     * @param int|null $id
     * @param string $activationKey
     */
    public function activate(?int $id, ?string $activationKey): void
    {
        $user = $this->getEntityManager()->getRepository(User::class)->find($id);
        if (!$user) {
            throw new AccountValidationException('account.not_found');
        }
        if ($user->getIsActivated()) {
            throw new AccountValidationException('account.already_activated');
        }
        if (KeyManager::verify($user->getActivationKey(), $activationKey)) {
            $user->setIsActivated(true);
            $user->setActivationKey(null);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        } else {
            throw new AccountValidationException('account.invalid_key');
        }
    }

    /**
     * Sends an e-mail for a forgotten password account.
     * @param string $resetPasswordUrl
     * @throws TransportExceptionInterface
     */
    public function forgotten(string $resetPasswordUrl): void
    {
        $this->getEntityManager()->persist($this->getUser());
        $this->getEntityManager()->flush();
        $resetPasswordEmail = (new TemplatedEmail())
            ->from(self::ACCOUNT_EMAIL)
            ->to($this->getUser()->getEmail())
            ->subject($this->getTranslator()->trans('email.forgotten.subject'))
            ->htmlTemplate(self::TEMPLATE_EMAIL_FORGOTTEN)
            ->context([
                'resetPasswordUrl' => $resetPasswordUrl
            ]);

        $this->getMailer()->send($resetPasswordEmail);
    }

    /**
     * Updates the current user profile.
     * @param FormInterface $profileForm
     */
    public function updateProfile(FormInterface $profileForm): void
    {
        $this->getUser()->setFirstName(ucfirst($profileForm->get(ProfileFormType::FIRST_NAME_FIELD)->getData()));
        $this->getUser()->setLastName(strtoupper($profileForm->get(ProfileFormType::LAST_NAME_FIELD)->getData()));
        $this->getEntityManager()->persist($this->getUser());
        $this->getEntityManager()->flush();
    }

    /**
     * Updates the current user password.
     * @param FormInterface $updatePasswordForm
     * @throws TransportExceptionInterface
     */
    public function updatePassword(FormInterface $updatePasswordForm)
    {
        $this->getUser()->setActivationKey(null);
        $this->getUser()->setPassword(
            $this->getUserPasswordEncoder()->encodePassword(
                $this->getUser(),
                $updatePasswordForm->get(PasswordFormType::PASSWORD_FIELD)->getData()
            )
        );
        $this->getEntityManager()->persist($this->getUser());
        $this->getEntityManager()->flush();
        $securityMail = (new TemplatedEmail())
            ->from(self::SECURITY_EMAIL)
            ->to($this->getUser()->getEmail())
            ->subject($this->getTranslator()->trans('email.security.subject'))
            ->htmlTemplate(self::TEMPLATE_EMAIL_SECURITY);

        $this->getMailer()->send($securityMail);
    }

    /**
     * Deletes the current user.
     */
    public function delete(): void
    {
        $this->getTokenStorage()->setToken(null);
        $this->getEntityManager()->remove($this->getUser());
        $this->getEntityManager()->flush();
        $this->getSession()->invalidate();
    }

    /**
     * Gets the user password encoder.
     * @return UserPasswordEncoderInterface
     */
    public function getUserPasswordEncoder(): UserPasswordEncoderInterface
    {
        return $this->userPasswordEncoder;
    }

    /**
     * Sets the user password encoder.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function setUserPasswordEncoder(UserPasswordEncoderInterface $userPasswordEncoder): void
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * Gets the mailer.
     * @return MailerInterface
     */
    public function getMailer(): MailerInterface
    {
        return $this->mailer;
    }

    /**
     * Sets the mailer.
     * @param MailerInterface $mailer
     */
    public function setMailer(MailerInterface $mailer): void
    {
        $this->mailer = $mailer;
    }

    /**
     * Gets the session.
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * Sets the session.
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    /**
     * Gets the token storage.
     * @return TokenStorageInterface
     */
    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }

    /**
     * Sets the token storage.
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage): void
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Gets the translator.
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * Sets the translator.
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
