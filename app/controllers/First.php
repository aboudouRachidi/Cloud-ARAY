<?php
use micro\controllers\Controller;
use micro\orm\DAO;
use micro\js\Jquery;
use micro\utils\RequestUtils;

class First extends Controller{
	public function initialize(){
		if(RequestUtils::isAjax() == false)
		$this->loadView("main/vHeader.html");
	}
	public function finalize(){
		$this->loadView("main/vFooter.html");
	}
	public function index(){
		echo "Méthode Index First";
	}
	public function users($search=NULL){
		$were = "1=1";
		if(isset($search)){
			$were = "login like '%{$search}%' or mail like '%{$search}'"; 
		}
		$users = DAO::getAll("Utilisateur",$were);
		$this->loadView("First/users.html",array("users"=>$users));

		echo Jquery::getOn("click", ".c-user", "first/showUser","#divUser");
	}
	
	public function showUser($idUser){
		$user = DAO::getOne("Utilisateur",$idUser);
		if(is_null($user) == false){
			echo "Login : " .$user->getLogin()."<br>";
			echo "Mail : " .$user->getMail()."<br>";
		}else{
			echo "Aucun utilisateur correspondant à l'id {$idUser}";
		}
	}
	
	public function addUser(){
		if(RequestUtils::isPost()){
			$user = new Utilisateur();
			RequestUtils::setValuesToObject($user,$_POST);
			if(DAO::insert($user)){
				
				echo $user->toString()." créé.";
				
			}else{
				
				
				
			}
		}else{
			$this->loadView("First/addUser.html");
		}
	}
}