<?php
use micro\orm\DAO;

class InscriptionTest extends \AjaxUnitTest {
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
		$this->assertPageContainsText("Inscription utilisateur");
		$this->assertNotNull($this->getElementById("frm2"));
		$this->assertNotNull($this->getElementById("inputNom"));
		$this->assertNotNull($this->getElementById("inputPrenom"));
		$this->assertNotNull($this->getElementById("inputLogin"));
		$this->assertNotNull($this->getElementById("inputEmail"));
		$this->assertNotNull($this->getElementById("inputPassword"));
		$this->assertNotNull($this->getElementById("inputConfirmPassword"));
	}
	//
	
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
		
		$btnSubmit=$this->getElementById("inscription");
		$btnSubmit->click();
		InscriptionTest::$webDriver->manage()->timeouts()->implicitlyWait(15);
		//$this->assertNotNull(DAO::getOne("Utilisateur","login='hdupont'"));
		
		//
	}
	
	
}