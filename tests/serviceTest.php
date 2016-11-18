<?php
use micro\orm\DAO;

class ServiceTest extends \AjaxUnitTest {
	private $config;
	public function setUp(){
		global $config;
		$this->config = $config;
		//$this->get("/");
		self::get("Services/create");
		DAO::connect($config["database"]["dbName"]);
	}

	public function testConfigIsOk(){
		$this->assertArrayHasKey("siteUrl", $this->config);
	}
	public function testDefault(){

		$this->assertPageContainsText("Ajouter un service");
		$this->assertNotNull($this->getElementById("frm"));
		$this->assertNotNull($this->getElementById("nom"));
		$this->assertNotNull($this->getElementById("prix"));
		$this->assertNotNull($this->getElementById("description"));
	}

	public function testUserConnect(){
		//
		$nom=$this->getElementById("nom");
		$nom->sendKeys("Service test");
		$prix=$this->getElementById("prix");
		$prix->sendKeys("5");
		$description=$this->getElementById("description");
		$description->sendKeys("description");
		$btnSubmit=$this->getElementById("btnSubmit");
		$btnSubmit->click();
		ConnexionTest::$webDriver->manage()->timeouts()->implicitlyWait(10);
	}


}