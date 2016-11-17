<?php
use micro\orm\DAO;

class InscriptionTest extends \AjaxUnitTest {
	private $config;
	public function setUp(){
		global $config;
		$this->config = $config;
		DAO::connect($config["database"]["dbName"]);
	}
	
	public function testConfigIsOk(){
		$this->assertArrayHasKey("siteUrl", $this->config);
	}
	
	public function testUserInscription(){
		
		$nom=$this->getElementById("nom");
		$nom->sendKeys("dupont");
		
		$prenom=$this->getElementById("prenom");
		$prenom->sendKeys("harry");
		
		$login=$this->getElementById("login");
		$login->sendKeys("hdupont");
				
		$email=$this->getElementById("email");
		$email->sendKeys("harry.dupont@test.fr");
		
		$inputPassword=$this->getElementById("inputPassword");
		$inputPassword->sendKeys("azerty");
		
		$inputConfirmPassword=$this->getElementById("inputConfirmPassword");
		$inputConfirmPassword->sendKeys("azerty");
		
		//-----
		InscriptionTest::$webDriver->manage()->timeouts()->implicitlyWait(5);
		$btnSubmit=$this->getElementById("inscription");
		$btnSubmit->click();
		
		
	}
	
	
}