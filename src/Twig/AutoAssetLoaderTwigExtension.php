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

use Symfony\Component\Finder\Finder;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig_Template;

/**
 * Class AutoAssetLoaderTwigExtension
 *
 * @category   Twig
 * @package    App\Twig
 * @author     Lauser, Nicolai <munchkin-counter@lauser.info>
 * @copyright  2019 Lauser, Nicolai
 * @version    $Id$
 */
class AutoAssetLoaderTwigExtension extends AbstractExtension
{
    /**
     * @var TagRenderer
     */
    private $_tagRenderer;

    /**
     * @var string
     */
    private $_rootDirectory;

    /**
     * @var Finder
     */
    private $_finder;

    /**
     * AutoAssetLoaderTwigExtension constructor.
     * @param TagRenderer $tagRenderer
     * @param string $rootDirectory
     */
    public function __construct(TagRenderer $tagRenderer, string $rootDirectory)
    {
        $this->_tagRenderer = $tagRenderer;
        $this->_rootDirectory = $rootDirectory;
        $this->_finder = new Finder();
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('load_js_assets', [$this, 'loadJsAssets'], ['is_safe' => ['html']]),
            new TwigFunction('load_css_assets', [$this, 'loadCssAssets'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @return string
     */
    public function loadJsAssets()
    {
        $template = $this->getTemplate();

        if (!$template || !$this->fileExists($template, 'js')) {
            return null;
        }

        return $this->_tagRenderer->renderWebpackScriptTags($template);
    }

    /**
     * @return string|null
     */
    private function getTemplate()
    {
        $template = null;
        foreach (debug_backtrace() as $trace) {
            if (isset($trace['object']) && $trace['object'] instanceof Twig_Template && 'Twig_Template' !== get_class($trace['object'])) {
                $template = $trace['object'];
            }
        }

        if (!$template) {
            return null;
        }

        return basename($template->getTemplateName(), '.html.twig');
    }

    /**
     * @param string $template
     * @param string $ext
     * @return bool
     */
    private function fileExists(string $template, string $ext)
    {
        return $this->_finder
            ->name(sprintf('%s.*.%s', $template, $ext))
            ->name(sprintf('%s.%s', $template, $ext))
            ->files()
            ->in(sprintf('%s/public/build', $this->_rootDirectory))
            ->hasResults();
    }

    /**
     * @return string|null
     */
    public function loadCssAssets()
    {
        $template = $this->getTemplate();

        if (!$template || !$this->fileExists($template, 'css')) {
            return null;
        }

        return $this->_tagRenderer->renderWebpackLinkTags($template);
    }
}