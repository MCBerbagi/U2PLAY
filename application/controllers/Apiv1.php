<?php
use chriskacerguis\RestServer\RestController;

class Apiv1 extends RestController
{
	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->database();
	}

	public function index_get() {
		// test response
		$response['status'] = 200;
		$response['error'] = false;
		$response['creator'] = "MoonMan89";

		$this->response($response, 200);
	}

	public function listgames_get() {
		$id = $this->get("id_game");
		if($id == '') {
			$dataplaygames = $this->db->get('playgames')->result();
		}else if($id != ''){
			$this->db->where('id_game', $id);
			$dataplaygames = $this->db->get('playgames')->result();
		}
		if(empty($dataplaygames)) {
			$this->response(['status'=>'false', 'code'=>'xx','data'=>$dataplaygames], self::HTTP_NOT_FOUND);
		}else{
			$this->response(['status'=>'success','code'=>'00','data'=>$dataplaygames], self::HTTP_OK);
		}
	}
}
