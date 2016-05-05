# Codeception utilities

A handful of generic helper and utility methods for Codeception.

* **seeLinkInSelector** - looks for a link in a particular CSS or XPath selector.
* **dontSeeLinkInSelector** - makes sure a link is not in a particular CSS or XPath selector.
* **seeElementHasStyle** - see an element has been applied a style. Allows you to check a single element has a CSS style assigned, e.g. you can check that ".icon-r" class is floated right. Requires WebDriver.
* **dontSeeElementHasStyle** -  see element has not been applied a style. Requires WebDriver.

## CkEditor utilities

* **fillCkEditor** - Fill a [CKEditor](http://ckeditor.com/) WYSIWYG field.
* **grabCkEditorValue** - Grab the current value of the CKEditor field.
* **seeInCkEditor** - see specified text inside a CKEditor instance.
* **dontSeeInCkEditor** - don't see specified text inside a CKEditor instance.

## Installing

Add to your **composer.json**, e.g.

```
{
  "repositories": [
    {
      "type":"vcs",
      "url":"git@github.com:ixis/codeception-utilities.git"
    }
  ],
  "require": {
    "ixis/codeception-utilities": "dev-develop"
  }
}
```

## Configuring

```
modules:
    enabled:
        - CodeceptionUtilities
        - CodeceptionCkEditorUtilities
```

## Examples

```
// See a link with title and URL as specified in the element selected by "#content #footer".
$I->seeLinkInSelector("Facebook", "https://www.facebook.com/", "#content #footer");

// See the element selected by "#footer .icon-r" has the float:right style applied.
$I->seeElementHasStyle("#footer .icon-r", "float", "right");

// Set the ckeditor with id "editor1" to "<p>hello world</p>"
$I->fillCkEditor("editor1", "<p>hello world</p>");

// Grab the current value of the ckeditor field and assert it contains "hello"
$value = $I->grabCkEditorValue("editor1");
PHPUnit_Framework_Assert::assertContains("hello", $value);
// or, if using Asserts Codeception module:
$I->assertContains("hello", $value)
```
