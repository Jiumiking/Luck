<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CI后台model
 * @category    model
 * @author      ming.king
 * @date        2015/11/26
 */
class Mdl_user extends MY_Model{
    /**
     * 构造函数
     *
     * @return  void
     */
    public function __construct(){
        parent::__construct();
        $this->my_select_field .= ',name,phone,grade';
        $this->my_table = 'user';
    }
    public function my_select_by_phone($phone){
        if( empty( $phone ) ){
            return false;
        }
        $sql = "
            SELECT
                {$this->my_select_field}
            FROM
                {$this->db->dbprefix($this->my_table)}
            WHERE
                phone = '$phone'
        ";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        return $data;
    }
}