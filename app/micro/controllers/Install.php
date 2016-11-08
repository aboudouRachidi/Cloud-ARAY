<?php
namespace micro\controllers;
use micro\orm\DAO;
use micro\db\Database;

class Install{
	public static function runIndex(){
		$db = new Database("");
		$db->connect();
		$message="";
		$messageCreateConf = "";
		$existDb = $db->query("SHOW DATABASES LIKE '".$GLOBALS["config"]["database"]["dbName"]."'");
		$existDb->execute();
		
		if($existDb->fetch()){
			
			$message = "Une BDD existe déjà, Installation par defaut !";
			$messageConf = "Configuration par défaut";
			$base = $GLOBALS["config"]["database"]["dbName"];
			$action = "runApp";
			
		}else{

			$message = "Aucune BDD trouvée !";
			$messageConf = "Mise à jour du fichier config.php";
			$base = "";
			$action = "runInstallBdd";
		}
		$existDb->closeCursor();
		$utilisateur = $db->getUser();
		
		if (!file_exists("app/config.php")){
			$messageCreateConf = "Creation de fichier de config";
			
			$nom_file = "config.php";
			$content ='<?php
					return array(
					"siteUrl"=>"http://127.0.0.1/Cloud-ARAY/",
					"documentRoot"=>"Accueil",
					"database"=>[
							"dbName"=>"cloud-aray",
							"serverName"=>"127.0.0.1",
							"port"=>"3306",
							"user"=>"root",
							"password"=>""
					],
					"onStartup"=>function($action){
					},
					"debug"=>false,
					"directories"=>["libraries"],
					"templateEngine"=>\'micro\views\engine\Twig\',
					"templateEngineOptions"=>array("cache"=>false),
					"test"=>false,
					"cloud"=>array(\'root\'=>\'files/\',
							\'prefix\'=>\'srv-\')
			);';
			
			// création du fichier
			$f = fopen($nom_file, "x+");
			// écriture
			if(fputs($f, $content )){
				$messageCreateConf = "Le fichier de configuration a été crée";
					$source =  $nom_file;
					$dest =  ROOT.$nom_file;
					if(copy($source, $dest)){
						$messageCopie = "Le fichier de configuration a été copié";
					}else{
						$messageCopie = "Erreur le fichier de configuration n'a pas été copié";
					}
					
			}else {
				$messageCreateConf = "Erreur : l'ecriture du fichier de configuration a echoué";
			}
			// fermeture
			fclose($f);
			
		}else{
			$messageCreateConf = "Le fichier de configuration existe !";
		}
	
		
		if(isset($_POST['runInstallBdd'])){
			if(empty($_POST['url']) || empty($_POST['base']) || empty($_POST['utilisateur'])){
				$postErreur = "Vous devez remplir tous les champs marqués par des <em>*</em> ";
				
			}else {
				Install::runInstallBdd();
			}
		}
		if(isset($_POST['runApp'])){
			if(empty($_POST['url']) || empty($_POST['base']) || empty($_POST['utilisateur'])){
				$postErreur = "Vous devez remplir tous les champs marqués par des <em>*</em> ";
				
			}else {
				Install::runInstallDefault();
			}
		}
		require_once ROOT."install/vInstall.php";
	}
	
	public static function runInstallBdd(){
		$db = new Database("");
		$db->connect();
		$url = $_POST['url'];
		$base = $_POST['base'];
		$user = $_POST['utilisateur'];
		$password = $_POST['password'];
		
		$filename = 'app/config.php';
		$vider = fopen($filename,"w");
		ftruncate($vider,0);
		$content ='<?php
		return array(
		"siteUrl"=>"http://'.$url.'/Cloud-ARAY/",
		"documentRoot"=>"Accueil",
		"database"=>[
				"dbName"=>"'.$base.'",
				"serverName"=>"'.$url.'",
				"port"=>"3306",
				"user"=>"'.$user.'",
				"password"=>"'.$password.'"
		],
		"onStartup"=>function($action){
		},
		"debug"=>false,
		"directories"=>["libraries"],
		"templateEngine"=>\'micro\views\engine\Twig\',
		"templateEngineOptions"=>array("cache"=>false),
		"test"=>false,
		"cloud"=>array(\'root\'=>\'files/\',
				\'prefix\'=>\'srv-\')
);';
		
		// Assurons nous que le fichier est accessible en écriture
		if (is_writable($filename)) {
				// Dans notre exemple, nous ouvrons le fichier $filename en mode d'ajout
				// Le pointeur de fichier est placé à la fin du fichier
				// c'est là que $content sera placé
				if (!$handle = fopen($filename, 'a')) {
					$messageInfo = "Impossible d'ouvrir le fichier ($filename)";
					exit;
				}
				// Ecrivons quelque chose dans notre fichier.
				if (fwrite($handle, $content) === FALSE) {
					$messageInfo = "Impossible d'écrire dans le fichier ($filename)";
					exit;
				}
				$messageInfo = "La configuration du fichier ($filename) a réussi";
				fclose($handle);
				
			$table = ROOT."database/cloud-arayversionsanstrigger.sql";
			
			$sql = "CREATE DATABASE $base";
			if ($db->query($sql)) {
				
				$messageBddCreate = "Base de données créée";
				
				$db = new Database("$base");
				$db->connect();
				//var_dump($db);
				
				
				$templine = '';
				$lines = file($table);
				foreach ($lines as $line)
				{
					if (substr($line, 0, 2) == '--' || $line == '')
						continue;
						$templine .= $line;
						if (substr(trim($line), -1, 1) == ';')
						{
							$db->query($templine) or print('Erreur lors de la requete \'<strong>' . $templine .'<br /><br />');
							$templine = '';
						}
				}
				$messageFin = "base restaurée";
				$oldName = ROOT."install/vInstall.php";
				$newName = ROOT."install/vInstallBdd.php";
				rename($oldName,$newName);
				header("Refresh:0");
			} else {
				
				$messageBddCreate = "Erreur lors de la création de la base";
			}
			

				
		} else {
			$messageInfo = "Le fichier $filename n'est pas accessible en écriture.";
		}
		
		require_once ROOT."install/vBddInstall.php";
	
	}
	
	public static function runInstallDefault(){
		$base = $_POST['base'];
		$db = new Database("");
		$db->connect();
		$table = ROOT."database/cloud-arayversionsanstrigger.sql";
			
		$sql = "CREATE DATABASE $base";
		if ($db->query($sql)) {
		
			$messageBddCreate = "Base de données créée";
		
			$db = new Database("$base");
			$db->connect();
			var_dump($db);
		
		
			$templine = '';
			$lines = file($table);
			foreach ($lines as $line)
			{
				if (substr($line, 0, 2) == '--' || $line == '')
					continue;
					$templine .= $line;
					if (substr(trim($line), -1, 1) == ';')
					{
						$db->query($templine) or print('Erreur lors de la requete \'<strong>' . $templine .'<br /><br />');
						$templine = '';
					}
			}
			$messageFin = "base restaurée";
			$oldName = ROOT."install/vInstall.php";
			$newName = ROOT."install/vInstallDefault.php";
			rename($oldName,$newName);
			header("Refresh:0");
			} else {
			
				$messageBddCreate = "Erreur lors de la création de la base";
			}

	}
}