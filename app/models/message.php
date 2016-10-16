<?php
class Message extends \Base {
	/**
	 * @Id
	 */
	private $id;
	private $date;
	private $contenu;
	/**
	 * @ManyToOne
	 * @JoinColumn(name="idUtilisateur",className="Utilisateur",nullable=false)
	 */
	private $utilisateur;
	/**
	 * @ManyToOne
	 * @JoinColumn(name="idDisque",className="Disque",nullable=false)
	 */
	private $disque;
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id=$id;
		return $this;
	}
	public function getDate() {
		return $this->date;
	}
	public function setDate($date) {
		$this->date=$date;
		return $this;
	}
	public function getContenu() {
		return $this->contenu;
	}
	public function setContenu($contenu) {
		$this->contenu=$contenu;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see Base::toString()
	 */
	public function toString() {
		return "Le message du ".$this->disque.":".$this->utilisateur;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
	public function setUtilisateur($utilisateur) {
		$this->utilisateur = $utilisateur;
		return $this;
	}
	public function getDisque() {
		return $this->disque;
	}
	public function setDisque($disque) {
		$this->disque = $disque;
		return $this;
	}
	
}