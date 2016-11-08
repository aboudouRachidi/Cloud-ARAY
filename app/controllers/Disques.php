<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;
class Disques extends \_DefaultController {
	public function isValid(){
		return Auth::isAuth();
	}
	public function onInvalidControl(){
		$this->messageDanger("Vous devez vous connecté pour afficher cette page !",3000,false);
		exit();
	}
	public function __construct(){
		parent::__construct();
		$this->title="Disques";
		$this->model="Disque";
	}

	/**
	 * Affiche la liste des instances de la class du modèle associé $model
	 * @see BaseController::index()
	 */
	public function index($message=null){
		global $config;
		$baseHref=get_class($this);
		if(isset($message)){
			if(is_string($message)){
				$message=new DisplayedMessage($message);
			}
			$message->setTimerInterval($this->messageTimerInterval);
			$this->_showDisplayedMessage($message);
		}
		$objects=DAO::getAll($this->model, "idUtilisateur='".Auth::getUser()->getId()."'");
		$this->loadView("Disque/vObjects.html",array("objects"=>$objects,"model"=>$this->model,"config"=>$config,"baseHref"=>$baseHref));
	}
	
	public function addDisque ($id=NULL) {
		if(isset($_POST['submit'])){
		$nom = $_POST['nom'];
		if(!isset($_POST['idTarif'])){
			$this->messageDanger("Veuillez choisir un tarif");
			$this->frmAdd($nom);
		}elseif (!isset($_POST['idServices'])){
			$this->messageDanger("Veuillez choisir au moins un service et un tarif");
			$this->frmAdd($nom);
		}else {
			$cloud = $GLOBALS["config"]["cloud"];
			$dir = $cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin();
			if(!DirectoryUtils::existDir($dir)){
				DirectoryUtils::mkDir($dir);
				}
				
			$pathname = $cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin()."/".$_POST['nom'];
			if(DirectoryUtils::mkDir($pathname)){
				//insert disque
				$disque = new Disque();
				$disque->setUtilisateur(Auth::getUser());
				RequestUtils::setValuesToObject($disque,$_POST);
				foreach ($_POST['idServices'] as $numService){
					$service = DAO::getOne("service", $numService);
					$disque->addService($service);
						
				}
				if(DAO::insert($disque,true)){
					//$this->messageSuccess($disque->toString()." créé.");
					//$this->index();
					$idDisque=$disque->getId();
					//insert disque-tarif
					$DisqueTarif=new DisqueTarif();
					$DisqueTarif->setDisque(DAO::getOne("disque", $idDisque));
					$DisqueTarif->setTarif(DAO::getOne("tarif", $_POST["idTarif"]));
					RequestUtils::setValuesToObject($DisqueTarif,$_POST);				
					if(DAO::insert($DisqueTarif)){
						$this->messageSuccess("Le disque <u>".$disque->toString()."</u> a été créé");
						$this->forward(MyDisques::class);
					}else{
						$this->messageWarning("Impossible d'associer les services au disque ".$disque->toString());
					}
				}else{
					$this->messageWarning("Impossible d'inserer le disque ".$disque->toString());
				}
			}else{
					$this->messageWarning("Impossible de créer le dossier du disque ".$disque->toString());
				}
		}
		}else{
			$this->forward(MyDisques::class);
		}
	}
	public function PaiementDisque(){
		$prixTotal = 0;
		$services = [];
		$idUtilisateur = Auth::getUser()->getId();
		$cloud = $GLOBALS["config"]["cloud"];
		$pathname = $cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin()."/".$_POST['nom'];
		if(!DAO::getOne("disque"," nom = '".$_POST['nom']."' AND idUtilisateur = '".$idUtilisateur."'") && !DirectoryUtils::existDir($pathname)){
			if(isset($_POST['idTarif'])){
				$tarif = DAO::getOne("tarif", $_POST['idTarif']);
			}
			if(isset($_POST['idServices'])){
				foreach ($_POST['idServices'] as $num){
					
					$service = DAO::getOne("service", $num);
					
					$prixTotal = $prixTotal + $service->getPrix();
						array_push($services, $service);
					
				}
				$prixTotal = $prixTotal += $tarif->getPrix();
				$date=date('Y-m-d H:i:s');
				$nom = $_POST['nom'];
				$this->loadView("disque/vPaiementDisque.html",array(
						"nom"=>$nom,
						"tarif"=>$tarif,
						"services"=>$services,
						"prixTotal"=>$prixTotal,
						"date"=>$date,
				));
			
			}else {
				$this->messageDanger("Veuillez choisir au moins un service et un tarif");
				$this->frmAdd($_POST['nom']);
			}
		}else {
			$this->messageDanger("Le disque ".$_POST['nom']." existe déjà");
			$this->frmAdd($_POST['nom']);
		}
		
	}
	
	public function frmAdd($nom = NULL){
		$user=Auth::getUser();
		$tarifs=DAO::getAll("tarif");
		$services=DAO::getAll("Service");
		
		//$disabled="";
		$date=date('Y-m-d H:i:s');
		$this->loadView("disque/vAdd.html",array("nom"=>$nom,"services"=>$services,"tarifs"=>$tarifs,"user"=>$user,"date"=>$date));
	}
		
	public function frmUpdate($id=NULL){
		if(Auth::isAdmin() && DAO::getOne("disque", "id = '".$id."'") || DAO::getOne("disque"," id = '".$id."' AND idUtilisateur = '".Auth::getUser()->getId()."'")){
			$disque = $this->getInstance($id);
			$user=$disque->getUtilisateur();
			$this->loadView("disque/vUpdate.html",array("user"=>$user,"disque"=>$disque));
		}else{
			$this->messageDanger("Disque introuvable");
		}
	}
	
	public function updateDisque(){

		if(RequestUtils::isPost()){
			$className=$this->model;
			$object=new $className();
			$this->setValuesToObject($object);
			if(!DAO::getOne("disque"," nom = '".$_POST['nom']."' AND idUtilisateur = '".$_POST["id"]."'")){
				if($_POST["id"]){
					try{
						$cloud = $GLOBALS["config"]["cloud"];
						$user = DAO::getOne("utilisateur", $_POST['idUtilisateur']);
						$oldName = $cloud["root"].$cloud["prefix"].$user->getLogin()."/".$_POST['oldName'];
						$newName = $cloud["root"].$cloud["prefix"].$user->getLogin()."/".$_POST['nom'];
						if(rename($oldName, $newName)){
							DAO::update($object);
							$this->messageSuccess("Le disque ".$_POST['oldName'].($user->getLogin())." a été renommé ",5000,true);
							$this->onUpdate($object);
							$this->forward(MyDisques::class);
						}else{
							$this->messageDanger("Impossible de renommer le dossier");
							$this->forward(MyDisques::class);
						}
					}catch(\Exception $e){
						$this->messageDanger("Impossible de modifier l'instance de ".$this->model,"danger");
					}
				}
			}else {
				$this->messageDanger("Le disque ".$_POST['nom']." existe déjà");
				$this->frmUpdate($object->getId());
			}
		}
	}
	
	/**
	 * Supprime l'instance dont l'id est $id dans la BDD
	 * @param int $id
	 */
	public function delete($id){
		$disque = DAO::getOne("disque", $id);
		$cloud = $GLOBALS["config"]["cloud"];
		$dir = $cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin()."/".$disque->getNom();
		
		if(DirectoryUtils::deleteDir($dir)){

			try{
				$object=DAO::getOne($this->model, $id);
				if($object!==NULL){
					DAO::delete($object);
					$this->messageSuccess($this->model." `{$object->toString()}` supprimé(e)");
					$this->onDelete($object);
				}else{
					$this->messageWarning($this->model." introuvable","warning");
				}
			}catch(\Exception $e){
				$this->messageDanger("Impossible de supprimer l'instance de ".$this->model,"danger");
			}
			$this->forward(MyDisques::class);
					
		}else{
			$contact = '<b><a class="btn btn-success btn-sm" href="messages/contact">Contacter administrateur ?</a><b></div>';
			$this->messageWarning("Imposible de spprimer le dossier du disque <u>".$disque->toString()."</u> ! ".$contact);
			$this->forward(MyDisques::class);
		}
	}
	
	public function tarification($id=NULL){
		//$this->loadView("Disque/vTarification.html");
		$date=date('Y-m-d H:i:s');
		$disque = $this->getInstance($id);
		if(Auth::isAdmin() &&DAO::getOne("disque", $id) || DAO::getOne("disque", "id = '".$id."' AND idUtilisateur = '".Auth::getUser()->getId()."'")){
			$tarifDisque=$disque->getTarif();
			
			$tarifs=DAO::getAll("tarif");
			$this->loadView("Disque/vDisqueTarif.html",array("tarifs"=>$tarifs,"disque"=>$disque,"date"=>$date,"tarifDisque"=>$tarifDisque));
		}else{
			$this->messageDanger("Disque introuvable");
			$this->forward(MyDisques::class);
		}
	}
	
	public function choixTarif(){
		if(RequestUtils::isPost()){
			$DisqueTarif=new DisqueTarif();
			$DisqueTarif->setDisque(DAO::getOne("disque", $_POST["idDisque"]));
			$DisqueTarif->setTarif(DAO::getOne("tarif", $_POST["idTarif"]));
			$Tarif = DAO::getOne("tarif", $_POST["idTarif"]);
			$disque = DAO::getOne("disque", $_POST["idDisque"]);
			RequestUtils::setValuesToObject($DisqueTarif,$_POST);
			try{
				DAO::insert($DisqueTarif);
				$this->messageSuccess("Le tarif <u>`{$Tarif->toString()}`</u> a été attribué au disque <u>".$disque->toString()."</u>");
				$this->onAdd($DisqueTarif);
			}catch(\Exception $e){
				$this->messageDanger("Impossible d'ajouter l'instance de ".$this->model,"danger");
			}
		}
		$this->forward(MyDisques::class);
	}
	
	public function frmUpdateService ($id=NULL){
		$disque = $this->getInstance($id);
		if(Auth::isAdmin() &&DAO::getOne("disque", $id) || DAO::getOne("disque", "id = '".$id."' AND idUtilisateur = '".Auth::getUser()->getId()."'")){
			$disquServices = DAO::getManyToMany($disque, "services");
			$services=DAO::getAll("service");
			$this->loadView("Disque/vDisqueService.html",array("services"=>$services,"disqueServices"=>$disquServices,"disque"=>$disque));
		}else{
			$this->messageDanger("Disque introuvable");
			$this->forward(MyDisques::class);
		}
	}
	
	public function ajoutService($idDisk,$idServ){
		$disque = DAO::getOne("disque", $idDisk);
		$service = DAO::getOne("service", $idServ);
		$disqueServices = DAO::getManyToMany($disque, "services");
		if(!in_array($service, $disqueServices)){
			$disque->addService($service);
			DAO::insertOrUpdateAllManyToMany($disque);
			$this->messageSuccess("Le service <u>".$service->toString()."</u> a été ajouté pour le disque : <u>".$disque->toString()."</u>");
			$this->frmUpdateService($disque->getId());
		}else {
			$this->messageDanger("Le service <u>".$service->toString()."</u> est déjà associé au disque : <u>".$disque->toString()."</u>");
			$this->frmUpdateService($disque->getId());
		}
	}
	
	public function suppressionService($idDisk,$idServ){
		$disque = DAO::getOne("disque", $idDisk);
		$service = DAO::getOne("service", $idServ);
		DAO::getManyToMany($disque, "services");
		if($disque->removeService($idServ)){
			DAO::update($disque,true);
		}
		$this->messageSuccess("Le service <u>".$service->toString()."</u> a été supprimé pour le disque : <u>".$disque->toString()."</u>");
		$this->frmUpdateService($disque->getId());
	}
		
}

