<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
class Admin extends \BaseController {
	use MessagesTrait;
	public function isValid(){
		if (Auth::isAdmin() && Auth::isAuth())
			return true;
	}
	public function onInvalidControl(){
		$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
		$this->messageDanger("Vous n'êtes pas autorisé à afficher cette page !",3000,false);
		$this->loadView("main/vDefault.html");
		$this->loadView("main/vFooter.html");
		exit();
	}
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
				$disquesServices = DAO::getManyToMany($disque, "services");
				foreach ($disquesServices as $service){
					$total = $total + $service->getPrix();
				}
			}
			$nbDisque = count($user->getDisques());
			
			$this->loadView("user/vAllUsers.html",array("user"=>$user,"nbDisque"=>$nbDisque,"total"=>$total));
		}
		
	}
	
	public function disques(){
		
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
	}
	
	public function sentMessage(){
		if(isset($_POST['receveur'])){
			$idReceveur = $_POST['receveur'];
			$receveur = DAO::getOne("utilisateur",$idReceveur);
			$message = new Message();
			RequestUtils::setValuesToObject($message,$_POST);
			$message->setExpediteur(Auth::getUser());
			$message->setReceveur($receveur);
			$message->setLu(1);
			if(DAO::insert($message)){
				$this->messageSuccess("Le message ".$message->toString()." a été envoyé à ".$receveur,8000,true);
				$this->users();
			}else{
				$this->messageWarning("Impossible d'envoyer le message ".$message->toString());
				$this->users();
			}
		}else{
			$this->users();
				
		}
	}
}