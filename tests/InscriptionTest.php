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
	
	public function testDefault(){
		$this->assertPageContainsText("Inscription utilisateur");
		$this->assertTrue($this->elementExists("#frm"));
		$this->assertTrue($this->elementExists("#inputNom"));
		$this->assertTrue($this->elementExists("#inputPrenom"));
		$this->assertTrue($this->elementExists("#inputLogin"));
		$this->assertTrue($this->elementExists("#inputEmail"));
		$this->assertTrue($this->elementExists("#inputPassword"));
		$this->assertTrue($this->elementExists("#InputConfirmPassword"));
	}
	
	
	public function testUserInscription(){
		
		$inputNom=$this->getElementById("inputNom");
		$inputNom->sendKeys("dupont");
		
		$inputPrenom=$this->getElementById("inputPrenom");
		$inputPrenom->sendKeys("harry");
		
		$inputLogin=$this->getElementById("inputLogin");
		$inputLogin->sendKeys("hdupont");
				
		$inputEmail=$this->getElementById("inputEmail");
		$inputEmail->sendKeys("harry.dupont@test.fr");
		
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