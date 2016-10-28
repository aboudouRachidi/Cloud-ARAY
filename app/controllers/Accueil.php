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

			if(Auth::isAdmin()){
				$this->forward(Admin::class);
			}else {
				$this->loadView("main/vDefault.html");
			}
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
	
	/**
	 * permet de se connecter avec le couple email/mot de passe
	 */
	public function connect(){
		$isAjax=RequestUtils::isAjax();
		if(isset($_POST['mail'])&&$_POST['password']){
			$email = $_POST['mail'];
			$password = $_POST['password'];
			
			if($user = DAO::getOne("Utilisateur", "mail='".$email."' and password='".hash("sha256", $password)."'")){
			$_SESSION["user"] = $user;
			$_SESSION['KCFINDER'] = array(
					'mail' => $user->getMail(),
					'disabled' => true
			);
			$this->index();
			}else{
				$email = $_POST['mail'];
				if(!$isAjax){
					$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
				}
				$this->messageDanger("Email ou mot de passe incorrect",5000,true);
				$this->loadView("main/vLogin.html",array("email"=>$email));
				Jquery::getOn("click","a[data-ajax]","","#main",array("attr"=>"data-ajax"));
				echo Jquery::compile();
				if(!$isAjax){
					$this->loadView("main/vFooter.html");
				}
			}
			
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
		$isAjax=RequestUtils::isAjax();
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$login = $_POST['login'];
		$email = $_POST['mail'];
		if(RequestUtils::isPost()){
			
			if($this->checkMail()==false){
			if($this->checkLogin()==false){
			if ($this->checkPwd()==true){
				$user = new Utilisateur();
				RequestUtils::setValuesToObject($user,$_POST);
				
				if(DAO::insert($user)){
					$this->loadView("main/vHeader.html");
					$this->messageSuccess("Le compte de <strong>".$user->toString()."</strong> a été créer ! vous pouvez maitenant vous connecté avec vos identifiants.");
					$this->loadView("main/vLogin.html",array("email"=>$email));
					$this->loadView("main/vFooter.html");
				}else{
					$this->loadView("main/vHeader.html");
					$this->messageWarning("Impossible d\'inserer l\'utilisateur ".$user->toString());
					$this->loadView("main/vLogin.html");
					$this->loadView("main/vFooter.html");
				}
			}else{
				if(!$isAjax){
					$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
				}
				$this->messageDanger("Les deux mots de passes ne conrrespondent pas ...",5000);
				$this->loadView("main/vLogin.html",array("email"=>$email,"nom"=>$nom,"prenom"=>$prenom,"login"=>$login));
				Jquery::getOn("click","a[data-ajax]","","#main",array("attr"=>"data-ajax"));
				echo Jquery::compile();
				if(!$isAjax){
					$this->loadView("main/vFooter.html");
			}
				
			}
				
			}else{
				if(!$isAjax){
					$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
				}
				$this->messageDanger("Ce login est deja utilisé...",5000);
				$this->loadView("main/vLogin.html",array("email"=>$email,"nom"=>$nom,"prenom"=>$prenom,"login"=>$login));
				Jquery::getOn("click","a[data-ajax]","","#main",array("attr"=>"data-ajax"));
				echo Jquery::compile();
				if(!$isAjax){
					$this->loadView("main/vFooter.html");}
			}
			}else{
				if(!$isAjax){
					$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
				}
				$this->messageDanger("Cet adresse e-mail est deja utilisé ...",5000);
				$this->loadView("main/vLogin.html",array("email"=>$email,"nom"=>$nom,"prenom"=>$prenom,"login"=>$login));
				Jquery::getOn("click","a[data-ajax]","","#main",array("attr"=>"data-ajax"));
				echo Jquery::compile();
				if(!$isAjax){
					$this->loadView("main/vFooter.html");}
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