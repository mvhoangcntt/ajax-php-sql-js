<?php 
class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
      	$this->load->library('form_validation');
      	$this->load->library('pagination');
      	$this->form_validation->set_error_delimiters('','');
	}
	// upload file (đường dẫn, tên thẻ input)
	function upload($upload_path = '', $fileimage = ''){
		$config = $this->config($upload_path);
		$this->load->library('upload', $config);
		$uploaded_name = '';
		if(!$this->upload->do_upload($fileimage)){
			if (empty($this->input->post('id'))) {
				return $uploaded_name;
			}
			$er = array(
	            'type'=>'errors',
	            'messet' => 'Up lỗi !'
            );			
            exit(json_encode($er));
		}
		$uploaded_name = $this->upload->data()['file_name'];
		return $uploaded_name;
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