<?php 
class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
      	$this->load->library('form_validation');
      	$this->form_validation->set_error_delimiters('','');
	}
	// upload file (đường dẫn, tên thẻ input)
	function upload($upload_path = '', $fileimage = ''){
		$config = $this->config($upload_path);
		$this->load->library('upload', $config);
		$data = array(
			'type' 		=> 'err',
			'message' 	=> 'lỗi upload !',
			'loi'		=>	$this->upload->display_errors()
		);

		if($this->upload->do_upload($fileimage)){
			// $data = $this->upload->data();
			$data = array(
				'type' 		=> 'success',
				'message'	=> 'Thành công !',
				'image'		=>	$this->upload->data()
			);
		}
		return $data;
	}
	function config($upload_path = ''){
		$config = array();	
		// thư mục chứa fiile
		$config['upload_path'] = $upload_path;
		// định dạng file được phép 
		$config['allowed_types'] = 'jpg|png|gif';
		$config['max_size']             = 1200;
      	$config['max_width']            = 1024;
      	$config['max_height']           = 1024;
      	return $config;
	}
}


 ?>