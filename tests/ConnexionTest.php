<?php
use micro\orm\DAO;

class ConnexionTest extends \PHPUnit_Framework_TestCase {
	private $config;
	public function setUp(){
		global $config;
		$this->config = $config;
		DAO::connect($config["database"]["dbName"]);
	}
	
	public function testConfigIsOk(){
		$this->assertArrayHasKey("siteUrl", $this->config);
	}

	public function testUserConnect(){
		$identifiant=$this->getElementById("identifiant");
		$identifiant->sendKeys("user@local.fr");
		$password=$this->getElementById("pwd");
		$password->sendKeys("azerty");
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(5);
		$btnSubmit=$this->getElementById("connexion");
		$btnSubmit->click();
	}
	

}