<?php
class Tarifs extends \_DefaultController{
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
		$this->title="Tarifs";
		$this->model="tarif";
	}
	
	public function frm($id=NULL){
		if(Auth::isAdmin()){
		$tarif = $this->getInstance($id);
		$this->loadView("Tarifs/edit.html",array("tarif"=>$tarif));
		}else {
			$this->onInvalidControl();
		}
		
	}
}