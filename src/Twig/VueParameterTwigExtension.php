<?php
/**
 * This file is part of the Munchkin Counter project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author     Lauser, Nicolai <munchkin-counter@lauser.info>
 * @copyright  2019 Lauser, Nicolai
 * @version    $Id$
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class VueParameterTwigExtension
 *
 * @category   Twig
 * @package    App\Twig
 * @author     Lauser, Nicolai <munchkin-counter@lauser.info>
 * @copyright  2019 Lauser, Nicolai
 * @version    $Id$
 */
class VueParameterTwigExtension extends AbstractExtension
{
    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('vue', [$this, 'escape'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @param string $param
     * @return string
     */
    public function escape(string $param)
    {
        return sprintf('{{ %s }}', $param);
    }
}