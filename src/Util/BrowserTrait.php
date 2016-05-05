<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 05/05/2016
 * Time: 13:10
 */
namespace Ixis\Codeception\Util;

use Codeception\SuiteManager;
use Codeception\Lib\Interfaces\Web as WebInterface;

trait BrowserTrait {

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

    /**
     * @return WebInterface
     * @throws \Codeception\Exception\Module
     */
    protected function getBrowserModule()
    {
        return $this->getModule($this->getBrowserModuleName());
    }
}
