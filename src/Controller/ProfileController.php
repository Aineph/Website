<?php
/**
 * ProfileController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on mars 09, 2020 at 11:08:41
 */

namespace App\Controller;

use App\Form\AccountDeletionFormType;
use App\Form\PasswordFormType;
use App\Form\ProfileFormType;
use App\Service\MissionService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @package App\Controller
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * The profile index template.
     * @var string
     */
    const TEMPLATE_PROFILE_INDEX = 'profile/index.html.twig';

    /**
     * The profile edit route.
     * @var string
     */
    const ROUTE_PROFILE_EDIT = 'profile_edit';

    /**
     * The profile edit template.
     * @var string
     */
    const TEMPLATE_PROFILE_EDIT = 'profile/edit.html.twig';

    /**
     * The control for the profile index.
     * @param UserService $userService
     * @param MissionService $missionService
     * @return Response
     * @Route("/", methods="GET", name="profile_index")
     */
    public function index(UserService $userService, MissionService $missionService)
    {
        return $this->render(self::TEMPLATE_PROFILE_INDEX, [
            'user' => $userService->getUser(),
            'missions' => $missionService->getLatestMissions(),
            'missionsCount' => $missionService->getMissionsCount(),
            'availability' => $missionService->getAvailability()
        ]);
    }

    /**
     * The control for the profile edit.
     * @param UserService $userService
     * @return Response
     * @Route("/edit", methods="GET", name="profile_edit")
     */
    public function edit(UserService $userService): Response
    {
        $updateProfileForm = $this->createForm(ProfileFormType::class, $userService->getUser());
        $updatePasswordForm = $this->createForm(PasswordFormType::class, $userService->getUser());
        $deleteAccountForm = $this->createForm(AccountDeletionFormType::class, $userService->getUser());

        return $this->render(self::TEMPLATE_PROFILE_EDIT, [
            'profileForm' => $updateProfileForm->createView(),
            'passwordForm' => $updatePasswordForm->createView(),
            'accountDeletionForm' => $deleteAccountForm->createView()
        ]);
    }

    /**
     * The control for the profile update.
     * @param Request $request
     * @param UserService $userService
     * @return RedirectResponse
     * @Route("/update", methods="POST", name="profile_update")
     */
    public function update(Request $request, UserService $userService)
    {
        $updateProfileForm = $this->createForm(ProfileFormType::class, $userService->getUser());
        $updatePasswordForm = $this->createForm(PasswordFormType::class, $userService->getUser());
        $deleteAccountForm = $this->createForm(AccountDeletionFormType::class, $userService->getUser());

        $updateProfileForm->handleRequest($request);
        $updatePasswordForm->handleRequest($request);
        $deleteAccountForm->handleRequest($request);
        if ($updateProfileForm->isSubmitted() && $updateProfileForm->isValid()) {
            $userService->updateProfile($updateProfileForm);
            return $this->redirectToRoute(self::ROUTE_PROFILE_EDIT, [
                'info' => 'profile.edit.profile_success'
            ]);
        } elseif ($updatePasswordForm->isSubmitted() && $updatePasswordForm->isValid()) {
            try {
                $userService->updatePassword($updatePasswordForm);
            } catch (TransportExceptionInterface $transportException) {
                return $this->redirectToRoute(self::ROUTE_PROFILE_EDIT, [
                    'error' => $transportException->getMessage()
                ]);
            }
            return $this->redirectToRoute(self::ROUTE_PROFILE_EDIT, [
                'info' => 'profile.edit.password_success'
            ]);
        } elseif ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {
            $userService->delete();
            return $this->redirectToRoute(WebsiteController::ROUTE_WEBSITE_INDEX);
        }
        return $this->redirectToRoute(self::ROUTE_PROFILE_EDIT, [
            'error' => 'profile.edit.error'
        ]);
    }
}
