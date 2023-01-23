<?php

namespace Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Classes\View;
use Classes\Config;
use Classes\Marker;

class MapController {
	
	public function show(Request $request, Response $response, array $args): Response {
		$markers = Marker::all();
		$jsonMarkers = json_encode($markers);
		
		$view = View::render('map', [
			'gmapsApiKey' => Config::get('GMAPS_API_KEY'),
			'jsonMarkers' => $jsonMarkers,
		]);
		$response->getBody()->write($view);
		
		return $response;
	}
	
	public function store(Request $request, Response $response, array $args): Response {
		$input = $request->getParsedBody();
		
		$inputError = false;
		$messages = [];
		
		if ($input['latitude'] == '') {
			$inputError = true;
			array_push($messages, 'Please set the latitude');
		}
		if ($input['longitude'] == '') {
			$inputError = true;
			array_push($messages, 'Please set the longitude');
		}
		
		if ($inputError) {
			$status = 'error';
			$jsonResponse = json_encode([
				'status' => $status,
				'messages' => $messages
			]);
			$response->getBody()->write($jsonResponse);
			
			return $response->withHeader('Content-type', 'application/json');
		}
		
		$marker = new Marker();
		$marker->lat = $input['latitude'];
		$marker->lng = $input['longitude'];
		$marker->icon = $input['icon'];
		$marker->comment = $input['comment'];
		$saveSuccess = $marker->save();
		
		if ($saveSuccess) {
			$status = 'success';
			$markers = Marker::all();
			$jsonResponse = json_encode([
				'status' => $status,
				'jsonMarkers' => $markers
			]);
		}
		else {
			$status = 'error';
			array_push($messages, 'Failed to save the marker. Please try again');
			$jsonResponse = json_encode([
				'status' => $status,
				'messages' => $messages
			]);
		}
		
		$response->getBody()->write($jsonResponse);
		
		return $response->withHeader('Content-type', 'application/json');
	}
}