<?php

use Tonic\Response;

/**
 * This class defines an example resource that is wired into the URI /example
 * @uri /statistik/{sensor}/{interval}
 */
class ExampleResource extends Tonic\Resource {

	const HOURLY = 0;
	const DAYLY = 1;
	const WEEKLY = 2;
	const MONTHLY = 3;
	const YEARLY = 4;
		
	/**
	 * @method GET
	 * @json
	 */
	function getStatistik($sensor, $interval) {
		
		$db = $this->app->container['db'];
		
		try {
			
			$data = array();
			
			$stmt = null;
			
			switch($sensor) {
				case "gas":
					$stmt = $db->query('SELECT * FROM statistik.gas ORDER BY Datum');
					break;
				default:
					return new Response(Response::BADREQUEST, array("message" => "Unknown sensor " . $sensor));
			}
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $row;
			}
			
			return new Response(Response::OK, $data);
			
		} catch (PDOException $e) {
			return new Response(Response::BADREQUEST, $e->getMessage());
		}
	}
	
	
	
	function json() {
		$this->before(function ($request) {
			if ($request->contentType == "application/json") {
				$request->data = json_decode($request->data);
			}
		});
		$this->after(function ($response) {
			$response->contentType = "application/json";
			$response->body = json_encode($response->body);
		});
	}
}