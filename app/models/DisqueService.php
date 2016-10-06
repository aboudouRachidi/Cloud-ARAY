<?php
/**
 * @Table(name="disque_service")
 */
class DisqueService extends \Base {
	/**
	 * @Id
	 */
	private $idDisque;
	/**
	 * @Id
	 */
	private $idService;



	/**
	 * @ManyToOne
	 * @JoinColumn(name="idService",className="Service,nullable=false)
	 */
	private $service;

	/**
	 * @ManyToOne
	 * @JoinColumn(name="idDisque",className="Disque",nullable=false)
	 */
	private $disque;

	public function toString() {
		$this->service;
	}
	public function getIdDisque() {
		return $this->idDisque;
	}
	public function setIdDisque($idDisque) {
		$this->idDisque = $idDisque;
		return $this;
	}
	public function getIdService() {
		return $this->idService;
	}
	public function setIdService($idService) {
		$this->idService = $idService;
		return $this;
	}
	public function getService() {
		return $this->service;
	}
	public function setService($service) {
		$this->service = $service;
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