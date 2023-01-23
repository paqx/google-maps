<?php

namespace Classes;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Classes\Config;

class View {
	
	public static function render(string $template, $args = []) {
		$loader = new FilesystemLoader(Config::get('TWIG_TEMPLATES'));
		$twig = new Environment($loader);
		$view = $twig->render($template.'.html', $args);
		
		return $view;
	}
}