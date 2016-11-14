<?php
class SeleniumTest extends \AjaxUnitTest{
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		self::get("Selenium/index");
	}

	public function testDefault(){
		$this->assertPageContainsText("Hello Selenium");
	}
}