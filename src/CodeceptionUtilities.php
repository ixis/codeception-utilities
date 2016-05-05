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
        /** @var WebInterface $module */
        $module = $this->getModule($this->getBrowserModuleName());

        $module->see($text, $this->getLinkSelector($link, $cssOrXpath));
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
        /** @var WebInterface $module */
        $module = $this->getModule($this->getBrowserModuleName());

        $module->dontSee($text, $this->getLinkSelector($link, $cssOrXpath));
    }

    /**
     * Helper method to calculate the correct link selector.
     *
     * @param string $link
     * @param string $cssOrXpath
     *
     * @return string
     */
    protected function getLinkSelector($link, $cssOrXpath)
    {
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

        return $link_selector;
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
     * @param string $pseudo
     *   The pseudo selector to retrieve the style for e.g. ::before, :hover
     */
    public function seeElementHasStyle($selector, $style, $value, $pseudo = null)
    {
        $this->assert($this->proceedSeeElementHasStyle($selector, $style, $value, $pseudo));
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
     * @param string $pseudo
     *   The pseudo selector to retrieve the style for e.g. ::before, :hover
     */
    public function dontSeeElementHasStyle($selector, $style, $value, $pseudo = null)
    {
        $this->assertNot($this->proceedSeeElementHasStyle($selector, $style, $value, $pseudo));
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
     * @param string $pseudo
     *   The pseudo selector to retrieve the style for e.g. ::before, :hover
     *
     * @return array
     *
     * @throws \LogicException
     *   If attempting to call function when WebDriver not in use.
     */
    protected function proceedSeeElementHasStyle($selector, $style, $value, $pseudo = null)
    {
        $computedvalue = $this->grabElementStyle($selector, $style, $pseudo);

        return array("Equals", $value, $computedvalue);
    }

    /**
     * Get a style value from an element.
     *
     * @param string $selector
     *   A CSS (only) selector to identify the element.
     * @param string $style
     *   The style name e.g. font-weight, float
     * @param string $pseudo
     *   The pseudo selector to retrieve the style for e.g. ::before, :hover
     *
     * @return mixed
     *
     * @throws \LogicException
     *   If attempting to call function when WebDriver not in use.
     */
    public function grabElementStyle($selector, $style, $pseudo = null)
    {
        if ($this->getBrowserModuleName() !== 'WebDriver') {
            throw new \LogicException("Computed styles only available for inspection when using WebDriver");
        }

        $js = sprintf(
          "return window.getComputedStyle(document.querySelector('%s')%s)['%s']",
          $selector,
          $pseudo ? ", '$pseudo'" : "",
          $style
        );

        return $this->getModule("WebDriver")->executeJs($js);
    }

    /**
     * Work out which browser module is in use.
     *
     * @return null|string
     *   Returns PhpBrowser if that module is detected. Otherwise, returns WebDriver. If neither of these are detected,
     *   return null.
     */
    public function getBrowserModuleName()
    {
        if (isset(SuiteManager::$actions['seeInTitle'])) {
            return SuiteManager::$actions['seeInTitle'];
        }

        return null;
    }
}
