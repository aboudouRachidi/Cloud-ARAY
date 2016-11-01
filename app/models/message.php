<?php
class Message extends \Base {
	/**
	 * @Id
	 */
	private $id;
	private $objet;
	private $contenu;
	private $date;
	private $lu;
	
	/**
	 * @ManyToOne
	 * @JoinColumn(name="idExpediteur",className="Utilisateur",nullable=false)
	 */
	private $expediteur;
	/**
	 * @ManyToOne
	 * @JoinColumn(name="idReceveur",className="Utilisateur",nullable=false)
	 */
	private $receveur;
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getObjet() {
		return $this->objet;
	}
	public function setObjet($objet) {
		$this->objet = $objet;
		return $this;
	}
	public function getContenu() {
		return $this->contenu;
	}
	public function setContenu($contenu) {
		$this->contenu = $contenu;
		return $this;
	}
	public function getDate() {
		return $this->date;
	}
	public function setDate($date) {
		$this->date = $date;
		return $this;
	}
	public function getLu() {
		return $this->lu;
	}
	public function setLu($lu) {
		$this->lu = $lu;
		return $this;
	}
	public function getExpediteur() {
		return $this->expediteur;
	}
	public function setExpediteur($expediteur) {
		$this->expediteur = $expediteur;
		return $this;
	}
	public function getReceveur() {
		return $this->receveur;
	}
	public function setReceveur($receveur) {
		$this->receveur = $receveur;
		return $this;
	}
	
	public function toString() {
		return (string) $this->objet.
				" <br>- Expéditeur ".$this->expediteur->getLogin()."<br> - Receveur ".$this->receveur->getLogin().
		"<br> - Date ".$this->date;
	}
	
}