<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;

class Services extends \_DefaultController{
	public function isValid(){
			if (Auth::isAdmin() && Auth::isAuth())
		return true;
	}
	public function onInvalidControl(){
		$this->messageDanger("Vous n'êtes pas autorisé à afficher cette page !",8000,false);
		$this->forward(Accueil::class);
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
			$this->messageSuccess("Le service ".$service->toString()." a été créé.");
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
		if(isset($_POST["avertir"])){
			$users = DAO::getAll("utilisateur","admin = 0");
			foreach ($users as $user){
				$receveur = DAO::getOne("utilisateur",$user->getId());
				
				$message = new Message();
				
				$message->setObjet("Mis à jour service ".$_POST['oldNom']);
				$message->setContenu("Le sevice '".$_POST['oldNom']."' a été mis à jour : Nom : ".$_POST['nom']." Description : ".$_POST['description']." Prix : ".$_POST['prix'] ."€");
				$message->setExpediteur(Auth::getUser());
				$message->setReceveur($receveur);
				$message->setLu(0);
				
				DAO::insert($message);
			}
		}
		if(RequestUtils::isPost()){
			$Service=$this->model;
			$service=new $Service();
			$this->setValuesToObject($service);
			if($_POST["id"]){
				try{
					DAO::update($service);
					$this->messageSuccess("Le service <b>".$_POST['nom']."</b> a été mis à jour",5000,true);
					$this->onUpdate($service);
					$this->index();
				}catch(\Exception $e){
					$this->messageDanger("Impossible de modifier l'instance de ".$this->model,"danger");
				}
			}
		}
	}
}