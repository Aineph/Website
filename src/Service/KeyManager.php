<?php
/**
 * KeyManager.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 01, 2020 at 12:32:35
 */

namespace App\Service;

/**
 * Class KeyManager
 * @package App\Service
 */
class KeyManager
{
    /**
     * @var int
     */
    const DEFAULT_ACTIVATION_KEY_SIZE = 32;

    /**
     * @param int $keySize
     * @return string
     */
    public static function generate(int $keySize = self::DEFAULT_ACTIVATION_KEY_SIZE): string
    {
        $activationKey = "";
        try {
            $activationKey .= bin2hex(random_bytes($keySize));
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        return $activationKey;
    }

    /**
     * @param string $inputActivationKey
     * @param string $activationKey
     * @return bool
     */
    public static function verify(string $inputActivationKey, string $activationKey): bool
    {
        return !strcmp($inputActivationKey, $activationKey);
    }
}
