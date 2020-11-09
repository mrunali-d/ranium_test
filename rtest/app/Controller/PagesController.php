<?php
App::uses('AppController', 'Controller');

class PagesController extends AppController {


	public $components = array('NasaApis');
	

	public function home()
	{
		
	}
	public function findNeoFeedData()
	{		
		$this->autoRender = FALSE;
		$this->layout = "ajax";
		if($this->request->is('ajax'))
		{	
			$key = array();
			$value = array();
			$result = array();
			if(!empty($this->request->data))
			{		
				$start_date = date('Y-m-d',strtotime($this->request->data['DateRange']['start_date']));	
				$end_date = date('Y-m-d',strtotime($this->request->data['DateRange']['end_date']));	
				$neoData = $this->NasaApis->getAsteroidsList($start_date,$end_date);
				$result = json_decode($neoData['body'],true);
				if(!empty($result))
				{
					$speed= 0;
					$dis = 0;
					$avg = 0;
					$avgVal = 0;
					$valueArr=$result['near_earth_objects'];
					ksort($valueArr);		
					foreach($valueArr as $k=>$row)
					{			
						$value[] = count($row);
						$key[] = $k;
						foreach($row as $ky =>$inner)
						{
							$vel =  $inner['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'];
							if($vel > $speed)
							{
								$speed = $vel;
								$id = $inner['id'];
							}
							$colse =  $inner['close_approach_data'][0]['miss_distance']['kilometers'];
							if($ky == 0)
							{
								$dis = $colse;
								$c_id = $inner['id'];
							}elseif($colse < $dis){
								$dis = $colse;
								$c_id = $inner['id'];								
							}
							$avg = $avg + $inner['close_approach_data'][0]['miss_distance']['kilometers'];
						}
							$avgVal = $avgVal +  count($row);	
					}
					$avg =	($avg)/($avgVal);
					$fast['id']=$id;
					$fast['speed']=$speed;
					$close['dis']=$dis;
					$close['c_id']=$c_id;
					
					return json_encode(array('status'=>200,'fast'=>$fast,'close'=>$close,'avg'=>$avg,'chars'=>$key,'value'=>$value),JSON_NUMERIC_CHECK);
				}else{					
					return json_encode(array('status'=> 500));
					
				}
					
			}
			
		}
		
		
	}
}
