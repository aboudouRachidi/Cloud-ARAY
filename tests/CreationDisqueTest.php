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