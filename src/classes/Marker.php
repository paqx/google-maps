<?php

namespace Classes;

use Classes\Config;

class Marker {
	
	const supportedDrivers = [
		'JSON',
	];
	
	protected $markersDriver;
	
	public $lat;
	public $lng;
	public $icon;
	public $comment;

	public function __construct() {
		$markersDriver = Config::get('MARKERS_DRIVER');
		
		if (!in_array($markersDriver, self::supportedDrivers)) {
			trigger_error('Unknown markers driver '.$markersDriver, E_USER_ERROR);
		}
		
		$this->markersDriver = $markersDriver;
	}
	
	public static function all(): array {
		$self = new static;
		
		switch ($self->markersDriver) {
			case 'JSON':
				return $self->fetchJsonMarkers();
		}
	}
	
	public function save(): bool {
		$marker = [
			'lat' => $this->lat,
			'lng' => $this->lng,
			'icon' => $this->icon,
			'comment' => $this->comment
		];
		
		switch ($this->markersDriver) {
			case 'JSON':
				return $this->writeJsonMarkers($marker);
		}
	}
	
	protected function getJsonMarkersFile(): string {
		$jsonFile = Config::get('MARKERS_JSON_FILE');
		
		if (!file_exists($jsonFile)) {
			trigger_error('Json file '.$jsonFile.' not found', E_USER_ERROR);
		}
		
		return $jsonFile;
	}
	
	protected function fetchJsonMarkers(): array {
		$jsonFile = $this->getJsonMarkersFile();
		$jsonMarkers = file_get_contents($jsonFile);
		$markers = json_decode($jsonMarkers);
		
		return $markers ?? [];
	}
	
	protected function writeJsonMarkers(array $marker): bool {
		$markers = Marker::all();
		array_push($markers, $marker);
		$jsonMarkers = json_encode($markers);
		$jsonFile = $this->getJsonMarkersFile();
		$result = file_put_contents($jsonFile, $jsonMarkers);
		
		if ($result == false) {
			return false;
		}
		
		return true;
	}
}