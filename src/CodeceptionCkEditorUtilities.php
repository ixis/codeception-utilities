<?php

namespace Codeception\Module;

use Codeception\Exception\ElementNotFound;
use Codeception\Module;
use Ixis\Codeception\Util\BrowserTrait;

class CodeceptionCkEditorUtilities extends Module
{
    use BrowserTrait;

    /**
     * Fill a CKEditor field.
     *
     * @see http://docs.ckeditor.com/#!/api/CKEDITOR.editor-method-setData
     *
     * @param string $instance_id
     *   The CKEditor instance id.
     * @param $value
     *   The value (raw HTML) to enter into CK Editor.
     *
     * @throws ElementNotFound
     *   If CKEditor text area could not be found.
     */
    public function fillCkEditor($instance_id, $value)
    {
        $this->checkCkEditorInstanceId($instance_id);
        $this->getBrowserModule()->executeJs("CKEDITOR.instances['$instance_id'].setData(".json_encode($value).")");
    }

    /**
     * Retrieve the contents of a CKEditor field.
     *
     * @see http://docs.ckeditor.com/#!/api/CKEDITOR.editor-method-getData
     *
     * @param string $instance_id
     *   The CKEditor instance id.
     *
     * @return string
     *   The CKEditor data (raw HTML).
     *
     * @throws ElementNotFound
     *   If CKEditor text area could not be found.
     *
     */
    public function grabCkEditorValue($instance_id)
    {
        $this->checkCkEditorInstanceId($instance_id);
        return $this->getBrowserModule()->executeJs("return CKEDITOR.instances['$instance_id'].getData()");
    }

    /**
     * @param string $instance_id
     *   The CKEditor instance id.
     *
     * @throws ElementNotFound
     *   If CKEditor instance could not be located.
     */
    protected function checkCkEditorInstanceId($instance_id)
    {
        if ($this->getBrowserModule()->executeJs("return typeof CKEDITOR == 'undefined'")) {
            throw new ElementNotFound("CKEditor");
        }

        if ($this->getBrowserModule()->executeJs("return typeof CKEDITOR.instances['$instance_id'] == 'undefined'")) {
            throw new ElementNotFound($instance_id, "CKEditor instance");
        }
    }

    /**
     * See text within a CK Editor iframe.
     *
     * @param string $value
     *   The value to check.
     * @param string $instance_id
     *   The CKEditor instance id.
     */
    public function seeInCkEditor($value, $instance_id)
    {
        $this->assert($this->proceedSeeInCkEditor($value, $instance_id));
    }

    /**
     * Don't see text within a CK Editor iframe.
     *
     * @param string $value
     *   The value that should not be there.
     * @param string $instance_id
     *   The CKEditor instance id.
     */
    public function dontSeeInCkEditor($value, $instance_id)
    {
        $this->assertNot($this->proceedSeeInCkEditor($value, $instance_id));
    }

    /**
     * Helper method for checking text in a ckeditor iframe.
     *
     * @param string $value
     *   The string to check.
     * @param string $instance_id
     *   The CKEditor instance id.
     *
     * @return array
     *
     * @throws \LogicException
     *   If attempting to call function when WebDriver not in use.
     */
    protected function proceedSeeInCkEditor($value, $instance_id)
    {
        $content = $this->grabCkEditorValue($instance_id);
        return array("True", strpos($content, $value) !== false);
    }
}
