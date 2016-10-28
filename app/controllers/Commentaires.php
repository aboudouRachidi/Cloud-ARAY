<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;

class Commentaires extends \_DefaultController{

	public function isValid(){
		return Auth::isAuth();
	}
	public function onInvalidControl(){
		$this->messageDanger("Vous devez vous connecté pour afficher cette page !",3000,false);
		exit();
	}
	
	public function __construct(){
		parent::__construct();
		$this->title="Commentaires";
		$this->model="Commentaire";
	}

	public function comments($id=NULL){
		
		$user=Auth::getUser();
		$disque = DAO::getOne("disque",$id);
		$commentaires = DAO::getOneToMany($disque,"commentaires");
		if(DAO::getOne("disque", "id = '".$disque->getId()."' AND idUtilisateur = '".$user->getId()."'")){
			$date=date('Y-m-d H:i:s');
			$this->loadView("commentaire/vDisqueComments.html",array("commentaires"=>$commentaires,"date"=>$date,"utilisateur"=>$user,"disque"=>$disque));
		}else {
			$this->messageDanger("Ce disque n'existe pas");
			$this->forward(MyDisques::class);
		}
		
	}
	
	public function sentComment(){
		if(isset($_POST['contenu'])){
		$id = $_POST['idDisque'];
		$disque = DAO::getOne("disque",$id);
		$commentaire = new Commentaire();
		RequestUtils::setValuesToObject($commentaire,$_POST);
		$commentaire->setUtilisateur(Auth::getUser());
		$commentaire->setDisque($disque);
		if(DAO::insert($commentaire)){
			$this->messageSuccess($commentaire->toString()." a été ajouté.");
			$this->forward(MyDisques::class);
		}else{
			$this->messageWarning("Impossible d'inserer le message ".$commentaire->toString());
		}
		}else{
			$this->forward(MyDisques::class);
		}
	}
}