<?php
/**
 * AccountValidationException.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 02, 2020 at 21:55:19
 */

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Throwable;

/**
 * Class AccountValidationException
 * @package App\Exception
 */
class AccountValidationException extends AccountStatusException
{
    /**
     * The message key.
     * @var string
     */
    private $messageKey;

    /**
     * AccountValidationException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setMessageKey($message);
    }

    /**
     * Gets the message key.
     * @return string
     */
    public function getMessageKey()
    {
        return $this->messageKey;
    }

    /**
     * Sets the message key.
     * @param string $messageKey
     */
    public function setMessageKey(string $messageKey): void
    {
        $this->messageKey = $messageKey;
    }
}
