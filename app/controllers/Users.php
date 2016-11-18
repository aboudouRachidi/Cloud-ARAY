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
use checkUsers;
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
		//$users=DAO::getAll($this->model);
		//$this->loadView("user/vAllusers.html",array("users"=>$users,"model"=>$this->model,"config"=>$config,"baseHref"=>$baseHref));
		$this->forward(Admin::class,"users");
		}else {
			$this->onInvalidControl();
		}
	}
	
	
	public function onInvalidControl(){
		if(Auth::isAuth()){
			$this->messageDanger("Vous n'êtes pas autorisé à afficher cette page !",8000,false);
			exit();
		}else{
		
			$this->messageDanger("Vous devez vous connecté pour afficher cette page !",8000,false);
			$this->loadView("main/vLogin.html");
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
	/**
	 * Met à jour à partir d'un post une instance de $className<br>
	 * L'affectation des membres de l'objet par le contenu du POST se fait par appel de la méthode setValuesToObject()
	 * @see _DefaultController::setValuesToObject()
	 */
	public function update(){
		if(RequestUtils::isPost()){
			$className=$this->model;
			$object=new $className();
			$this->setValuesToObject($object);
			if($_POST["id"]){
				$user = DAO::getOne("utilisateur", $_POST['id']);
				if($this->checkLogin() == false || $_POST['login'] == $user->getLogin()){
					if($this->checkMail() == false || $_POST['mail'] == $user->getMail()){
						try{
							DAO::update($object);
							$this->messageSuccess($this->model." `{$object->toString()}` mis à jour");
							$this->onUpdate($object);
							$this->vUser($_POST['id']);
						}catch(\Exception $e){
							$this->messageDanger("Impossible de modifier l'instance de ".$this->model,"danger");
							$this->index();
						}
					}else{
						$this->messageDanger("Cet adresse mail est déjà utilisé !");
						$this->frm($_POST['id']);
					}
				}else {
					$this->messageDanger("Ce login existe déjà !");
					$this->frm($_POST['id']);
				}
			}else{
				if($this->checkLogin() == false){
					if($this->checkMail() == false){
						try{
							DAO::insert($object);
							$this->messageSuccess("Instance de ".$this->model." `{$object->toString()}` ajoutée");
							$this->onAdd($object);
						}catch(\Exception $e){
							$this->messageDanger("Impossible d'ajouter l'instance de ".$this->model,"danger");
						}
					}else{
						$this->messageDanger("Cet adresse mail est déjà utilisé !");
					}
				}else {
					$this->messageDanger("Ce login existe déjà !");
				}
				$this->frm();
			}
		}
	}
	public function profil(){
		if(Auth::isAuth()){
			$idReceveur = Auth::getUser()->getId();
			$messages = DAO::getAll("message","idReceveur = '".$idReceveur."'ORDER BY date DESC");
			$user = DAO::getOne("Utilisateur", "id='".Auth::getUser()->getId()."'");
			$this->loadView("user/vProfil.html",array("user"=>$user,"messages"=>$messages));
			
			echo Jquery::getOn("click", ".n-message", "Messages/Contact","#divResponse");
			echo Jquery::getOn("click", ".s-message", "Messages/Envoyes","#divResponse");
			echo Jquery::getOn("click", ".c-updateUser", "Users/frmUpdate","#divResponse");
			echo Jquery::getOn("click", ".c-updatePass", "Users/frmUpdatePass","#divResponse");
		}else{
			$this->onInvalidControl();
		}
	}
	
	public function frmUpdate($id=NULL){
		if(Auth::isAuth()){
			$user=Auth::getUser();
			$disabled="";
			$this->loadView("user/vUpdate.html",array("user"=>$user,"disabled"=>$disabled));
		}else{
			$this->onInvalidControl();
		}
	}
	
	public function frmUpdatePass($id=NULL){
		if(Auth::isAuth()){
			$user=Auth::getUser();
			$disabled="";
			$this->loadView("user/vUpdatePass.html",array("user"=>$user,"disabled"=>$disabled));
		}else{
			$this->onInvalidControl();
		}
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
					$this->messageSuccess("Vos informations ont été mis à jour",5000,true);
					$this->onUpdate($object);
					$this->profil();
				}catch(\Exception $e){
					$this->messageWarning("Impossible de modifier l'instance de ".$this->model,"danger");
				}
			}
		}

	}
	
	public function vUser($id=NULL){
		$user = $this->getInstance($id);
		
		if(DAO::getOne("utilisateur", $id) && Auth::isAdmin()){
			$user->setNouveau(0);
			DAO::update($user);
		$disques = DAO::getAll("disque","idUtilisateur = ".$user->getId());
		foreach ($disques as $disque){
			DAO::getManyToMany($disque, "services");
		}

		$this->loadView("user/vUser.html",array("user"=>$user,"disques"=>$disques));
		}else{
			$this->messageDanger("Utilisateur introuvable");
			$this->onInvalidControl();
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