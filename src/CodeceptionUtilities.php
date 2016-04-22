<?php

namespace Codeception\Module;

use Codeception\Module;
use Codeception\SuiteManager;

class CodeceptionUtilities extends Module
{
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
