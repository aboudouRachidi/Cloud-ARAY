<?php
use micro\js\Jquery;
use micro\orm\DAO;
/**
 * Contrôleur permettant d'afficher/gérer 1 disque
 * @author jcheron
 * @version 1.1
 * @package cloud.controllers
 */
class Scan extends BaseController {
	use MessagesTrait;
	public function index(){

	}

	/**
	 * Affiche un disque
	 * @param int $idDisque
	 */
	public function show($idDisque) {
		if(Auth::isAdmin() && DAO::getOne("disque", "id = '".$idDisque."'") || DAO::getOne("disque", "id = '".$idDisque."' AND idUtilisateur ='".Auth::getUser()->getId()."'")){
		
		$disque = DAO::getOne("disque", $idDisque);
		if(Auth::isAdmin()){
			$disque->setNouveau(0);
			DAO::update($disque);
		}
		$utilisateur=$disque->getUtilisateur();
		$tarif=$disque->getTarif();
		$services=DAO::getManyToMany($disque, "services");
		$diskName=$disque->getNom();
		if ($disque->Pourcentage()>100)
		{
			$this->messageDanger("Quota depassé veuillez changer de tarif ou vider votre disque.");
			
		}
		$this->loadView("scan/vFolder.html",array("disque"=>$disque,"utilisateur"=>$utilisateur,"tarif"=>$tarif,"services"=>$services));
		Jquery::executeOn("#ckSelectAll", "click","$('.toDelete').prop('checked', $(this).prop('checked'));$('#btDelete').toggle($('.toDelete:checked').length>0)");
		Jquery::executeOn("#btUpload", "click", "$('#tabsMenu a:last').tab('show');");
		Jquery::doJqueryOn("#btDelete", "click", "#panelConfirmDelete", "show");
		Jquery::postOn("click", "#btConfirmDelete", "scan/delete","#ajaxResponse",array("params"=>"$('.toDelete:checked').serialize()"));
		Jquery::doJqueryOn("#btFrmCreateFolder", "click", "#panelCreateFolder", "toggle");
		Jquery::postFormOn("click", "#btCreateFolder", "Scan/createFolder", "frmCreateFolder","#ajaxResponse");
		Jquery::execute("window.location.hash='';scan('".$diskName."')",true);
		echo Jquery::compile();
		
		}else{
			$this->messageWarning("Ce disque n'existe pas");
			$this->forward(MyDisques::class);
		}
	}

	public function files($dir="Datas"){
		$cloud=$GLOBALS["config"]["cloud"];
		$root=$cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin()."/";
		$response = DirectoryUtils::scan($root.$dir,$root);

		header('Content-type: application/json');
		echo json_encode(array(
				"name" => $dir,
				"type" => "folder",
				"path" => $dir,
				"items" => $response,
				"root" => $root
		));
	}

	public function upload(){
		$allowed = array('png', 'jpg', 'gif','zip');

		if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

			$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

			if(!in_array(strtolower($extension), $allowed)){
				echo '{"status":"error"}';
				exit;
			}

			if(move_uploaded_file($_FILES['upl']['tmp_name'], $_POST["activeFolder"].'/'.$_FILES['upl']['name'])){
				echo '{"status":"success"}';
				exit;
			}
		}

		echo '{"status":"error"}';
		exit;
	}

	/**
	 * Supprime le fichier dont le nom est fourni dans la clé toDelete du $_POST
	 */
	public function delete(){
		if(array_key_exists("toDelete", $_POST)){
			foreach ($_POST["toDelete"] as $f){
				unlink(realpath($f));
			}
			echo Jquery::execute("scan()");
			echo Jquery::doJquery("#panelConfirmDelete", "hide");

		}
	}

	/**
	 * Crée le dossier dont le nom est fourni dans la clé folderName du $_POST
	 */
	public function createFolder(){
		if(!DirectoryUtils::existDir($_POST["activeFolder"].DIRECTORY_SEPARATOR.$_POST["folderName"])){
		if(array_key_exists("folderName", $_POST)){
			$pathname=$_POST["activeFolder"].DIRECTORY_SEPARATOR.$_POST["folderName"];
			if(DirectoryUtils::mkdir($pathname)===false){
				$this->showMessage("Impossible de créer le dossier `".$pathname."`", "warning");
			}else{
				Jquery::execute("scan();",true);
			}
			Jquery::doJquery("#panelCreateFolder", "hide");
			echo Jquery::compile();
		}
		}else{
			$this->showMessage("Le dossier ".$_POST["folderName"]." existe déjà", "warning");
		}
	}

	/**
	 * Affiche un message dans une alert Bootstrap
	 * @param String $message
	 * @param String $type Class css du message (info, warning...)
	 * @param number $timerInterval Temps d'affichage en ms
	 * @param string $dismissable Alert refermable
	 * @param string $visible
	 */
	public function showMessage($message,$type,$timerInterval=5000,$dismissable=true){
		$this->loadView("main/vInfo",array("message"=>$message,"type"=>$type,"dismissable"=>$dismissable,"timerInterval"=>$timerInterval,"visible"=>true));
	}
}