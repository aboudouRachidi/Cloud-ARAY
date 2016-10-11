<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;
class Disques extends \_DefaultController {

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
				$this->messageSuccess($disque->toString()." créé.");
				//$this->index();
				$idDisque=$disque->getId();
				//insert disque-tarif
				$DisqueTarif=new DisqueTarif();
				$DisqueTarif->setDisque(DAO::getOne("disque", $idDisque));
				$DisqueTarif->setTarif(DAO::getOne("tarif", $_POST["idTarif"]));
				RequestUtils::setValuesToObject($DisqueTarif,$_POST);				
				if(DAO::insert($DisqueTarif)){
					$this->messageSuccess("Le disque".$disque->toString()." a été créé");
					$this->index();
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
	
	public function create(){
		$user=Auth::getUser();
		$tarifs=DAO::getAll("tarif");
		$services=DAO::getAll("Service");
		//$disabled="";
		$date=date('Y-m-d H:i:s');
		$this->loadView("disque/vAdd.html",array("services"=>$services,"tarifs"=>$tarifs,"user"=>$user,"date"=>$date));
	}
		
	public function frm($id=NULL){
		$user=Auth::getUser();
		$disque = $this->getInstance($id);
		$this->loadView("disque/vUpdate.html",array("user"=>$user,"disque"=>$disque));
	}
	
	public function updateDisque(){
		if(RequestUtils::isPost()){
			$className=$this->model;
			$object=new $className();
			$this->setValuesToObject($object);
			if($_POST["id"]){
				try{
					$cloud = $GLOBALS["config"]["cloud"];
					$oldName = $cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin()."/".$_POST['oldName'];
					$newName = $cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin()."/".$_POST['nom'];
					if(rename($oldName, $newName)){
						DAO::update($object);
						$this->messageSuccess("Le disque ".$_POST['oldName']." a été renommé ",5000,true);
						$this->onUpdate($object);
						$this->updateService($object->getId());
					}else{
						$this->messageDanger("Impossible de renommer le dossier");
						$this->updateService($object->getId());
					}
				}catch(\Exception $e){
					$this->messageDanger("Impossible de modifier l'instance de ".$this->model,"danger");
				}
			}
		}
	}
	
	public function tarification($id=NULL){
		//$this->loadView("Disque/vTarification.html");
		$date=date('Y-m-d H:i:s');
		$disque = $this->getInstance($id);
		$tarifs=DAO::getAll("tarif");
		$this->loadView("Disque/vDisqueTarif.html",array("tarifs"=>$tarifs,"disque"=>$disque,"date"=>$date));
	}
	
	public function choixTarif(){
		if(RequestUtils::isPost()){
			$DisqueTarif=new DisqueTarif();
			$DisqueTarif->setDisque(DAO::getOne("disque", $_POST["idDisque"]));
			$DisqueTarif->setTarif(DAO::getOne("tarif", $_POST["idTarif"]));
			RequestUtils::setValuesToObject($DisqueTarif,$_POST);
			try{
				DAO::insert($DisqueTarif);
				$msg=new DisplayedMessage("Instance de ".$this->model." `{$DisqueTarif->toString()}` ajoutée");
				$this->onAdd($DisqueTarif);
			}catch(\Exception $e){
				$msg=new DisplayedMessage("Impossible d'ajouter l'instance de ".$this->model,"danger");
			}
		}
		$this->_postUpdateAction($msg);
	}
	
	public function updateService ($id=NULL){
		$disque = $this->getInstance($id);
		$disquServices = DAO::getManyToMany($disque, "services");
		$services=DAO::getAll("service");
		$this->loadView("Disque/vDisqueService.html",array("services"=>$services,"disqueServices"=>$disquServices,"disque"=>$disque));
	}
	
	public function ajoutService($idDisk,$idServ){
		$disque = DAO::getOne("disque", $idDisk);
		$service = DAO::getOne("service", $idServ);
		$disqueServices = DAO::getManyToMany($disque, "services");
		if(!in_array($service, $disqueServices)){
			$disque->addService($service);
			DAO::insertOrUpdateAllManyToMany($disque);
			$this->messageSuccess("Le service <u>".$service->toString()."</u> a été ajouté pour le disque : <u>".$disque->toString()."</u>");
			$this->updateService($disque->getId());
		}else {
			$this->messageDanger("Le service <u>".$service->toString()."</u> est déjà associé au disque : <u>".$disque->toString()."</u>");
			$this->updateService($disque->getId());
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
		$this->updateService($disque->getId());
	}
		
}

