<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;
use micro\js\Jquery;
/**
 * Gestion des users
 * @author jcheron
 * @version 1.1
 * @package nas.controllers
 */
class Users extends \_DefaultController {

	public function __construct(){
		parent::__construct();
		$this->title="Utilisateurs";
		$this->model="Utilisateur";
	}
	/**
	 * Affiche la liste des instances de la class du modèle associé $model
	 * @see BaseController::index()
	 */
	public function index($message=null){
		if(Auth::isAdmin()){
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
		$this->loadView("user/vObjects.html",array("objects"=>$objects,"model"=>$this->model,"config"=>$config,"baseHref"=>$baseHref));
	
		}else {
			$this->onInvalidControl();
		}
	}
	
	
	public function onInvalidControl(){
		if(Auth::isAuth()){
			$this->messageDanger("Vous n'êtes pas autorisé à afficher cette page !",8000,false);
			exit();
		}else{
		$isAjax=RequestUtils::isAjax();
		if(!$isAjax){
				$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
			}
			$this->messageDanger("Vous n'êtes pas autorisé à afficher cette page !",8000,false);
			$this->loadView("main/vLogin.html");
			Jquery::getOn("click","a[data-ajax]","","#main",array("attr"=>"data-ajax"));
			echo Jquery::compile();
			if(!$isAjax){
				$this->loadView("main/vFooter.html");
			}
		exit();
		}
	}
	public function frm($id=NULL){
		if(Auth::isAdmin()){
		$user=$this->getInstance($id);
		$disabled="";
		$this->loadView("user/vAdd.html",array("user"=>$user,"disabled"=>$disabled));
		}else{
			$this->onInvalidControl();
		}
	}

	public function profil(){
		$user = DAO::getOne("Utilisateur", "id='".Auth::getUser()->getId()."'");
		$this->loadView("user/vProfil.html",array("user"=>$user));
	}
	
	/**
	 * Met à jour à partir d'un post une instance de $className<br>
	 * L'affectation des membres de l'objet par le contenu du POST se fait par appel de la méthode setValuesToObject()
	 * @see _DefaultController::setValuesToObject()
	 */
	public function updateProfil(){
		//$user = DAO::getOne("Utilisateur", "id='".Auth::getUser()->getId()."'");
		if(RequestUtils::isPost()){
			$className=$this->model;
			$object=new $className();
			$this->setValuesToObject($object);
			if($_POST["id"]){
				try{
					DAO::update($object);
					$this->messageSuccess("<b>Vos informations ont été mis à jour</b>",5000,true);
					$this->onUpdate($object);
					$this->profil();
				}catch(\Exception $e){
					$this->messageWarning("Impossible de modifier l'instance de ".$this->model,"danger");
				}
			}
		}
	}
	
	/* (non-PHPdoc)
	 * @see _DefaultController::setValuesToObject()
	 */
	protected function setValuesToObject(&$object) {
		parent::setValuesToObject($object);
		$object->setAdmin(isset($_POST["admin"]));
	}

	public function tickets(){
		$this->forward("tickets");
	}
}