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
     * The default key size.
     * @var int
     */
    const DEFAULT_ACTIVATION_KEY_SIZE = 32;

    /**
     * Generates a random key with an optional key size.
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
     * Checks if an input key matches a generated key.
     * @param string $inputActivationKey
     * @param string $activationKey
     * @return bool
     */
    public static function verify(?string $inputActivationKey, ?string $activationKey): bool
    {
        return !strcmp($inputActivationKey, $activationKey);
    }
}
