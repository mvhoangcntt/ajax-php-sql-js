<?php
class News extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        // $this->lang->load('form_validation', 'vi');     
        $this->load->helper('url_helper');
    }
    public function ds(){
        $data['news'] = $this->news_model->get_news();
        $this->load->view('news/danhsach',$data);
    }

    public function index()
    {
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->view('news/view');
    }

    public function abc()
    {
        $this->load->helper(array('form', 'url'));
        // $this->form_validation->set_error_delimiters('', '');
        $this->load->library('form_validation');

        $config = array(
            'username'=>
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|min_length[15]',
                'errors' => array(
                        'required' => 'You must provide a %s.',
                        'min_length' => 'Ngắn quá !'
                ),
            ),
            'password'=>
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required',
                'errors' => array(
                        'required' => 'You must provide a %s.',
                ),
            )
        );
        
        // $this->form_validation->set_rules($config);
        $result = array();
        foreach ($config as $value) {
            $this->form_validation->set_rules(
                $value['field'],
                $value['label'],
                $value['rules'],
                $value['errors']
            );
        }

        if (!$this->form_validation->run())
        {
            foreach ($config as $key => $value) {

                $result[$key] = form_error($value['field']);

            }
            $er = array(
                'type'=>'errors',
                'value' => $result
            );
            exit(json_encode($er));

        }
        else
        {
            $er = array(
                'type'=>'success',
                'value' => $result
            );
            exit(json_encode($er));
        }
            
                  

    }
    public function create()
    {
            
        $this->load->helper('form');
        $this->load->library('form_validation');

        //$data['title'] = 'Create a news item';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');

        if ($this->form_validation->run() === FALSE)
        {       
            $this->load->view('news/view');
                // $this->load->view('templates/footer');

        }
        else
        {
            $this->news_model->set_news();
            $this->load->view('news/success');
        }
    }
}