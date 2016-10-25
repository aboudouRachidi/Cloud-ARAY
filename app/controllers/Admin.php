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
		
		foreach ($users as $user){
			$total = 0;
			$user = DAO::getOne("utilisateur", $user->getId());
			$disques = DAO::getOneToMany($user,"disques");
			foreach ($disques as $disque){	
				$total = $total + $disque->getTarif()->getPrix();
			}
			$nbDisque = count($user->getDisques());
			$this->loadView("user/vAllUsers.html",array("users"=>$user,"nbDisque"=>$nbDisque,"total"=>$total));
		}
		
	}
	
	public function disques(){
		$objects = DAO::getAll("disque");
		$users=DAO::getAll("Utilisateur");
		
		foreach ($users as $user) {
			$disque = DAO::getOneToMany($user, "disques");
			
			if(sizeof($user->getDisques())>0)
				foreach ($user->getDisques() as $disque){
				$disque = DAO::getOne("disque", $disque->getId());
				$tarifs = $disque->getTarif();
			}
				$this->loadView("disque/vDisquesUsers.html",array("user"=>$user,"tarifs"=>$tarifs,"disque"=>$disque));
		}
		$this->loadView("disque/vObjects.html",array("objects"=>$objects));
	}
}