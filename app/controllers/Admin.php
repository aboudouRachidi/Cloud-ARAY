<?php
use micro\orm\DAO;
class Admin extends \BaseController {

	public function index() {
		if(Auth::isAdmin() && Auth::isAuth()){
			array(
					"newUser"	=> $newUser		= DAO::count("utilisateur","nouveau = 1"),
					"newDisk"	=> $newDisk		= DAO::count("disque","nouveau = 1"),
					"nbUser"	=> $nbUser		= DAO::count("utilisateur"),
					"nbDisk" 	=> $nbDisk		= DAO::count("disque"),
					"nbTarif"	=> $nbTarif		= DAO::count("tarif"),
					"nbService"	=> $nbService	= DAO::count("service"),
			);
			
			$this->loadView("admin/vDefault.html",
					array(	"newUser"=>$newUser,
							"newDisk"=>$newDisk,
							"nbUser"=>$nbUser,
							"nbDisk"=>$nbDisk,
							"nbTarif"=>$nbTarif,
							"nbService"=>$nbService,
							
					));
		}else {
			$this->loadView("main/vLogin.html");
		}
	}
	
	public function users(){
		$users = DAO::getAll("utilisateur");
		$this->loadView("user/vUsers.html",array("users"=>$users));
	}
	
	public function disques(){
		$objects = DAO::getAll("disque");
		$this->loadView("disque/vObjects.html",array("objects"=>$objects));
	}
}