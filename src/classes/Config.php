<?php

namespace Classes;

class Config {
	
	protected $configFile;
	protected $envFile;
	protected $config = [];
	
	public function __construct() {
		$this->configFile = CONFIG_FILE;
		$this->envFile = ENV_FILE;
		
		if (!file_exists($this->configFile)) {
			trigger_error('Config file '.$this->configFile.' not found', E_USER_ERROR);
		}
		
		$config = require $this->configFile;
		
		if (file_exists($this->envFile)) {
			$env = parse_ini_file($this->envFile);

			foreach (array_keys($env) as $key) {
				$config[$key] = $env[$key];
			}
		}
		
		$this->config = $config;
	}
	
	/**
	 * Get the value of the $key from the configuration file.
	 * Keys from CONFIG_FILE are overwritten by keys from ENV_FILE, 
	 * if the latter is present
	 * 
	 * @param string $key
	 * @return string
	 */
	public static function get(string $key): string {
		$self = new static;
		
		if (isset($self->config[$key])) {
			return $self->config[$key];
		}
		else {
			trigger_error('Key '.$key.' not found in config file '.$self->configFile, E_USER_ERROR);
		}
	}
}