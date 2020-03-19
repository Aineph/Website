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
use App\Form\PasswordForgottenType;
use App\Form\PasswordFormType;
use App\Form\ProfileFormType;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/security")
 */
class SecurityController extends AbstractController
{
    /**
     * @var string
     */
    const ROUTE_SECURITY_LOGIN = 'security_login';

    /**
     * @var string
     */
    const TEMPLATE_SECURITY_LOGIN = 'security/login.html.twig';

    /**
     * @var string
     */
    const ROUTE_SECURITY_REGISTER = 'security_register';

    /**
     * @var string
     */
    const TEMPLATE_SECURITY_REGISTER = 'security/register.html.twig';

    /**
     * @var string
     */
    const ROUTE_SECURITY_ACTIVATE = 'security_activate';

    /**
     * @var string
     */
    const ACTIVATE_ID_PARAMETER = 'id';

    /**
     * @var string
     */
    const ACTIVATE_KEY_PARAMETER = 'activation_key';

    /**
     * @var string
     */
    const ROUTE_SECURITY_PROFILE = 'security_profile';

    /**
     * @var string
     */
    const TEMPLATE_SECURITY_PROFILE = 'security/profile.html.twig';

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @param UserService $userService
     * @return Response
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, UserService $userService): Response
    {
        $passwordForgottenForm = $this->createForm(PasswordForgottenType::class);
        if ($this->getUser()) {
            return $this->redirectToRoute(WebsiteController::ROUTE_WEBSITE_INDEX);
        }
        if ($passwordForgottenForm->isSubmitted() && $passwordForgottenForm->isValid()) {
            // TODO: Send Password Reset Link.
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(self::TEMPLATE_SECURITY_LOGIN, [
            'last_username' => $lastUsername,
            'error' => $error,
            'passwordForgottenForm' => $passwordForgottenForm->createView()
        ]);
    }

    /**
     * @Route("/register", methods="GET", name="security_register")
     * @param UserService $userService
     * @return Response
     */
    public function register(UserService $userService): Response
    {
        $registrationForm = $this->createForm(RegistrationFormType::class, $userService->getUser());

        return $this->render(self::TEMPLATE_SECURITY_REGISTER, [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param UserService $userService
     * @return RedirectResponse
     * @Route("/registration", methods="POST", name="security_registration")
     */
    public function registration(Request $request, UserService $userService)
    {
        $registrationForm = $this->createForm(RegistrationFormType::class, $userService->getUser());

        $registrationForm->handleRequest($request);
        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $userService->create($registrationForm);
            try {
                $activationUrl = $this->generateUrl(self::ROUTE_SECURITY_ACTIVATE, [
                    self::ACTIVATE_ID_PARAMETER => $userService->getUser()->getId(),
                    self::ACTIVATE_KEY_PARAMETER => $userService->getUser()->getActivationKey()
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                $userService->prepare($activationUrl);
            } catch (TransportExceptionInterface $transportException) {
                return $this->redirectToRoute(self::ROUTE_SECURITY_REGISTER, [
                    'error' => $transportException->getMessage()
                ]);
            }
        }
        return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param UserService $userService
     * @return Response
     * @Route("/activate/{id}", methods="GET", name="security_activate", requirements={"id"="\d+"})
     */
    public function activate(int $id, Request $request, UserService $userService)
    {
        try {
            $userService->activate($id, $request->get(self::ACTIVATE_KEY_PARAMETER));
        } catch (AccountValidationException $accountValidationException) {
            // TODO: Handle error message.
            return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN);
        }
        return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN);
    }

    /**
     * @param Request $request
     * @param UserService $userService
     * @return Response
     * @Route("/profile", name="security_profile")
     */
    public function profile(Request $request, UserService $userService): Response
    {
        $currentEmailAddress = $userService->getUser()->getUsername();
        $updateProfileForm = $this->createForm(ProfileFormType::class, $userService->getUser());
        $updatePasswordForm = $this->createForm(PasswordFormType::class, $userService->getUser());
        $deleteAccountForm = $this->createForm(AccountDeletionFormType::class, $userService->getUser());

        $updateProfileForm->handleRequest($request);
        $updatePasswordForm->handleRequest($request);
        $deleteAccountForm->handleRequest($request);
        if ($updateProfileForm->isSubmitted() && $updateProfileForm->isValid()) {
            $userService->updateProfile($updateProfileForm, $currentEmailAddress);
        } elseif ($updatePasswordForm->isSubmitted() && $updatePasswordForm->isValid()) {
            try {
                $userService->updatePassword($updatePasswordForm);
            } catch (TransportExceptionInterface $transportException) {
                return $this->redirectToRoute(self::ROUTE_SECURITY_PROFILE, [
                    'error' => $transportException->getMessage()
                ]);
            }
        } elseif ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {
            $userService->delete();
            return $this->redirectToRoute(WebsiteController::ROUTE_WEBSITE_INDEX);
        }
        return $this->render(self::TEMPLATE_SECURITY_PROFILE, [
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
