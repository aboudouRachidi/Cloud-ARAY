<?php
use micro\orm\DAO;

class FolderTest extends \PHPUnit_Framework_TestCase {
	private $config;
	public function setUp(){
		global $config;
		$this->config = $config;
		DAO::connect($config["database"]["dbName"]);
	}
	
	public function testConfigIsOk(){
		$this->assertArrayHasKey("siteUrl", $this->config);
	}
	
	public function testBaseDirectoryExists(){
		$cloud = $this->config["cloud"];
		$this->assertTrue(file_exists("./../".$cloud["root"]));
	}
	
	public function testCreateFolder(){
		$cloud = $this->config["cloud"];
		$pathname="./../".$cloud["root"]."/".$cloud["prefix"]."test";
		$this->assertFileNotExists($pathname);
		DirectoryUtils::mkDir($pathname);
		$this->assertFileExists($pathname);
		rmdir($pathname);
	}
	
	public function testExistUsersFolder(){
		$cloud = $this->config["cloud"];
		$users = DAO::getAll("Utilisateur");
		foreach ($users as $user){
			$pathname="./../".$cloud["root"]."/".$cloud["prefix"].$user->getLogin();
			$this->assertFileExists($pathname);
			//DirectoryUtils::mkDir($pathname);
		}
		
	}
}