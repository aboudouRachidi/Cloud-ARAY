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
		//$id = $this->getInstance($id);
		$cloud = $GLOBALS["config"]["cloud"];
		$dir = $cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin();
		
		if(!DirectoryUtils::existDir($dir)){
			DirectoryUtils::mkDir($dir);
			}
			
		$pathname = $cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin()."/".$_POST['nom'];
		echo $pathname;
		
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
				$this->messageSuccess("ok");
				
				}
					

				
			}else{
				$this->messageWarning("Impossible d'inserer le disque ".$disque->toString());
			}
			
		}
	}
	
	public function addDir ($id=NULL) {
		
		$cloud = $GLOBALS["config"]["cloud"];
		$dir = $cloud["root"].$cloud["prefix"].Auth::getUser()->getNom();
		
		if(!DirectoryUtils::existDir($dir)){
			DirectoryUtils::mkDir($dir);
			
		}
		
		$pathname = $cloud["root"].$cloud["prefix"].Auth::getUser()->getNom()."/".$_POST['nom'];
			echo $pathname;
		if(DirectoryUtils::mkDir($pathname)){
			$this->messageSuccess(" créé.");
			
		}else{
			$this->messageWarning("Impossible d'inserer le disque ");
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
						DAO::update($object);
						$this->messageSuccess("<b>mis à jour</b>",5000,true);
						$this->onUpdate($object);
					$this->index();
					}catch(\Exception $e){
						$msg=new DisplayedMessage("Impossible de modifier l'instance de ".$this->model,"danger");
					}
				}
			}
		}
	
	public function Tarification($id=NULL){
		//$this->loadView("Disque/vTarification.html");
		$date=date('Y-m-d H:i:s');
		$disque = $this->getInstance($id);
		$tarifs=DAO::getAll("tarif");
		$this->loadView("Disque/vDisqueTarif.html",array("tarifs"=>$tarifs,"disque"=>$disque,"date"=>$date));
	}
	
	public function ChoixTarif(){
		
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
	
	
		
	}

