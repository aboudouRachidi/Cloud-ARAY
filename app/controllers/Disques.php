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
		$this->loadView("main/vObjects.html",array("objects"=>$objects,"model"=>$this->model,"config"=>$config,"baseHref"=>$baseHref));
	}
	public function addDisque ($id=NULL) {
		//$id = $this->getInstance($id);
		$disque = new Disque();
		$disque->setUtilisateur(Auth::getUser());
		RequestUtils::setValuesToObject($disque,$_POST);
		if(DAO::insert($disque)){
			$this->messageSuccess($disque->toString()." créé.");
			$this->index();
		}else{
			$this->messageWarning("Impossible d'inserer le disque ".$disque->toString());
		}
	}
	
	public function create(){
		$user=Auth::getUser();
		
		//$disabled="";
		$date=date('Y-m-d H:i:s');
		$this->loadView("disque/vAdd.html",array("user"=>$user,"date"=>$date));
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

		
	}

