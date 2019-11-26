<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed'); 
class Products extends MY_Controller {

   public function __construct()
   {
      parent::__construct();
      $this->load->model('products_model');
   }
   public function index()
   {
      $data = array();
      $data['layout'] = $this->load->view('products/datatable', $data, true);
      $this->load->view('partical/layout_datatable',$data);
   }
   public function jsonDatatable()
   {
      $data = $this->products_model->get_news();
      foreach ($data as $index => $value) {
         $size = $this->products_model->get_size($value->product_id);
         $value->size = $size;
      }
      $product = array(
         "data"   => $data,
      );
      exit(json_encode($product));
   }
   public function view($limit = '',$page = 'listproduct')
   {
      $config['total_rows'] = $this->products_model->countALL();
      $config['base_url']   = base_url()."products/view";
      $config['per_page']   = 4;
      $this->pagination->initialize($config);
      $data['page']         = $this->pagination->create_links();
      $data['products']     = $this->products_model->getList($config['per_page'], $limit);
      
      for ($i=0; $i < count($data['products']) ; $i++) { 
         $data['products'][$i]['size'] = $this->products_model->get_size($data['products'][$i]['product_id']);
      }
      $data['layout'] = $this->load->view('products/'.$page, $data, true);
      $this->load->view('partical/master_layout',$data);
   }
   
   public function create(){
      $data = $this->convert_data();
      // thời gian hiện tại
      $data['created'] = date("Y-m-d");
      // bóc thông tin để insert bảng size
      $quantity   =  $data['quantity'];
      $text_size  =  $data['textsize'];
      // unset để thêm vào bảng product
      unset($data['quantity']);
      unset($data['textsize']);
      $insert_id = $this->products_model->set_products($data);
      if($insert_id != ''){
         $this->convert_size($insert_id, $quantity, $text_size);
      }else{
         $er = array(
            'type'=>'errors',
         );
         exit(json_encode($er));
      }
   }
   public function json_update($id){
      $data       = $this->products_model->get_json($id);
      $data->size = $this->products_model->get_size_come_product($id);
      die(json_encode($data));
   }

   public function update($id){
      $data = $this->convert_data();
      $quantity   =  $data['quantity'];
      $text_size  =  $data['textsize'];
      unset($data['quantity']); // không có trong bảng
      unset($data['textsize']);
      if ($this->products_model->update_products($data, $id)) 
      {
         $this->convert_size($id, $quantity, $text_size);
      }else{
         $er = array(
            'type'=>'errors',
         );
         exit(json_encode($er));
      }
   }
   public function convert_size($id, $quantity, $text_size){
      // xóa thông tin cũ
      $this->products_model->delete_size($id);
      foreach ($quantity as $key_quantity => $value_quantity) {
         $size = array(
            "product_id"   => $id,
            "text_size"    => $text_size[$key_quantity],
            "quantity"     => $value_quantity,
         );
         if($this->products_model->set_size($size)){
            $er = array(
            'type'=>'errors',
            );
            exit(json_encode($er));
         }
      }
      $er = array(
         'type'=>'success',
      );
      exit(json_encode($er));
   }
   // chứa data
   public function convert_data(){
      $this->_validate();
      $data  = $this->input->post();
      if(!empty(parent::upload("./image","image_link")))
         $data['image_link'] = parent::upload("./image","image_link");
      return $data;
   }
   // validate kiểm tra lỗi
   public function _validate(){
      if($this->input->post()){
         $config = array(
            'name'   => array(
               'field'  => 'name',
               'label'  => 'name',
               'rules'  => 'required|min_length[5]',
               'errors' => array(
                  'required'  => 'Không được để trống !',
                  'min_length'=> 'Nhập độ dài lớn hơn 5 ký tự !'
               ),
            ),
            'content'   => array(
               'field'  => 'content',
               'label'  => 'content',
               'rules'  => 'required|min_length[5]',
               'errors' => array(
                  'required'  => 'Không được để trống !',
                  'min_length'=> 'Nhập độ dài lớn hơn 5 ký tự !',
               ),
            ),
            'price'   => array(
               'field'  => 'price',
               'label'  => 'price',
               'rules'  => 'required|numeric',
               'errors' => array(
                  'required'  => 'Không được để trống !',
                  'numeric'   => 'Không phải là số !'
               ),
            ),
            'total'   => array(
               'field'  => 'total',
               'label'  => 'total',
               'rules'  => 'required|numeric',
               'errors' => array(
                  'required'  => 'Không được để trống !',
                  'numeric'   => 'Không phải là số !'
               ),
            ),
         );
         $post = $this->input->post();
         foreach ($post['quantity'] as $key => $value) {
            $config['quantity['.$key.']'] = array(
               'field'  => "quantity[".$key."]",
               'label'  => 'quantity_'.$key,
               'rules'  => 'required|numeric',
               'errors' => array(
                  'required'  => 'Không được để trống !',
                  'numeric'   => 'Không phải là số !'
               ),
            );
            $config['textsize['.$key.']'] = array(
               'field'  => "textsize[".$key."]",
               'label'  => 'textsize_'.$key,
               'rules'  => 'required',
               'errors' => array(
                  'required'  => 'Không được để trống !',
               ),
            );
         }
         
         $result = array();
         foreach ($config as $value) {
            $this->form_validation->set_rules(
               $value['field'],
               $value['label'],
               $value['rules'],
               $value['errors']
            );
         }
         if (!$this->form_validation->run()){
            foreach ($config as $key => $value) {
               $result[$key] = form_error($value['field']);
            }
            $er = array(
               'type'  =>'errors',
               'value' => $result
            );
            exit(json_encode($er));
         }
      }
   }

   public function delete_pr($id){
      if($this->products_model->delete_product($id)){
         $er = array(
            'type'=>'success',
         );
         exit(json_encode($er));
      }else{
         $er = array(
            'type'=>'errors',
         );

         exit(json_encode($er));
      }
   }
}

 ?>