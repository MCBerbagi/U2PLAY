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
		$data["service"] = "w2playgames";
		$data["author"] = "letra89";
		$response['status'] = "Ready";
		$response['code'] = "00";
		$response['data'] = $data;
		$this->response($response, self::HTTP_OK);
	}

	public function listgames_get() {
		$id = $this->get("id_game");
		if($id == '') {
			// list all games
			$dataplaygames = $this->db->get('playgames')->result();
		}else if($id != ''){
			// game detail by id_game
			$this->db->where('id_game', $id);
			$dataplaygames = $this->db->get('playgames')->result();
		}
		if(empty($dataplaygames)) {
			$this->response(['status'=>'false', 'code'=>'xx','data'=>$dataplaygames], self::HTTP_NOT_FOUND);
		}else{
			$this->response(['status'=>'success','code'=>'00','data'=>$dataplaygames], self::HTTP_OK);
		}
	}

	public function updategames_post() {
		$id = $this->post('id_game');

		$config = [
			'allowed_types' => 'jpg|png|gif|jpeg|',
			'max_size' => '4096',
			'upload_path' => './assets/img'
		];

		$this->load->library('upload', $config);
		if($this->upload->do_upload('file') != '') {
			$image =  $this->upload->data();
			$data = [
				'id_game' => $this->post('id_game'),
				'nama_game' => $this->post('nama_game'),
				'deskripsi_game' => $this->post('deskripsi_game'),
				'kategori' => $this->post('kategori'),
				'gambar_game' => $image['file_name']
			];
			$this->db->where('id_game', $id);
			$update = $this->db->update('playgames', $data);
			if($update) {
				$this->response(['status'=>'success','code'=>'00','data'=>$data], self::HTTP_OK);
			}else{
				$this->response(array('status' => 'fail', 502));
			}
		}else{
			$error = ['error' => $this->upload->display_errors()];
			$this->response(['status'=>'false', 'code'=>'xx', 'error'=>$error], self::HTTP_NOT_FOUND);
		}
	}

	public function index_post() {
		$id = $this->post('id_game');
		$this->db->where('id_game', $id);
		$delete = $this->db->delete('playgames');
		if($delete) {
			$this->response(['status'=>'success deleted','code'=>'00'], self::HTTP_OK);
		}else{
			$this->response(array('status' => 'fail', 502));
		}

	}


}
