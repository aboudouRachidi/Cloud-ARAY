<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;

class Services extends \_DefaultController{
	public function isValid(){
			if (Auth::isAdmin() && Auth::isAuth())
		return true;
	}
	public function onInvalidControl(){
		$this->messageDanger("Vous n'êtes pas autorisé à afficher cette page !",3000,false);
		exit();
	}
	public function __construct(){
		parent::__construct();
		$this->title="Services";
		$this->model="service";
	}

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
		$objects=DAO::getAll("service");
		$this->loadView("Service/vObjects.html",array("objects"=>$objects,"model"=>$this->model,"config"=>$config,"baseHref"=>$baseHref));
	}
	
	public function addService ($id=NULL) {
		//$id = $this->getInstance($id);
		$service = new Service();
		RequestUtils::setValuesToObject($service,$_POST);
		if(DAO::insert($service)){
			$this->messageSuccess($service->toString()." créé.");
			$this->index();
		}else{
			$this->messageWarning("Impossible d'inserer le disque ".$service->toString());
		}
	}
	
	public function create(){

		$this->loadView("service/vAdd.html");
	}
	
	
	public function frm($id=NULL){

		$service = $this->getInstance($id);
	
		$this->loadView("service/vUpdate.html",array("service"=>$service));
	}
	
	public function updateService(){
			
		if(RequestUtils::isPost()){
			$Service=$this->model;
			$service=new $Service();
			$this->setValuesToObject($service);
			if($_POST["id"]){
				try{
					DAO::update($service);
					$this->messageSuccess("<b>mis à jour</b>",5000,true);
					$this->onUpdate($service);
					$this->index();
				}catch(\Exception $e){
					$msg=new DisplayedMessage("Impossible de modifier l'instance de ".$this->model,"danger");
				}
			}
		}
	}
}