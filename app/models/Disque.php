<?php

class Disque extends Base{
	/**
	 * @Id
	 */
	private $id;
	private $nom;
	private $createdAt;
	private $nouveau;
	/**
	 * @ManyToOne
	 * @JoinColumn(name="idUtilisateur",className="Utilisateur",nullable=false)
	 */
	private $utilisateur;

	/**
	 * @OneToMany(mappedBy="disque",className="DisqueTarif")
	 */
	private $disqueTarifs;

	/**
	 * @ManyToMany(targetEntity="Service", inversedBy="disques")
	 * @JoinTable(name="disque_service")
	 */
	private $services;
	
	/**
	 * @OneToMany(mappedBy="disque",className="Message")
	 */
	private $messages;
	
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id=$id;
		return $this;
	}

	public function getNom() {
		return $this->nom;
	}

	public function setNom($nom) {
		$this->nom=$nom;
		return $this;
	}

	public function getUtilisateur() {
		return $this->utilisateur;
	}

	public function setUtilisateur($utilisateur) {
		$this->utilisateur=$utilisateur;
		return $this;
	}

	public function getTarifs() {
		return $this->tarifs;
	}

	public function setTarifs($tarifs) {
		$this->tarifs=$tarifs;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 * @see Base::toString()
	 */
	public function toString() {
		return $this->nom.":".$this->utilisateur->getLogin();
	}

	public function getDisqueTarifs() {
		return $this->disqueTarifs;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

	public function setCreatedAt($createdAt) {
		$this->createdAt=$createdAt;
		return $this;
	}

	public function getSize(){
		 $result= ModelUtils::getDisqueOccupation($GLOBALS["config"]["cloud"],$this);
		 return $result;
	}

	public function getTarif(){
		return ModelUtils::getDisqueTarif($this);
	}

	public function getQuota(){
		$result=0.1;
		$tarif=$this->getTarif();
		if($tarif!=null)
			$result=ModelUtils::sizeConverter($tarif->getUnite())*$tarif->getQuota();
		return $result;
	}

	public function getOccupation(){
		return round($this->getSize()/$this->getQuota()*100,2);
	}
	
	public function pourcentage(){
		$result=($this->getOccupation()*100)/$this->getQuota();
		return round($result);
	}
	
	public function getUnite(){
		$tarif=$this->getTarif();
		return $tarif->getUnite();
	}
	
	public function setDisqueTarifs($disqueTarifs) {
		$this->disqueTarifs=$disqueTarifs;
		return $this;
	}

	public function getServices() {
		return $this->services;
	}

	public function setServices($services) {
		$this->services=$services;
		return $this;
	}
	public function getNouveau() {
		return $this->nouveau;
	}
	public function setNouveau($nouveau) {
		$this->nouveau = $nouveau;
		return $this;
	}

	
	public function  addService($service){
		$this->services[]=$service;
		return $this;
	}
	
	public function removeService($idService){
		$i=0;
		foreach ($this->services as $service){
			if($service->getId()===$idService){
				unset($this->services[$i]);
				return true;
			}
			$i++;
		}
		return false;
	}
	public function getMessages() {
		return $this->messages;
	}
	public function setMessages($messages) {
		$this->messages = $messages;
		return $this;
	}
	
	
	
}
