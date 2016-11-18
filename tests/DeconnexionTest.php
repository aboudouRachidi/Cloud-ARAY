<?php
use micro\orm\DAO;

class DeconnexionTest extends \AjaxUnitTest {
	private $config;
	public function setUp(){
		global $config;
		$this->config = $config;
		self::get("Accueil/index");
		DAO::connect($config["database"]["dbName"]);
	}
	
	public function testConfigIsOk(){
		$this->assertArrayHasKey("siteUrl", $this->config);
	}
	
	public function testDefault(){
		$this->assertNotNull($this->getElementById("deco"));
	}
	
	public function testUserDisconnect(){
		//
		$this->getElementById("mail")->sendKeys("user@local.fr");
		$password=$this->getElementById("pwd");
		$password->sendKeys("azerty");
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(10);
		$btnSubmit=$this->getElementById("connexion");
		$btnSubmit->click();
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(10);
		$btnDisconect=$this->getElementById("deco");
		$btnDisconect->click();
		
		}
	}