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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/")
 */
class SecurityController extends AbstractController
{
    /**
     * @var string
     */
    const ROUTE_APP_LOGIN = 'security_login';

    /**
     * @var string
     */
    const TEMPLATE_APP_LOGIN = 'security/login.html.twig';

    /**
     * @var string
     */
    const ROUTE_APP_REGISTER = 'security_register';

    /**
     * @var string
     */
    const TEMPLATE_APP_REGISTER = 'security/register.html.twig';

    /**
     * @var string
     */
    const ROUTE_APP_ACTIVATE = 'security_activate';

    /**
     * @var string
     */
    const ACTIVATE_KEY_PARAMETER = 'activation_key';

    /**
     * @var string
     */
    const ROUTE_APP_PROFILE = 'security_profile';

    /**
     * @var string
     */
    const TEMPLATE_APP_PROFILE = 'security/profile.html.twig';

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(WebsiteController::ROUTE_WEBSITE_INDEX);
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(self::TEMPLATE_APP_LOGIN, ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="security_register")
     * @param Request $request
     * @param AccountManager $accountManager
     * @return Response
     */
    public function register(Request $request, AccountManager $accountManager): Response
    {
        $accountManager->setUser(new User());
        $registrationForm = $this->createForm(RegistrationFormType::class, $accountManager->getUser());

        $registrationForm->handleRequest($request);
        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $accountManager->setEntityManager($this->getDoctrine()->getManager());
            $accountManager->register($registrationForm);
            try {
                $activationUrl = $this->generateUrl(self::ROUTE_APP_ACTIVATE, [
                    'id' => $accountManager->getUser()->getId(),
                    self::ACTIVATE_KEY_PARAMETER => $accountManager->getUser()->getActivationKey()
                ], UrlGeneratorInterface::ABSOLUTE_URL);
                $accountManager->sendActivationEmail($activationUrl);
            } catch (TransportExceptionInterface $transportException) {
                return $this->redirectToRoute(self::ROUTE_APP_REGISTER, [
                    'error' => $transportException->getMessage()
                ]);
            }
            return $this->redirectToRoute(self::ROUTE_APP_LOGIN);
        }
        return $this->render(self::TEMPLATE_APP_REGISTER, [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    /**
     * @param string $id
     * @param Request $request
     * @param AccountManager $accountManager
     * @return Response
     * @Route("/activate/{id}", name="security_activate")
     */
    public function activate(string $id, Request $request, AccountManager $accountManager)
    {
        $accountManager->setUser($this->getDoctrine()->getRepository(User::class)->find(intval($id)));

        $accountManager->setEntityManager($this->getDoctrine()->getManager());
        try {
            $accountManager->activate($request->get(self::ACTIVATE_KEY_PARAMETER));
        } catch (AccountValidationException $accountValidationException) {
            // TODO: Handle error message.
            return $this->redirectToRoute(self::ROUTE_APP_LOGIN);
        }
        return $this->redirectToRoute(self::ROUTE_APP_LOGIN);
    }

    /**
     * @param Request $request
     * @param AccountManager $accountManager
     * @return Response
     * @Route("/profile", name="security_profile")
     */
    public function profile(Request $request, AccountManager $accountManager): Response
    {
        $accountManager->setUser($this->getUser());
        $currentEmailAddress = $accountManager->getUser()->getUsername();
        $updateProfileForm = $this->createForm(ProfileFormType::class, $accountManager->getUser());
        $updatePasswordForm = $this->createForm(PasswordFormType::class, $accountManager->getUser());
        $deleteAccountForm = $this->createForm(AccountDeletionFormType::class, $accountManager->getUser());

        $updateProfileForm->handleRequest($request);
        $updatePasswordForm->handleRequest($request);
        $deleteAccountForm->handleRequest($request);
        $accountManager->setEntityManager($this->getDoctrine()->getManager());
        if ($updateProfileForm->isSubmitted() && $updateProfileForm->isValid()) {
            $accountManager->updateProfile($currentEmailAddress);
            return $this->redirectToRoute(WebsiteController::ROUTE_WEBSITE_INDEX);
        } elseif ($updatePasswordForm->isSubmitted() && $updatePasswordForm->isValid()) {
            try {
                $accountManager->updatePassword($updatePasswordForm);
            } catch (TransportExceptionInterface $transportException) {
                return $this->redirectToRoute(self::ROUTE_APP_PROFILE, [
                    'error' => $transportException->getMessage()
                ]);
            }
        }
        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {
            $accountManager->delete();
            return $this->redirectToRoute(WebsiteController::ROUTE_WEBSITE_INDEX);
        }
        return $this->render(self::TEMPLATE_APP_PROFILE, [
            'profileForm' => $updateProfileForm->createView(),
            'passwordForm' => $updatePasswordForm->createView(),
            'accountDeletionForm' => $deleteAccountForm->createView()
        ]);
    }

    /**
     * @throws Exception
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        throw new Exception(
            'This method can be blank - it will be intercepted by the logout key on your firewall'
        );
    }
}
