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
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AccountManager
 * @package App\Service
 */
class AccountManager
{
    /**
     * @var array
     */
    const DEFAULT_ROLES = ['ROLE_ADMIN'];

    /**
     * @var User
     */
    private $user;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @param FormInterface $registerForm
     * @param TranslatorInterface $translator
     * @param array $roles
     * @throws TransportExceptionInterface
     */
    public function register(FormInterface $registerForm, TranslatorInterface $translator, array $roles = self::DEFAULT_ROLES)
    {
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
        // TODO: Remove hard coded addresses.
        $activationMail = (new TemplatedEmail())
            ->from('registration@feznicolas.com')
            ->to($this->getUser()->getEmail())
            ->subject($translator->trans('account.activation'))
            ->htmlTemplate('email/activation.html.twig')
            ->context([
                'firstName' => $this->getUser()->getFirstName(),
                'userID' => $this->getUser()->getId(),
                'activationKey' => $this->getUser()->getActivationKey()
            ]);
        $this->getMailer()->send($activationMail);
    }

    /**
     * @param object $user
     * @param string $activationKey
     */
    public function activate($user, string $activationKey)
    {
        if ($user == null) {
            throw new AccountValidationException('account.not_found');
        }
        if ($user->getIsActivated()) {
            throw new AccountValidationException('account.already_activated');
        }
        if (KeyManager::verify($user->getActivationKey(), $activationKey)) {
            $user->setIsActivated(true);
            // TODO: Set Activation Key to NULL.
//            $user->setActivationKey(null);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        } else {
            throw new AccountValidationException('account.invalid_key');
        }
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
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
