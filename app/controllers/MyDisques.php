<?php
use micro\controllers\Controller;
use micro\js\Jquery;
use micro\utils\RequestUtils;
use micro\orm\DAO;
class MyDisques extends Controller{
	
	public function __construct(){
		parent::__construct();
		$this->title="MyDisques";
		$this->model="Disque";
	}
	
	public function initialize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
		}
	}
	public function index($message=Null) {
		echo Jquery::compile();
		
		$utilisateur=Auth::getUser();
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
		$this->loadView("MyDisque/vObjects.html",array("objects"=>$objects,"model"=>$this->model,"config"=>$config,"baseHref"=>$baseHref,"utilisateur"=>$utilisateur));
		
	}
	
	public function create(){
		$user=Auth::getUser();
		$tarifs=DAO::getAll("tarif");
		$services=DAO::getAll("Service");
		//$disabled="";
		$date=date('Y-m-d H:i:s');
		$this->loadView("disque/vAdd.html",array("services"=>$services,"tarifs"=>$tarifs,"user"=>$user,"date"=>$date));
	}
	
	/**
	 * Supprime l'instance dont l'id est $id dans la BDD
	 * @param int $id
	 */
	public function delete($id){
		try{
			$object=DAO::getOne($this->model, $id);
			if($object!==NULL){
				DAO::delete($object);
				echo ($this->model." `{$object->toString()}` supprimé(e)");
				$this->onDelete($object);
			}else{
				echo ($this->model." introuvable");
			}
		}catch(\Exception $e){
			echo ("Impossible de supprimer l'instance de ".$this->model);
		}
		
	}

	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}

}