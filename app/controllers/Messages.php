<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;
use micro\js\Jquery;

class Messages extends \_DefaultController{

	public function isValid(){
		return Auth::isAuth();
	}
	public function onInvalidControl(){
		$this->messageDanger("Vous devez vous connecté pour afficher cette page !",3000,false);
		exit();
	}

	public function __construct(){
		parent::__construct();
		$this->title="Messages";
		$this->model="Message";
	}

	public function contact($id=NULL){
		$users = DAO::getAll("utilisateur","admin = 0");
		$user=Auth::getUser();
		$admins = DAO::getAll("utilisateur","admin = 1");
		$isAdmin = Auth::isAdmin();
		$date=date('Y-m-d H:i:s');
		$this->loadView("message/vContact.html",array("date"=>$date,"users"=>$users,"utilisateur"=>$user,"admins"=>$admins,"isAdmin"=>$isAdmin));
	}
	
	public function sentMessage(){
		if(isset($_POST['receveur'])){
			$idReceveur = $_POST['receveur'];
			$receveur = DAO::getOne("utilisateur",$idReceveur);
			$message = new Message();
			RequestUtils::setValuesToObject($message,$_POST);
			$message->setExpediteur(Auth::getUser());
			$message->setReceveur($receveur);
			$message->setLu(0);
			if(DAO::insert($message)){
				$this->messageSuccess("Le message ".$message->getObjet()." a été envoyé à ".$receveur,5000,true);
				$this->forward(Users::class,"profil");
			}else{
				$this->messageWarning("Impossible d'envoyer le message ".$message->getObjet());
				$this->forward(Users::class,"profil");
			}
		}else{
			$this->contact();
			
		}
	}
	
	public function updateMessage(){
		//$user = DAO::getOne("Utilisateur", "id='".Auth::getUser()->getId()."'");
		if(RequestUtils::isPost()){
			
			if($_POST["id"]){
				$message = DAO::getOne("message", $_POST["id"]);
				$message->setLu(1);
				try{
					DAO::update($message);
					$this->messageSuccess("Message lu",5000,true);
					$this->onUpdate($message);
					$this->vMessage($_POST["id"]);
				}catch(\Exception $e){
					$this->messageWarning("Une erreur est survenue !");
				}
			}
		}
	
	}
	
	public function vMessage($id=NULL){
		if(Auth::isAuth()){
			$idReceveur = Auth::getUser()->getId();
			if(Auth::isAdmin() || DAO::getOne("message", "id = '".$id."' AND idReceveur = '".$idReceveur."'")){
			$message = $this->getInstance($id);
			$message->setLu(1);
			DAO::update($message);
			$this->onUpdate($message);
			$this->loadView("message/vMessage.html",array("message"=>$message));
			echo Jquery::getOn("click", ".r-message", "Messages/repondre/".$id,"#divResponse");
			}else{
				$this->messageDanger("Message introuvable");
				$this->forward(Users::class,"profil");
			}
		}else {
			$this->onInvalidControl();
		}
	}
	
	public function envoyes($id=NULL){
		if(Auth::isAuth()){
			$messages = DAO::getAll("message","idExpediteur = '".Auth::getUser()->getId()."'ORDER BY date DESC");
			$this->loadView("message/vMessageEnvoyes.html",array("messages"=>$messages));
		}else {
			$this->onInvalidControl();
		}
	}
	public function repondre($id=NULL){
		if(Auth::isAuth()){
			$idReceveur = Auth::getUser()->getId();
			if(DAO::getOne("message", "id = '".$id."' AND idReceveur = '".$idReceveur."'")){
			$message = $this->getInstance($id);
			$date=date('Y-m-d H:i:s');
			$this->loadView("message/vReponse.html",array("date"=>$date,"message"=>$message));
			}else{
				$this->messageDanger("Message introuvable");
				$this->forward(Users::class,"profil");
			}
		}else {
			$this->onInvalidControl();
		}
	}
	public function vMessageEnvoyes($id=NULL){
		if(Auth::isAuth()){
			$idExpediteur = Auth::getUser()->getId();
			if(DAO::getOne("message", "id = '".$id."' AND idExpediteur = '".$idExpediteur."'")){
			$message = $this->getInstance($id);
			$this->loadView("message/vSentMessage.html",array("message"=>$message));
			}else{
				$this->messageDanger("Message introuvable");
				$this->forward(Users::class,"profil");
			}
		}else {
			$this->onInvalidControl();
		}
	}
}