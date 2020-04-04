<?php
/**
 * SecurityController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 01, 2020 at 12:23:20
 */

namespace App\Controller;

use App\Exception\AccountValidationException;
use App\Form\PasswordForgottenFormType;
use App\Form\PasswordResetFormType;
use App\Form\RegistrationFormType;
use App\Service\KeyManager;
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
     * The security login route.
     * @var string
     */
    const ROUTE_SECURITY_LOGIN = 'security_login';

    /**
     * The security login template.
     * @var string
     */
    const TEMPLATE_SECURITY_LOGIN = 'security/login.html.twig';

    /**
     * The security register route.
     * @var string
     */
    const ROUTE_SECURITY_REGISTER = 'security_register';

    /**
     * The security register template.
     * @var string
     */
    const TEMPLATE_SECURITY_REGISTER = 'security/register.html.twig';

    /**
     * The security activate route.
     * @var string
     */
    const ROUTE_SECURITY_ACTIVATE = 'security_activate';

    /**
     * The security password reset route.
     * @var string
     */
    const ROUTE_SECURITY_PASSWORD_RESET = 'security_password_reset';

    /**
     * The security activate template.
     * @var string
     */
    const TEMPLATE_SECURITY_PASSWORD_RESET = 'security/password_reset.html.twig';

    /**
     * The security id parameter.
     * @var string
     */
    const ID_PARAMETER = 'id';

    /**
     * The security key parameter.
     * @var string
     */
    const KEY_PARAMETER = 'key';

    /**
     * The control for the security login.
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @Route("/login", methods="GET|POST", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $passwordForgottenForm = $this->createForm(PasswordForgottenFormType::class);
        if ($this->getUser()) {
            return $this->redirectToRoute(WebsiteController::ROUTE_WEBSITE_INDEX);
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
     * The control for the security password forgotten.
     * @param Request $request
     * @param UserService $userService
     * @return RedirectResponse
     * @Route("/password/forgotten", methods="POST", name="security_password_forgotten")
     */
    public function password_forgotten(Request $request, UserService $userService): RedirectResponse
    {
        $passwordForgottenForm = $this->createForm(PasswordForgottenFormType::class);

        $passwordForgottenForm->handleRequest($request);
        if ($passwordForgottenForm->isSubmitted() && $passwordForgottenForm->isValid()) {
            $userService->setUser($userService->getObjectRepository()->findOneBy([
                'email' => $passwordForgottenForm->get(PasswordForgottenFormType::EMAIL_FIELD)->getData()
            ]));
            if ($userService->getUser()) {
                $userService->getUser()->setActivationKey(KeyManager::generate());
                $editPasswordUrl = $this->generateUrl(self::ROUTE_SECURITY_PASSWORD_RESET, [
                    self::ID_PARAMETER => $userService->getUser()->getId(),
                    self::KEY_PARAMETER => $userService->getUser()->getActivationKey()
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                try {
                    $userService->forgotten($editPasswordUrl);
                } catch (TransportExceptionInterface $transportException) {
                    return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN, [
                        'error' => $transportException->getMessage()
                    ]);
                }
            }
        }
        return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN, [
            'info' => 'security.login.reset'
        ]);
    }

    /**
     * The control for the security password reset.
     * @param int $id
     * @param Request $request
     * @param UserService $userService
     * @return Response
     * @Route("/password/reset/{id?0}", methods="GET", name="security_password_reset", requirements={"id"="\d+"})
     */
    function password_reset(int $id, Request $request, UserService $userService): Response
    {
        $userService->setUser($userService->get($id));
        if ($userService->getUser()) {
            $userActivationKey = $userService->getUser()->getActivationKey();

            if ($userActivationKey && KeyManager::verify($request->get(self::KEY_PARAMETER), $userActivationKey)) {
                $passwordResetForm = $this->createForm(PasswordResetFormType::class);

                return $this->render(self::TEMPLATE_SECURITY_PASSWORD_RESET, [
                    self::ID_PARAMETER => $id,
                    self::KEY_PARAMETER => $userActivationKey,
                    'passwordResetForm' => $passwordResetForm->createView()
                ]);
            }
        }
        return $this->redirectToRoute('profile_index');
    }

    /**
     * The control for the security password update.
     * @param int $id
     * @param Request $request
     * @param UserService $userService
     * @return RedirectResponse
     * @Route("/password/update/{id?0}", methods="POST", name="security_password_update", requirements={"id"="\d+"})
     */
    function password_update(int $id, Request $request, UserService $userService): RedirectResponse
    {
        $userService->setUser($userService->get($id));
        if ($userService->getUser()) {
            $userActivationKey = $userService->getUser()->getActivationKey();

            if ($userActivationKey && KeyManager::verify($request->get(self::KEY_PARAMETER), $userActivationKey)) {
                $passwordResetForm = $this->createForm(PasswordResetFormType::class, $this->getUser());

                $passwordResetForm->handleRequest($request);
                if ($passwordResetForm->isSubmitted() && $passwordResetForm->isValid()) {
                    try {
                        $userService->updatePassword($passwordResetForm);
                    } catch (TransportExceptionInterface $transportException) {
                        return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN, [
                            'error' => $transportException->getMessage()
                        ]);
                    }
                }
                return $this->redirectToRoute(self::ROUTE_SECURITY_PASSWORD_RESET, [
                    self::ID_PARAMETER => $id,
                    self::KEY_PARAMETER => $request->get(self::KEY_PARAMETER),
                    'error' => 'security.reset.error'
                ]);
            }
        }
        return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN);
    }

    /**
     * The control for the security register.
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
     * The control for the security registration.
     * @param Request $request
     * @param UserService $userService
     * @return RedirectResponse
     * @Route("/registration", methods="POST", name="security_registration")
     */
    public function registration(Request $request, UserService $userService): RedirectResponse
    {
        $registrationForm = $this->createForm(RegistrationFormType::class, $userService->getUser());

        $registrationForm->handleRequest($request);
        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $userService->create($registrationForm);
            try {
                $activationUrl = $this->generateUrl(self::ROUTE_SECURITY_ACTIVATE, [
                    self::ID_PARAMETER => $userService->getUser()->getId(),
                    self::KEY_PARAMETER => $userService->getUser()->getActivationKey()
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                $userService->prepare($activationUrl);
            } catch (TransportExceptionInterface $transportException) {
                return $this->redirectToRoute(self::ROUTE_SECURITY_REGISTER, [
                    'error' => $transportException->getMessage()
                ]);
            }
        } elseif ($registrationForm->isSubmitted() && !$registrationForm->isValid()) {
            return $this->redirectToRoute(self::ROUTE_SECURITY_REGISTER, [
                'error' => 'security.register.error'
            ]);
        }
        return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN, [
            'info' => 'security.login.registered'
        ]);
    }

    /**
     * The control for the security activate.
     * @param int $id
     * @param Request $request
     * @param UserService $userService
     * @return RedirectResponse
     * @Route("/activate/{id}", methods="GET", name="security_activate", requirements={"id"="\d+"})
     */
    public function activate(int $id, Request $request, UserService $userService): RedirectResponse
    {
        try {
            $userService->activate($id, $request->get(self::KEY_PARAMETER));
        } catch (AccountValidationException $accountValidationException) {
            return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN, [
                'error' => 'security.login.activation_error'
            ]);
        }
        return $this->redirectToRoute(self::ROUTE_SECURITY_LOGIN, [
            'info' => 'security.login.activated'
        ]);
    }

    /**
     * The control for the security logout.
     * @throws Exception
     * @Route("/logout", methods="GET", name="security_logout")
     */
    public function logout(): void
    {
        throw new Exception(
            'This method can be blank - it will be intercepted by the logout key on your firewall'
        );
    }
}
