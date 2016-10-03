<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;
class Disques extends \_DefaultController {

	public function __construct(){
		parent::__construct();
		$this->title="Disques";
		$this->model="Disque";
	}
	
	public function addDisque ($id=NULL) {
		//$id = $this->getInstance($id);
		$disque = new Disque();
		$disque->setUtilisateur(Auth::getUser());
		RequestUtils::setValuesToObject($disque,$_POST);
		if(DAO::insert($disque)){
			$this->messageSuccess($disque->toString()." créé.");
			
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
		

		
	}

