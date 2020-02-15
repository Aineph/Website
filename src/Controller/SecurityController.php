<?php
/**
 * SecurityController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 01, 2020 at 12:23:20
 */

namespace App\Controller;

use App\Entity\User;
use App\Exception\AccountValidationException;
use App\Form\AccountDeletionFormType;
use App\Form\PasswordFormType;
use App\Form\ProfileFormType;
use App\Form\RegistrationFormType;
use App\Service\AccountManager;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/")
 */
class SecurityController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('website_index');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param MailerInterface $mailer
     * @param TranslatorInterface $translator
     * @param AccountManager $accountManager
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder,
                             MailerInterface $mailer, TranslatorInterface $translator,
                             AccountManager $accountManager): Response
    {
        $accountManager->setUser(new User());
        $registrationForm = $this->createForm(RegistrationFormType::class, $accountManager->getUser());

        $registrationForm->handleRequest($request);
        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $accountManager->setUserPasswordEncoder($userPasswordEncoder);
            $accountManager->setEntityManager($this->getDoctrine()->getManager());
            $accountManager->setMailer($mailer);
            try {
                $accountManager->register($registrationForm, $translator);
            } catch (TransportExceptionInterface $transportException) {
                return $this->redirectToRoute('app_register', [
                    'error' => $transportException->getMessage()
                ]);
            }
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    /**
     * @param string $id
     * @param Request $request
     * @param AccountManager $accountManager
     * @return Response
     * @Route("/activate/{id}", name="app_activate")
     */
    public function activate(string $id, Request $request, AuthenticationUtils $authenticationUtils, AccountManager $accountManager)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find(intval($id));
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        $accountManager->setEntityManager($this->getDoctrine()->getManager());
        try {
            // TODO: Replace 'key' by constant.
            $accountManager->activate($user, $request->get('key'));
        } catch (AccountValidationException $accountValidationException) {
            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $accountValidationException
            ]);
        }
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerInterface $mailer
     * @param SessionInterface $session
     * @param AccountManager $accountManager
     * @return Response
     * @throws TransportExceptionInterface
     * @Route("/profile", name="app_profile")
     */
    public function profile(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                            MailerInterface $mailer, SessionInterface $session,
                            AccountManager $accountManager): Response
    {
        $user = $this->getUser();
        $profileForm = $this->createForm(ProfileFormType::class, $user);
        $passwordForm = $this->createForm(PasswordFormType::class, $user);
        $accountDeletionForm = $this->createForm(AccountDeletionFormType::class, $user);

        $profileForm->handleRequest($request);
        $passwordForm->handleRequest($request);
        $accountDeletionForm->handleRequest($request);
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $passwordForm->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $email = (new TemplatedEmail())
                ->from('dev@feznicolas.com')
                ->to($user->getEmail())
                ->subject('Security alert')
                ->htmlTemplate('email/security.html.twig');
            $mailer->send($email);
        }
        if ($accountDeletionForm->isSubmitted() && $accountDeletionForm->isValid()) {
            $this->get('security.token_storage')->setToken(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $session->invalidate();

            /*
             * TODO: Correctly delete user and flush
             */

            /*
             * TODO: Send a goodbye e-mail
             */

            return $this->redirectToRoute('website_index');
        }
        return $this->render('security/profile.html.twig', [
            'profileForm' => $profileForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'accountDeletionForm' => $accountDeletionForm->createView()
        ]);
    }

    /**
     * @throws Exception
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
