<?php
/**
 * AccountManager.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 01, 2020 at 12:29:28
 */

namespace App\Service;

use App\Entity\User;
use App\Exception\AccountValidationException;
use App\Form\PasswordFormType;
use App\Form\ProfileFormType;
use App\Form\RegistrationFormType;
use App\Pagination\Paginator;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AccountManager
 * @package App\Service
 */
class AccountManager
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $accountEmail;

    /**
     * @var string
     */
    private $securityEmail;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var array
     */
    const DEFAULT_ROLES = ['ROLE_ADMIN'];

    /**
     * @var string
     */
    const TEMPLATE_EMAIL_ACTIVATION = 'email/activation.html.twig';

    /**
     * @var string
     */
    const TEMPLATE_EMAIL_SECURITY = 'email/security.html.twig';

    /**
     * AccountManager constructor.
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     * @param MailerInterface $mailer
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param TokenStorageInterface $tokenStorage
     * @param string $accountEmail
     * @param string $securityEmail
     */
    public function __construct(SessionInterface $session, TranslatorInterface $translator, MailerInterface $mailer,
                                UserPasswordEncoderInterface $userPasswordEncoder, TokenStorageInterface $tokenStorage,
                                string $accountEmail, string $securityEmail)
    {
        $this->setSession($session);
        $this->setTranslator($translator);
        $this->setMailer($mailer);
        $this->setUserPasswordEncoder($userPasswordEncoder);
        $this->setTokenStorage($tokenStorage);
        $this->setAccountEmail($accountEmail);
        $this->setSecurityEmail($securityEmail);
    }

    /**
     * @param FormInterface $registerForm
     * @param array $roles
     */
    public function register(FormInterface $registerForm, array $roles = self::DEFAULT_ROLES)
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
    public function sendActivationEmail($activationUrl)
    {
        $activationMail = (new TemplatedEmail())
            ->from($this->getAccountEmail())
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
     * @param string $activationKey
     */
    public function activate(?string $activationKey)
    {
        if ($this->getUser() == null) {
            throw new AccountValidationException('account.not_found');
        }
        if ($this->getUser()->getIsActivated()) {
            throw new AccountValidationException('account.already_activated');
        }
        if (KeyManager::verify($this->getUser()->getActivationKey(), $activationKey)) {
            $this->getUser()->setIsActivated(true);
            $this->getUser()->setActivationKey(null);
            $this->getEntityManager()->persist($this->getUser());
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
        if ($this->getUser()->getUsername() !== $oldEmailAddress) {
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
            ->from($this->getSecurityEmail())
            ->to($this->getUser()->getEmail())
            ->subject('Security Alert')
            ->htmlTemplate(self::TEMPLATE_EMAIL_SECURITY);
        $this->getMailer()->send($securityMail);
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function getUserPage(int $page)
    {
        return $this->getUserRepository()->findLatest($page);
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param object $user
     */
    public function setUser(object $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getAccountEmail(): string
    {
        return $this->accountEmail;
    }

    /**
     * @param string $accountEmail
     */
    public function setAccountEmail(string $accountEmail): void
    {
        $this->accountEmail = $accountEmail;
    }

    /**
     * @return string
     */
    public function getSecurityEmail(): string
    {
        return $this->securityEmail;
    }

    /**
     * @param string $securityEmail
     */
    public function setSecurityEmail(string $securityEmail): void
    {
        $this->securityEmail = $securityEmail;
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
     * @return UserRepository
     */
    public function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }

    /**
     * @param object $userRepository
     */
    public function setUserRepository(object $userRepository): void
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return ObjectManager
     */
    public function getEntityManager(): ObjectManager
    {
        return $this->entityManager;
    }

    /**
     * @param ObjectManager $entityManager
     */
    public function setEntityManager(ObjectManager $entityManager): void
    {
        $this->entityManager = $entityManager;
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
}
