<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed'); 
class Products extends MY_Controller {

   public function __construct()
   {
      parent::__construct();
      $this->load->model('products_model');
   }
   public function view($limit = '',$page = 'listproduct')
   {
      $this->load->library('pagination');
      $config['total_rows'] = $this->products_model->countALL();
      $config['base_url']   = base_url()."index.php/products/view";
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
      $date = new DateTime();
      $created = date_format($date,"Y/m/d");
      $data['created'] = $created;
      // bóc thông tin để insert bảng size
      $quantity   =  $data['quantity'];
      $text_size  =  $data['textsize'];
      // unset để thêm vào bảng product
      unset($data['quantity']);
      unset($data['textsize']);
      $insert_id = $this->products_model->set_products($data);
      if($insert_id != ''){
         // lấy thông tin cần insert
         $si = array();
         foreach ($quantity as $key => $value) {
            array_push($si, $value);            
         }
         $ze = array();
         foreach ($text_size as $key => $value) {
            array_push($ze, $value);            
         }
         for ($i=0; $i < count($si) ; $i++) {
            $size = array(
               "product_id"   => $insert_id,
               "text_size"    => $ze[$i],
               "quantity"     => $si[$i],
            );
            $this->products_model->set_size($size); 
         }
         $er = array(
            'type'=>'success',
         );
         exit(json_encode($er));
      }
   }
   public function json_update($id){
      $data      = $this->products_model->get_json($id);
      // chuyển về dạng json
      $product   = array();
      $quantity  = array();
      $text_size = array();
      for ($i=0; $i < count($data) ; $i++) { 
         foreach ($data[$i] as $key => $value) {
            $product[$key] = $value;
            if ($key == 'quantity') {
               array_push($quantity, $value);
            }
            if ($key == 'text_size') {
               array_push($text_size, $value);
            }
         }
      }
      unset($product['quantity']);
      unset($product['text_size']);
      $product['quantity']    = $quantity;
      $product['text_size']   = $text_size;
      
      die(json_encode($product));
   }

   public function update($id){
      $data = $this->convert_data();
      $quantity   =  $data['quantity'];
      $text_size  =  $data['textsize'];
      unset($data['quantity']); // không có trong bảng
      unset($data['textsize']);
      if ($this->products_model->update_products($data, $id)) 
      {
         // xóa thông tin cũ
         $this->products_model->delete_size($id);
         // lấy thông tin mới cần insert
         $si = array();
         foreach ($quantity as $key => $value) {
            array_push($si, $value);            
         }
         $ze = array();
         foreach ($text_size as $key => $value) {
            array_push($ze, $value);            
         }
         for ($i=0; $i < count($si) ; $i++) {
            $size = array(
               "product_id"   => $id,
               "text_size"    => $ze[$i],
               "quantity"     => $si[$i],
            );
            $this->products_model->set_size($size); 
         }
         $er = array(
            'type'=>'success',
         );
         exit(json_encode($er));
      }
   }
   // chứa data
   public function convert_data(){
      $this->_validate();
      $data  = $this->input->post();
      $image = parent::upload("./image","image_link");
      if($image['type'] == 'success'){
         $image_link = $image['image']['file_name'];
         $data['image_link'] = $image_link;
      }
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
            $config[$key] = array(
               'field'  => "quantity[".$key."]",
               'label'  => 'quantity_'.$key,
               'rules'  => 'required|numeric',
               'errors' => array(
                  'required'  => 'Không được để trống !',
                  'numeric'   => 'Không phải là số !'
               ),
            );
         }
         foreach ($post['textsize'] as $key => $value) {
            $config[$key] = array(
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