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
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    const DEFAULT_ROLES = ['ROLE_ADMIN'];

    /**
     * @var string
     */
    const ACCOUNT_EMAIL = 'accounts@feznicolas.com';

    /**
     * @var string
     */
    const SECURITY_EMAIL = 'security@feznicolas.com';

    /**
     * @var string
     */
    const TEMPLATE_EMAIL_ACTIVATION = 'email/activation.html.twig';

    /**
     * @var string
     */
    const TEMPLATE_EMAIL_SECURITY = 'email/security.html.twig';

    /**
     * UserService constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param MailerInterface $mailer
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, MailerInterface $mailer,
                                SessionInterface $session, TokenStorageInterface $tokenStorage,
                                TranslatorInterface $translator, Security $security,
                                EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $entityManager);
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
     * @param FormInterface $registerForm
     * @param array $roles
     */
    public function create(FormInterface $registerForm, array $roles = self::DEFAULT_ROLES)
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
     * @param $activationUrl
     * @throws TransportExceptionInterface
     */
    public function prepare(string $activationUrl)
    {
        $activationMail = (new TemplatedEmail())
            ->from(self::ACCOUNT_EMAIL)
            ->to($this->getUser()->getEmail())
            ->subject($this->getTranslator()->trans('account.activation'))
            ->htmlTemplate(self::TEMPLATE_EMAIL_ACTIVATION)
            ->context([
                'firstName' => $this->getUser()->getFirstName(),
                'activationUrl' => $activationUrl,
            ]);
        $this->getMailer()->send($activationMail);
    }

    /**
     * @param int|null $id
     * @param string $activationKey
     */
    public function activate(?int $id, ?string $activationKey)
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
     * @param FormInterface $profileForm
     * @param string $oldEmailAddress
     */
    public function updateProfile(FormInterface $profileForm, string $oldEmailAddress)
    {
        $this->getUser()->setFirstName(ucfirst($profileForm->get(ProfileFormType::FIRST_NAME_FIELD)->getData()));
        $this->getUser()->setLastName(strtoupper($profileForm->get(ProfileFormType::LAST_NAME_FIELD)->getData()));
        if ($this->getUser()->getEmail() !== $oldEmailAddress) {
            $this->getTokenStorage()->setToken(null);
            $this->getUser()->setActivationKey(KeyManager::generate());
            $this->getUser()->setIsActivated(false);
            $this->getSession()->invalidate();
        }
        $this->getEntityManager()->persist($this->getUser());
        $this->getEntityManager()->flush();
    }

    /**
     * @param FormInterface $updatePasswordForm
     * @throws TransportExceptionInterface
     */
    public function updatePassword(FormInterface $updatePasswordForm)
    {
        $this->getUser()->setPassword(
            $this->getUserPasswordEncoder()->encodePassword(
                $this->getUser(),
                $updatePasswordForm->get(PasswordFormType::PASSWORD_FIELD)->getData()
            )
        );
        $this->getEntityManager()->persist($this->getUser());
        $this->getEntityManager()->flush();
        // TODO: Add translation.
        $securityMail = (new TemplatedEmail())
            ->from(self::SECURITY_EMAIL)
            ->to($this->getUser()->getEmail())
            ->subject($this->getTranslator()->trans('account.security'))
            ->htmlTemplate(self::TEMPLATE_EMAIL_SECURITY);
        $this->getMailer()->send($securityMail);
    }

    /**
     *
     */
    public function delete()
    {
        $this->getTokenStorage()->setToken(null);
        $this->getEntityManager()->remove($this->getUser());
        $this->getEntityManager()->flush();
        $this->getSession()->invalidate();
        // TODO: Send a goodbye e-mail
    }

    /**
     * @return UserPasswordEncoderInterface
     */
    public function getUserPasswordEncoder(): UserPasswordEncoderInterface
    {
        return $this->userPasswordEncoder;
    }

    /**
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function setUserPasswordEncoder(UserPasswordEncoderInterface $userPasswordEncoder): void
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @return MailerInterface
     */
    public function getMailer(): MailerInterface
    {
        return $this->mailer;
    }

    /**
     * @param MailerInterface $mailer
     */
    public function setMailer(MailerInterface $mailer): void
    {
        $this->mailer = $mailer;
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    /**
     * @return TokenStorageInterface
     */
    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage): void
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
