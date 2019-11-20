<?php
class Products_model extends MY_Model {

   public function __construct()
   {
      $this->load->database();
   }
   public function get_news()
   {
      $query = $this->db->get('product');
      return $query->result();
   }
   // đếm số bản ghi
   public function countALL(){
      return $this->db->count_all("product");
   }
   // lấy ra số bản ghi(số bản gi, điểm bắt đầu)
   public function getList($total, $start){
      // $this->db->select('*')
      //          ->from('product')
      //          ->join('size','product.product_id = size.product_id');
      $this->db->limit($total, $start);
      $query = $this->db->get('product');// get('product')
      return $query->result_array();
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

   public function get_json($id){
      $this->db->select('*')
               ->from('product')
               ->join('size','product.product_id = size.product_id')
               ->where('product.product_id',$id);

      $query = $this->db->get()->result();
      if ($query) {
         return $query;
      }
      else{
         $this->db->where("product_id",$id);
         $query = $this->db->get('product');
         return $query->result();// lấy ra row mất công sửa js
      }
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