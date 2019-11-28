<?php
class Products_model extends MY_Model {

   private $searchCol = array(
      'product.product_id',
      'name',
      'content',
      'catalog',
      'image_link',
      '',
      'maker_id',
      'price',
      'created',
      'view',
      'total',
      '',
   );

   public function __construct()
   {
      $this->load->database();
   }
   // Sử lý Datatable
   public function get_product_datatable($params)
   {  
      $this->_filter($params);
      $this->search($params['search']);
      $this->db->limit($params['length'], $params['start']);
      $this->db->order_by($this->searchCol[$params['columns']], $params['order']);
      $query = $this->db->get('product');
      return $query->result();
   }
   public function countALL($params){
      $this->_filter($params);
      $this->search($params['search']);
      $query = $this->db->get('product');
      return count($query->result());
   }
   public function _filter($params){
      if (!empty($params['catalog'])) {
         $this->db->where('catalog',$params['catalog']);
      }
      if (!empty($params['maker_id'])) {
         $this->db->where('maker_id',$params['maker_id']);
      }
      if (!empty($params['size'])) {
         $this->db->select('*')->join('size', 'size.product_id = product.product_id');
         $this->db->where('text_size',$params['size']);
      }
   }
   public function search($search){
      if (!empty($search)) {
         $dem = 0;
         foreach($this->searchCol as $col){
            if ($dem < 1) {
               $this->db->like($col,$search);   
            } else {
               if ($this->searchCol[$dem] != '') {
                  $this->db->or_like($col,$search);
               }
            }
            $dem++;
         }
      }
   }
   //----------- END CODE Datatable-------------------
   // lấy size cho database
   public function list_size_datatable(){
      $this->db->select('text_size');
      $this->db->distinct();
      $query = $this->db->get('size');
      return $query->result();
   }
   // lấy ra số bản ghi(số bản gi, điểm bắt đầu)
   public function getList($total, $start){
      $this->db->limit($total, $start);
      $query = $this->db->get('product');// get('product')
      return $query->result_array();
   }
   public function count(){
      return $this->db->count_all("product");
   }
   // lấy ra size theo đi product
   public function get_size($id){
      $this->db->select('text_size')->from('size')->where('size.product_id',$id);
      $query = $this->db->get();
      return $query->result();
   }


   public function set_products($data)
   {
      $this->db->insert('product', $data);
      $insert_id = $this->db->insert_id();
      return $insert_id;
   }
   public function set_size($data)
   {
      $this->db->insert('size', $data);
   }
   // get json form update
   public function get_json($id){
      $this->db->where('product.product_id',$id);
      $query = $this->db->get('product');
      return $query->row();
   }
   public function get_size_come_product($id){
      $this->db->select('*')->from('size')->where('size.product_id',$id);
      $query = $this->db->get();
      return $query->result();
   }

   public function update_products($data, $id)
   {
      $this->db->where("product_id",$id);
      return $this->db->update("product", $data);
   }

   public function delete_size($id){
      $this->db->where("product_id",$id);
      return $this->db->delete("size");
   }
   public function delete_product($id){
      $this->db->where("product_id",$id);
      $this->db->delete("detail_order");
      $this->db->where("product_id",$id);
      $this->db->delete("size");
      $this->db->where("product_id",$id);
      return $this->db->delete("product");
   }
}