<?php
use micro\orm\DAO;

trait checkUsers{
	/**
	 * Verifie si le login et l'e-mail ne sont pas utilisé
	 * @return boolean
	 */
	public function checkLogin(){
	
		if ($_POST["login"]){
			if (DAO::getOne("Utilisateur", "login='".$_POST["login"]."' ")){
				return true;
			}else{
				return false;
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
			}else{
				return false;
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
}