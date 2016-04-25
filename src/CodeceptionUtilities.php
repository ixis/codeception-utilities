<?php

namespace Codeception\Module;

use Codeception\Lib\Interfaces\Web as WebInterface;
use Codeception\Module;
use Codeception\SuiteManager;
use Codeception\Util\Locator;

class CodeceptionUtilities extends Module
{
    /**
     * Looks for a link in a particular CSS or XPath selector.
     *
     * @param string $text
     *   Text to look for.
     * @param string $link
     *   URL the text should link to.
     * @param string $cssOrXpath
     *   The selector in which to look for the link.
     */
    public function seeLinkInSelector($text, $link, $cssOrXpath)
    {
        $moduleName = SuiteManager::$actions['seeInTitle'];
        /** @var WebInterface $module */
        $module = $this->getModule($moduleName);

        if ($link) {
            if (Locator::isCSS($cssOrXpath)) {
                $link_selector = sprintf("%s a[href*='%s']", $cssOrXpath, $link);
            } else {
                $link_selector = sprintf("%s//a[contains(@href,'%s')]", $cssOrXpath, $link);
            }
        } else {
            if (Locator::isCSS($cssOrXpath)) {
                $link_selector = sprintf("%s a", $cssOrXpath);
            } else {
                $link_selector = sprintf("%s//a", $cssOrXpath);
            }
        }
        $module->see($text, $link_selector);
    }

    /**
     * Checks that a link does not appear in a particular CSS or XPath selector.
     *
     * @param string $text
     *   Text to ensure doesn't exist.
     * @param string $link
     *   URL the text should not link to.
     * @param string $cssOrXpath
     *   The selector in which to look for the lack of link.
     */
    public function dontSeeLinkInSelector($text, $link, $cssOrXpath)
    {
        $moduleName = SuiteManager::$actions['seeInTitle'];
        /** @var WebInterface $module */
        $module = $this->getModule($moduleName);

        if ($link) {
            if (Locator::isCSS($cssOrXpath)) {
                $link_selector = sprintf("%s a[href*='%s']", $cssOrXpath, $link);
            } else {
                $link_selector = sprintf("%s//a[contains(@href,'%s')]", $cssOrXpath, $link);
            }
        } else {
            if (Locator::isCSS($cssOrXpath)) {
                $link_selector = sprintf("%s a", $cssOrXpath);
            } else {
                $link_selector = sprintf("%s//a", $cssOrXpath);
            }
        }
        $module->dontSee($text, $link_selector);
    }

    /**
     * See element has been applied a style.
     *
     * Allows you to check a single element has a css style assigned.
     * e.g. you can check that ".icon-r" class is floated right.
     *
     * @param string $selector
     *   A CSS (only) selector to identify the element.
     * @param string $style
     *   The style name e.g. font-weight, float
     * @param string $value
     *   The value to check e.g. bold, right
     */
    public function seeElementHasStyle($selector, $style, $value)
    {
        $this->assert($this->proceedSeeElementHasStyle($selector, $style, $value));
    }

    /**
     * See element has not been applied a style.
     *
     * @param string $selector
     *   A CSS (only) selector to identify the element.
     * @param string $style
     *   The style name e.g. font-weight, float
     * @param string $value
     *   The value to check e.g. bold, right
     */
    public function dontSeeElementHasStyle($selector, $style, $value)
    {
        $this->assertNot($this->proceedSeeElementHasStyle($selector, $style, $value));
    }

    /**
     * Helper method for checking an element has a css style applied.
     *
     * @param string $selector
     *   A CSS (only) selector to identify the element.
     * @param string $style
     *   The style name e.g. font-weight, float
     * @param string $value
     *   The value to check e.g. bold, right
     *
     * @return array
     *
     * @throws \LogicException
     *   If attempting to call function when WebDriver not in use.
     */
    protected function proceedSeeElementHasStyle($selector, $style, $value)
    {
        if ($this->getBrowserName() !== 'WebDriver') {
            throw new \LogicException("Computed styles only available for inspection when using WebDriver");
        }

        $computedvalue = $this->getModule("WebDriver")
          ->executeJs("return window.getComputedStyle(document.querySelector('$selector')).$style");

        return array("Equals", $value, $computedvalue);
    }

    /**
     * Work out which browser module is in use.
     *
     * @return null|string
     *   Returns PhpBrowser if that module is detected. Otherwise, returns WebDriver. If neither of these are detected,
     *   return null.
     */
    public function getBrowserName()
    {
        if (SuiteManager::hasModule('PhpBrowser')) {
            return 'PhpBrowser';
        } elseif (SuiteManager::hasModule('WebDriver')) {
            return 'WebDriver';
        } else {
            return null;
        }
    }
}
