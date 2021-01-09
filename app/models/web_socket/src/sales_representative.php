<?php
namespace myApp;
use models\sales_representative_models\sales_representative_coords;
class sales_representative
{
    private $sales_representative_coords_model,$from_data,$file_json,$json_data;
    public function __construct($from,$data,$clients,$users)
    {
        $right_params = new check_params($data,['coords']);
        if($right_params){
			$this->get_sales_status();
			parse_str($from->WebSocket->request->getQuery(),$from_data);
			$this->from_data = $from_data;
			$this->sales_representative_coords_model =  new sales_representative_coords;
			$saved = $this->save_updates($data);
			$this->sales_representative_coords_model::$id = '';
			$this->send_response_to_clients($data,$clients,$saved,$users,$from);
		}
	}
	private function save_updates($data)
	{
		$sales_rep_id = filter_var($this->from_data['id'],FILTER_VALIDATE_INT);
		$this->coords = $data->coords;
		if(is_numeric($this->coords->lat) && is_numeric($this->coords->lng)){
			if($this->check_if_sales_representative_has_record_today($sales_rep_id)){
				$this->sales_representative_coords_model::$id = $sales_rep_id;
				$this->sales_representative_coords_model::$tableSchema['coords'] = "\"lat\",".$this->coords->lat.",\"lng\",".$this->coords->lng;
				return $this->sales_representative_coords_model->update_coords();
			}else{
				$this->register_sales_representative($sales_rep_id);
				$this->sales_representative_coords_model::$id = $sales_rep_id;
				$this->sales_representative_coords_model::$tableSchema['coords'] = "\"lat\",".$this->coords->lat.",\"lng\",".$this->coords->lng;
				return $this->sales_representative_coords_model->insert();
			}
		}
	}
	private function check_if_sales_representative_has_record_today($id)
	{		
		return in_array($id,$this->json_data['sales_representative_id']);
	}
	private function register_sales_representative($sales_rep_id)
	{
		$json = json_decode($this->file_json_content);
		$json->sales_representative_id[] = $sales_rep_id;
		$data = json_encode($json);
		file_put_contents($this->file_json,$data);
	}
	private function get_sales_status()
	{
		$this->file_json = dirname(__DIR__).DS.'..'.DS.'..'.DS.'api'.DS.'check_if_sales_representative_has_record_today.json';
		$this->file_json_content = file_get_contents($this->file_json);
		$this->json_data = json_decode($this->file_json_content,true);
	}
    private function send_response_to_clients($data,$clients,$saved,$users,$from)
    {
        // OUTPUT
		if($saved){
			foreach($clients as $client)
			{
				parse_str($client->WebSocket->request->getQuery(),$client_data);
				parse_str($from->WebSocket->request->getQuery(),$from_data);
				if($client_data['type'] == 'admin' && isset($users[$client_data['access_token']]))
				{
					$client->send(json_encode(array('coords' => $this->coords,'sales_id' => $from_data['id'])));
				}
			}
		}
    }
}