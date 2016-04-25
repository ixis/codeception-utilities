<?php

namespace Codeception\Module;

use Codeception\Exception\ElementNotFound;
use Codeception\Module;

class CodeceptionCkEditorUtilities extends Module
{
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
        $this->getModule("WebDriver")->executeJs("CKEDITOR.instances['$instance_id'].setData(".json_encode($value).")");
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
        return $this->getModule("WebDriver")->executeJs("return CKEDITOR.instances['$instance_id'].getData()");
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
        /** @var WebDriver $wd */
        $wd = $this->getModule("WebDriver");
        if ($wd->executeJs("return typeof CKEDITOR.instances['$instance_id'] == 'undefined'")) {
            throw new ElementNotFound($instance_id);
        }
    }
}
