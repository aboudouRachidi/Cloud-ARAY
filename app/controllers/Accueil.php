<?php
use micro\orm\DAO;
use micro\js\Jquery;
use micro\controllers\Controller;
use micro\utils\RequestUtils;
/**
 * Contrôleur par défaut (défini dans config => documentRoot)
 * @author jcheron
 * @version 1.2
 * @package cloud.controllers
 */
class Accueil extends Controller {
	use MessagesTrait;
	/**
	 * Affiche la page par défaut du site
	 * @see BaseController::index()
	 */
	public function index() {
		$isAjax=RequestUtils::isAjax();
		if(Auth::isAuth()){
			if(!$isAjax){
				$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
			}
			$user = var_dump($_SESSION);
			$this->loadView("main/vDefault.html");
	    	Jquery::getOn("click","a[data-ajax]","","#main",array("attr"=>"data-ajax"));
			echo Jquery::compile();
			if(!$isAjax){
				$this->loadView("main/vFooter.html");
			}
		}else{
			if(!$isAjax){
				$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
			}
			$this->loadView("main/vLogin.html");
			Jquery::getOn("click","a[data-ajax]","","#main",array("attr"=>"data-ajax"));
			echo Jquery::compile();
			if(!$isAjax){
				$this->loadView("main/vFooter.html");
			}
		}
	}

	public function login($message){
		$isAjax=RequestUtils::isAjax();
		if(!$isAjax){
			$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
		}
		$this->loadView("main/vLogin.html",array("message"=>$message));
		Jquery::getOn("click","a[data-ajax]","","#main",array("attr"=>"data-ajax"));
		echo Jquery::compile();
		if(!$isAjax){
			$this->loadView("main/vFooter.html");
		}
	}
	
	/**
	 * permet de se connecter avec le couple email/mot de passe
	 */
	public function connect(){
		$isAjax=RequestUtils::isAjax();
		if(isset($_POST['mail'])&&$_POST['password']){
			$email = $_POST['mail'];
			$password = $_POST['password'];
			
			$user = DAO::getOne("Utilisateur", "mail='".$email."' and password='".$password."'");
			$_SESSION["user"] = $user;
			$_SESSION['KCFINDER'] = array(
					'mail' => $user->getMail(),
					'disabled' => true
			);
			$this->index();
		}else {
			
			if(!$isAjax){
				$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
			}
				$this->loadView("main/vLogin.html");
				Jquery::getOn("click","a[data-ajax]","","#main",array("attr"=>"data-ajax"));
				echo Jquery::compile();
				if(!$isAjax){
					$this->loadView("main/vFooter.html");
			}
		}
	}
	/**
	 * Verifie si le login et l'e-mail ne sont pas utilisé
	 * @return boolean
	 */
	public function checkLogin(){
		
		if ($_POST["login"]){
			if (DAO::getOne("Utilisateur", "login='".$_POST["login"]."' ")){
				return true;
			}
			
		}
	}
	/**
	 * Verifie si l'addresse e-mail n'est pas deja utilisé
	 */
	public function checkMail(){
		if ($_POST["mail"]){
			if (DAO::getOne("Utilisateur", "mail='".$_POST["mail"]."' ")){
				return true;
			}
				
		}
	}
	
	/**
	 * Verifie si les deux mots de passes sont identiques.
	 * @return boolean
	 */
	public function checkPwd(){
		if($_POST["password"]==$_POST["confirmPassword"]){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * permet de s'inscrire
	 */
	public function register(){
		
		if(RequestUtils::isPost()){
			if($this->checkMail()==false){
			if($this->checkLogin()==false){
			if ($this->checkPwd()==true){
				$user = new Utilisateur();
				RequestUtils::setValuesToObject($user,$_POST);
				
				if(DAO::insert($user)){
					echo $user->toString()." créé.";
					$this->index();
				}else{
					$this->index();
				}
			}else{
				$message = $this->messageWarning("Les deux mots de passes ne conrrespondent pas ...",4000,true,true);
				$this->login(array("message"=>$message));
				
			}
				
			}else{
				echo "Ce login est deja utilisé...";
			}
			}else{
				echo "Cette addresse e-mail est deja utilisé ...";
			}	
		}
	}
	
	/**
	 * Affiche la page de test
	 */
	public function test() {
		$this->loadView("main/vTest");
	}
	/**
	 * Connecte le premier administrateur trouvé dans la BDD
	 */
	public function asAdmin(){
		$_SESSION["user"]=DAO::getOne("Utilisateur", "admin=1");
		$this->index();
	}

	/**
	 * Connecte le premier utilisateur (non admin) trouvé dans la BDD
	 */
	public function asUser(){
		$_SESSION["user"]=DAO::getOne("Utilisateur", "admin=0");
		$this->index();
	}

	/**
	 * Déconnecte l'utilisateur actuel
	 */
	public function disconnect(){
		if(array_key_exists("autoConnect", $_COOKIE)){
			unset($_COOKIE['autoConnect']);
			setcookie("autoConnect", "", time()-3600,"/");
		}
		$_SESSION = array();
		$_SESSION['KCFINDER'] = array(
				'disabled' => true
		);
		$this->index();
	}
	

	public function getInfoUser(){
		echo Auth::getInfoUser();
	}
}