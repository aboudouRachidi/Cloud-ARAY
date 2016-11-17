<?php
use micro\orm\DAO;

class ConnexionTest extends \AjaxUnitTest {
	private $config;
	public function setUp(){
		global $config;
		$this->config = $config;
		//$this->get("/");
		self::get("Accueil/index");
		DAO::connect($config["database"]["dbName"]);
	}
	
	public function testConfigIsOk(){
		$this->assertArrayHasKey("siteUrl", $this->config);
	}
	public function testDefault(){

		$this->assertPageContainsText("Connexion utilisateur");
		$this->assertTrue($this->getElementById("frm"));
		$this->assertTrue($this->getElementById("mail"));
		$this->assertTrue($this->getElementById("pwd"));
	}

	public function testUserConnect(){

		$this->getElementById("mail")->sendKeys("user@local.fr");
		$password=$this->getElementById("pwd");
		$password->sendKeys("azerty");
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(10);
		$btnSubmit=$this->getElementById("connexion");
		$btnSubmit->click();
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(10);
	}
	

}