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
		$this->get("Accueil/index");
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(5);
		$identifiant=$this->getElementById("identifiant");
		$identifiant->sendKeys("user@local.fr");
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(5);
		$password=$this->getElementById("pwd");
		$password->sendKeys("azerty");
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(5);
	}
	

}