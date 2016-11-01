<?php
use micro\orm\DAO;
use micro\utils\RequestUtils;
use micro\js\Jquery;

class Recherches extends \_DefaultController{
	public function isValid(){
			if (Auth::isAdmin() && Auth::isAuth())
		return true;
	}
	public function onInvalidControl(){
		$this->messageDanger("Vous n'êtes pas autorisé à afficher cette page !",8000,false);
		$this->forward(Accueil::class);
		exit();
		
	}
	public function __construct(){
		parent::__construct();
		$this->title="Recherches";
		$this->model="recherche";
	}

	public function index(){

		$this->loadView("recherche/vSearch.html",array(
				"activeHome"=>"active",
				"nbNouveauUsers"=>DAO::count("utilisateur","nouveau = 1"),
				"nbUsers"=>DAO::count("utilisateur"),
				"nbNouveauDisques"=>DAO::count("disque","nouveau = 1"),
				"nbDisques"=>DAO::count("disque"),
				"nbMessages"=>DAO::count("message"),
				"nbServices"=>DAO::count("service"),
				"nbTarifs"=>DAO::count("tarif"),
				));
	}
	
	
	public function resultUsers($search=NULL){
		if(isset($_POST['submit'])){
		$were = "1=1";
		if(isset($_POST['search'])){
			$search = $_POST['search'];
			$were = "login like '%{$search}%' or mail like '%{$search}'";
			$results = DAO::getAll("Utilisateur",$were);
		}
		if($_POST['users'] == "all"){
			$results = DAO::getAll("Utilisateur");
		}
		if ($_POST['users'] == "nouveau"){
			$results = DAO::getAll("Utilisateur","nouveau = 1");
		}
		
		$this->loadView("recherche/vSearch.html",array(
				"activeHome"=>"active",
				"controller"=>"Users",
				"method"=>"vUser",
				"results"=>$results,
				"search"=>$search,
				"nbNouveauUser"=>DAO::count("utilisateur","nouveau = 1"),
				"nbUser"=>DAO::count("utilisateur"),
				"nbNouveauDisques"=>DAO::count("disque","nouveau = 1"),
				"nbDisques"=>DAO::count("disque"),
				"nbMessage"=>DAO::count("message"),
				"nbServices"=>DAO::count("service"),
				"nbTarifs"=>DAO::count("tarif"),
		));

		}else{
			$this->index();
		}
		
	}
	
	public function resultDisques($search=NULL){
		if(isset($_POST['submit'])){

			$were = "1=1";
			if(isset($_POST['search'])){
				$search = $_POST['search'];
				$were = "nom like '%{$search}%'";
				$results = DAO::getAll("disque",$were);
			}
			if($_POST['disques'] == "all"){
				$results = DAO::getAll("disque");
			}
			if ($_POST['disques'] == "nouveau"){
				$results = DAO::getAll("disque","nouveau = 1");
			}
	
			$this->loadView("recherche/vSearch.html",array(
					"activeDisques"=>"active",
					"controller"=>"Scan",
					"method"=>"show",
					"results"=>$results,
					"search"=>$search,
					"nbNouveauUser"=>DAO::count("utilisateur","nouveau = 1"),
					"nbUser"=>DAO::count("utilisateur"),
					"nbNouveauDisques"=>DAO::count("disque","nouveau = 1"),
					"nbDisques"=>DAO::count("disque"),
					"nbMessage"=>DAO::count("message"),
					"nbServices"=>DAO::count("service"),
					"nbTarifs"=>DAO::count("tarif"),
			));

		}else{
			$this->index();
		}
	
	}
	
	public function resultMessages($search=NULL){
		if(isset($_POST['submit'])){
			$were = "1=1";
			if(isset($_POST['searchObjet'])){
				$search = $_POST['searchObjet'];
				$were = "objet like '%{$search}%'";
				$results = DAO::getAll("message",$were);
			}
			if($_POST['messages'] == "all"){
				$results = DAO::getAll("message");
			}
			if($_POST['messages'] == "lu"){
				$results = DAO::getAll("message","lu = 1");
			}
			if ($_POST['messages'] == "nonLu"){
				$results = DAO::getAll("message","lu = 0");
			}
			if ($_POST['dateDebut']  && $_POST['dateFin'] ){
				$dateDebut = $_POST['dateDebut'];
				$dateFin = $_POST['dateFin'];
				$were = "DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '{$dateDebut}' AND '{$dateFin}'";
				$results = DAO::getAll("message",$were);
			}
	
			$this->loadView("recherche/vSearch.html",array(
					"activeMessages"=>"active",
					"controller"=>"Messages",
					"method"=>"vMessage",
					"results"=>$results,
					"searchObjet"=>$search,
					"nbNouveauUser"=>DAO::count("utilisateur","nouveau = 1"),
					"nbUser"=>DAO::count("utilisateur"),
					"nbNouveauDisques"=>DAO::count("disque","nouveau = 1"),
					"nbDisques"=>DAO::count("disque"),
					"nbMessagelu"=>DAO::count("message","lu = 1"),
					"nbMessage"=>DAO::count("message"),
					"nbServices"=>DAO::count("service"),
					"nbTarifs"=>DAO::count("tarif"),
			));

		}else{
			$this->index();
		}
	
	}
	
	public function resultServices($search=NULL){
		if(isset($_POST['submit'])){
			$were = "1=1";
			if($_POST['searchService'] !== ""){
				$_POST['montantMin']="";
				$_POST['montantMax'] = "";
				$search = $_POST['searchService'];
				$were = "nom like '%{$search}%'";
				$results = DAO::getAll("service",$were);
			}
			if($_POST['services'] == "all"){
				$_POST['montantMin']="";
				$_POST['montantMax'] = "";
				$results = DAO::getAll("Service");
			}
			
			if($_POST['services'] == "gratuit"){
				$_POST['montantMin']="";
				$_POST['montantMax'] = "";
				$results = DAO::getAll("service","prix = 0");
			}
			
			if($_POST['montantMin'] && $_POST['montantMax'] ){
				$montantMin = $_POST['montantMin'];
				$montantMax = $_POST['montantMax'];
				$were = "prix BETWEEN '{$montantMin}' AND '{$montantMax}'";
				$results = DAO::getAll("service",$were);
			}elseif($_POST['montantMax'] ){
				$montantMax = $_POST['montantMax'];
				$were = "prix = '{$montantMax}'";
				$results = DAO::getAll("service",$were);
			}elseif($_POST['montantMin']){
				$montantMin = $_POST['montantMin'];
				$were = "prix = '{$montantMin}'";
				$results = DAO::getAll("service",$were);
			}
	
			$this->loadView("recherche/vSearch.html",array(
					"activeServices"=>"active",
					"controller"=>"Services",
					"method"=>"frm",
					"results"=>$results,
					"searchService"=>$search,
					"montantMin"=>$_POST['montantMin'],
					"montantMax"=>$_POST['montantMax'],
					"nbNouveauUser"=>DAO::count("utilisateur","nouveau = 1"),
					"nbUser"=>DAO::count("utilisateur"),
					"nbNouveauDisques"=>DAO::count("disque","nouveau = 1"),
					"nbDisques"=>DAO::count("disque"),
					"nbMessage"=>DAO::count("message"),
					"nbServices"=>DAO::count("service"),
					"nbTarifs"=>DAO::count("tarif"),
			));
	
		}else{
			$this->index();
		}
	}
	
	public function resultTarifs($search=NULL){
		if(isset($_POST['submit'])){
			$were = "1=1";
			if(isset($_POST['searchMontant'])){
				$search = $_POST['searchMontant'];
				$were = "prix = '{$search}'";
				$results = DAO::getAll("tarif",$were);
			}
			if($_POST['tarifs'] == "all"){
				$results = DAO::getAll("tarif");
			}
			if($_POST['quotaMin'] && $_POST['quotaMax'] ){
				$quotatMin = $_POST['quotaMin'];
				$quotaMax = $_POST['quotaMax'];
				$were = "quota BETWEEN '{$quotatMin}' AND '{$quotaMax}'";
				$results = DAO::getAll("tarif",$were);
			}elseif($_POST['quotaMin']){
				$quotatMin = $_POST['quotaMin'];
				$were = "quota = '{$quotatMin}'";
				$results = DAO::getAll("tarif",$were);
			}elseif($_POST['quotaMax'] ){
				$quotaMax = $_POST['quotaMax'];
				$were = "quota ='{$quotaMax}'";
				$results = DAO::getAll("tarif",$were);
			}
			
	
			$this->loadView("recherche/vSearch.html",array(
					"activeTarifs"=>"active",
					"controller"=>"Tarifs",
					"method"=>"frmUpdate",
					"results"=>$results,
					"searchMontant"=>$search,
					"nbNouveauUser"=>DAO::count("utilisateur","nouveau = 1"),
					"nbUser"=>DAO::count("utilisateur"),
					"nbNouveauDisques"=>DAO::count("disque","nouveau = 1"),
					"nbDisques"=>DAO::count("disque"),
					"nbMessage"=>DAO::count("message"),
					"nbServices"=>DAO::count("service"),
					"nbTarifs"=>DAO::count("tarif"),
			));
	
		}else{
			$this->index();
		}
	}
	public function newUsers(){
		$users = DAO::getAll("Utilisateur","nouveau = 1");
		$this->loadView("recherche/vResult.html",array("results"=>$users));
	}
	
	public function allUsers(){
		$users = DAO::getAll("Utilisateur");
		$this->loadView("recherche/vResult.html",array("results"=>$users));
	}
	

}