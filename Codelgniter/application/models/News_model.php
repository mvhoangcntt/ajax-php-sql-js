<?php
class News_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    public function get_news()
	{
        $query = $this->db->get('news');
        return $query->result();
	}
    public function set_news()
    {
        $this->load->helper('url');

        $data = array(
            'title' => $this->input->post('title'),
            'text' => $this->input->post('text')
        );

        return $this->db->insert('news', $data);
    }
}