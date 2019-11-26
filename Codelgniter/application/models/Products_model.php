<?php
class Products_model extends MY_Model {

   private $searchCol = array('name','product_id','content');

   public function __construct()
   {
      $this->load->database();
   }
   
   public function get_product_datatable($dataTable)
   {
      $this->search($dataTable['search']);
      $this->db->limit($dataTable['length'], $dataTable['start']);
      $query = $this->db->get('product');
      return $query->result();
   }
   // đếm số bản ghi
   public function countALL($search){
      $this->search($search);
      $query = $this->db->get('product');
      return count($query->result());
   }
   public function search($search){
      if (!empty($search)) {
         foreach($this->searchCol as $col){
            if (count($this->searchCol) == 1) {
               $this->db->like($col,$search);   
            } else {
               $this->db->or_like($col,$search);            
            }
         }
      }
   }
   // lấy ra số bản ghi(số bản gi, điểm bắt đầu)
   public function getList($total, $start){
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