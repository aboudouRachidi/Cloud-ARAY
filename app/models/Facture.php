<?php
class Facture extends \Base {
	/**
	 * @Id
	 */
	private $id;
	private $total;
	private $date;
	private $reglee;

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
		$this->id = $id;
		return $this;
	}
	public function getTotal() {
		return $this->total;
	}
	public function setTotal($total) {
		$this->total = $total;
		return $this;
	}
	public function getDate() {
		return $this->date;
	}
	public function setDate($date) {
		$this->date = $date;
		return $this;
	}
	public function getReglee() {
		return $this->reglee;
	}
	public function setReglee($reglee) {
		$this->reglee = $reglee;
		return $this;
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