<?php
use micro\utils\RequestUtils;
use micro\orm\DAO;

class Tarifs extends \_DefaultController{
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
		$this->title="Tarifs";
		$this->model="tarif";
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
		$objects=DAO::getAll($this->model);
		$this->loadView("Tarifs/vObjects.html",array("objects"=>$objects,"model"=>$this->model,"config"=>$config,"baseHref"=>$baseHref));
	}
	
	public function frm($id=NULL){
		if(Auth::isAdmin()){
		$this->loadView("Tarifs/vAdd.html");
		}else {
			$this->onInvalidControl();
		}
		
	}
	
	public function addTarif(){
		if(RequestUtils::isPost()){
			$className=$this->model;
			$object=new $className();
			$this->setValuesToObject($object);
			if($_POST["unite"] != ""){
				try{
					DAO::insert($object);
					$this->messageSuccess("Instance de ".$this->model." `{$object->toString()}` ajoutée");
					$this->onAdd($object);
					$this->index();
				}catch(\Exception $e){
					$this->messageDanger("Impossible d'ajouter l'instance de ".$this->model,"danger");
				}
			}else{
				$this->messageDanger("Veuillez choisir une unité !");
				$this->loadView("Tarifs/vAdd.html",array("quota"=>$_POST['quota'],"prix"=>$_POST['prix'],"margeDepassement"=>$_POST['margeDepassement'],"coutDepassement"=>$_POST['coutDepassement']));
			}
			//$this->_postUpdateAction($msg);
		}
	}
	
	public function frmUpdate($id=NULL){
		if(Auth::isAdmin()){
			$tarif = $this->getInstance($id);
			$this->loadView("Tarifs/edit.html",array("tarif"=>$tarif));
		}else {
			$this->onInvalidControl();
		}
	}
	
	public function updateTarif(){
		if(isset($_POST["avertir"])){
			$users = DAO::getAll("utilisateur","admin = 0");
			foreach ($users as $user){
				$receveur = DAO::getOne("utilisateur",$user->getId());
		
				$message = new Message();
		
				$message->setObjet("Mis à jour tarif ".$_POST['oldPrix'] . " €/".$_POST['oldQuota'].$_POST['oldUnite']);
				$message->setContenu("Le tarif '".$_POST['oldPrix']." € ' associé au quota '".$_POST['oldQuota'].$_POST['oldUnite']."' a été mis à jour : Prix : ".$_POST['prix']."€ Quota : ".$_POST['quota']." ".$_POST['unite'] ." Marge de dépassement : ".($_POST['margeDepassement'] * 100)."% Coût de dépassement : ".$_POST['coutDepassement']."€.");
				$message->setExpediteur(Auth::getUser());
				$message->setReceveur($receveur);
				$message->setLu(0);
		
				DAO::insert($message);
			}
		}
		if(RequestUtils::isPost()){
			$className=$this->model;
			$object=new $className();
			$this->setValuesToObject($object);
			if($_POST["id"]){
				try{
					DAO::update($object);
					$this->messageSuccess($this->model." `{$object->toString()}` mis à jour");
					$this->onUpdate($object);
					$this->index();
				}catch(\Exception $e){
					$this->messageDanger("Impossible de modifier l'instance de ".$this->model,"danger");
				}
			}else {
				$this->messageDanger("Erreur ! instance non trouvé");
				$this->index();
			}
		}
	}
}