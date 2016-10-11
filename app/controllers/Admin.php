<?php
use micro\orm\DAO;
class Admin extends \BaseController {

	public function index() {
		if(Auth::isAdmin() && Auth::isAuth()){
			$Count = array(
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
}