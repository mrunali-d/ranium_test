<?php
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');

define('SUCCESS', 'SUCCESS');
define('HEADER_TYPE', 'application/json');
define('GET_NEO_FEED', 'https://api.nasa.gov/neo/rest/v1/feed');
define('APP_KEY', 'DEMO_KEY');


class NasaApisComponent extends Component {	
	
/*For get http object*/
	private function getHttpSocket(){
		$httpSocket = new HttpSocket(array('ssl_verify_host' => true));
		return $httpSocket;
	}
	
/*For set http header*/
	private function setHttpHeader($token = null){
		$ApiReqDetail['header']['Content-Type'] = HEADER_TYPE;
		$ApiReqDetail['header']['Accept'] = HEADER_TYPE;
		return $ApiReqDetail;
	}

/*To get asteroids*/
	public function getAsteroidsList($start_date,$end_date) {
		try {					
		        $apiUrl = GET_NEO_FEED."?start_date=$start_date&end_date=$end_date&api_key=".APP_KEY;
				$httpSocket = $this->getHttpSocket();
				$ApiReqDetail = $this->setHttpHeader();
				return $httpSocket->get($apiUrl, null, $ApiReqDetail);
			
		} catch (Exception $e) {
			//print_r($e);
		}
		return null;
	}
	
}
