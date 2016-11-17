<?php
use micro\orm\DAO;

class ConnexionTest extends \AjaxUnitTest {
	private $config;
	public function setUp(){
		global $config;
		$this->config = $config;
		DAO::connect($config["database"]["dbName"]);
	}
	
	public function testConfigIsOk(){
		$this->assertArrayHasKey("siteUrl", $this->config);
	}
	public function testDefault(){
		$this->assertPageContainsText("Connexion utilisateur");
		$this->assertTrue($this->elementExists("#frm"));
		$this->assertTrue($this->elementExists("#mail"));
		$this->assertTrue($this->elementExists("#pwd"));
	}

	public function testUserConnect(){
		$this->getElementById("mail")->sendKeys("user@local.fr");
		$password=$this->getElementById("pwd");
		$password->sendKeys("azerty");
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(5);
		$btnSubmit=$this->getElementById("connexion");
		$btnSubmit->click();
	}
	

}