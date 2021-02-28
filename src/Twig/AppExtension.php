<?php
/**
 * AppExtension.php
 * Created by nicolas for Website
 * Developed and maintained using PhpStorm
 * Started on févr. 21, 2021 at 19:16:54
 */

namespace App\Twig;

use Symfony\Component\Intl\Locales;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class AppExtension
 * @package App\Twig
 */
class AppExtension extends AbstractExtension
{
    /**
     * @var string[]
     */
    private $locales;

    /**
     * @var false|string[]
     */
    private $localeCodes;

    /**
     * AppExtension constructor.
     * @param string $locales
     */
    public function __construct(string $locales)
    {
        $localeCodes = explode('|', $locales);
        sort($localeCodes);
        $this->localeCodes = $localeCodes;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('getLocaleName', [$this, 'getLocaleName'])
        ];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getLocales', [$this, 'getLocales'])
        ];
    }

    /**
     * @param string $localeCode
     * @return string
     */
    public function getLocaleName(string $localeCode): string
    {
        return Locales::getName($localeCode, $localeCode);
    }

    /**
     * @return array|string[]
     */
    public function getLocales(): array
    {
        if ($this->locales !== null)
            return $this->locales;
        $this->locales = [];
        foreach ($this->localeCodes as $localeCode) {
            $this->locales[] = [
                'code' => $localeCode,
                'name' => $this->getLocaleName($localeCode)
            ];
        }
        return $this->locales;
    }
}
