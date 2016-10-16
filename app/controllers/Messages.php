<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;

class Messages extends \_DefaultController{

	public function isValid(){
		return Auth::isAuth();
	}
	public function onInvalidControl(){
		$this->messageDanger("Vous n'êtes pas autorisé à afficher cette page !",3000,false);
		exit();
	}
	
	public function __construct(){
		parent::__construct();
		$this->title="Messages";
		$this->model="message";
	}

	public function contact($id=NULL){
		$user=Auth::getUser();
		$disque = DAO::getOne("disque",$id);
		$messages = DAO::getOneToMany($disque, "messages");
		$date=date('Y-m-d H:i:s');
		$this->loadView("message/vContact.html",array("messages"=>$messages,"date"=>$date,"utilisateur"=>$user,"disque"=>$disque));
	}
	
	public function sentMessage(){
		$id = $_POST['idDisque'];
		$disque = DAO::getOne("disque",$id);
		$message = new Message();
		RequestUtils::setValuesToObject($message,$_POST);
		$message->setUtilisateur(Auth::getUser());
		$message->setDisque($disque);
		if(DAO::insert($message)){
			$this->messageSuccess($message->toString()." a été envoyé.");
			$this->forward(MyDisques::class);
		}else{
			$this->messageWarning("Impossible d'inserer le message ".$message->toString());
		}
	}
}