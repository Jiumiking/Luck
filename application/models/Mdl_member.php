<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CI后台model
 * @category    model
 * @author      ming.king
 * @date        2015/11/26
 */
class Mdl_member extends MY_Model{
    /**
     * 构造函数
     *
     * @return  void
     */
    public function __construct(){
        parent::__construct();
        $this->my_select_field .= ',name_real,name_nick,password,phone,email,email_check,integral,sex,birthday';
        $this->my_table = 'member';
    }
    /**
     * 详情 by 电话号码 或 邮箱
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_select_username( $username = '' ){
        if( empty( $username ) ){
            return false;
        }
        $sql = "
            SELECT
                {$this->my_select_field}
            FROM
                {$this->db->dbprefix($this->my_table)}
            WHERE
                phone = '$username' OR email = '$username' OR name_nick = '$username'
        ";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        return $data;
    }
    /**
     * 详情 by 昵称
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_select_nick( $name_nick, $id = '' ){
        if( empty( $name_nick ) ){
            return false;
        }
        $sql = "
            SELECT
                {$this->my_select_field}
            FROM
                {$this->db->dbprefix($this->my_table)}
            WHERE
                name_nick = '$name_nick' ".(empty($id)?'':"AND id != $id")."
        ";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        return $data;
    }
}