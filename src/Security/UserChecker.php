<?php
/**
 * UserChecker.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 09, 2020 at 16:40:01
 */

namespace App\Security;

use App\Entity\User;
use App\Exception\AccountValidationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserChecker
 * @package App\Security
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * Performs extra checks before authentication.
     * @param UserInterface $user
     * @throws \Exception
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!($user instanceof User))
            return;
        if (!$user->getIsActivated())
            throw new AccountValidationException('Account not yet activated');
    }

    /**
     * Performs extra checks after authentication.
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!($user instanceof User))
            return;
    }
}
