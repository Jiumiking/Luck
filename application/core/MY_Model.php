<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Model公用
 * @category    model
 * @author      ming.king
 * @date        2015/11/26
 */
class MY_Model extends CI_Model{
    /**
     * 当前控制器
     * @access  protected
     **/
    protected $my_table = '';
    /**
     * 详情字段
     * @access  protected
     **/
    protected $my_select_field = 'id,date_add,date_edit,status';
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct(){
        parent::__construct();
    }
    /**
     * 设置表名
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_table_set( $table ){
        $this->my_table = $table;
    }
    /**
     * 设置表字段
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_select_field_set( $field ){
        $this->my_select_field = $field;
    }
    /**
     * 详情
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_select( $id = '' ){
        if( empty( $id ) ){
            return false;
        }
        $sql = "
            SELECT
                {$this->my_select_field}
            FROM
                {$this->db->dbprefix($this->my_table)}
            WHERE
                id = '$id'
        ";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        return $data;
    }
    /**
     * 列表
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_selects( $num=0, $limit=0, $where=array(), $order_by='id DESC' ){
        $_where = '';
        if( !empty($where) ){
            $_where = $this->my_where($where);
        }
        $_limit = '';
        if( !empty($num) ){
            $_limit = "LIMIT $limit,$num";
        }
        $sql = "
            SELECT
                {$this->my_select_field}
            FROM
                {$this->db->dbprefix($this->my_table)}
            WHERE
                1
                $_where
            ORDER BY
                $order_by
            $_limit
        ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }
    /**
     * 列表数量
     *
     * @param   mixed
     * @return  object
     */
    public function my_count( $where=array() ){
        $_where = '';
        if(!empty($where)){
            $_where = $this->my_where($where);
        }
        $sql = "
            SELECT
                count(1) AS count
            FROM
                {$this->db->dbprefix($this->my_table)}
            WHERE
                1
                $_where
        ";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        if(!empty($data['count'])){
            return $data['count'];
        }
        return 0;
    }
    /**
     * 列表条件处理
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    protected function my_where( $where=array() ){
        if(empty($where)){
            return '';
        }
        $return = '';
        foreach($where as $key=>$value){
            if( !empty($value) ){
                $value = str_replace('.','\.',$value);
                $value = str_replace('%','\%',$value);
                if( $key == 'custom' ){
                    $return .= " AND $value";
                }else{
                    $return .= ' AND '.$key." = '$value'";
                }
            }
        }
        return $return;
    }
    /**
     * 修改
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_update( $id = '', $data = '' ){
        if(empty($id) || empty($data)){
            return false;
        }
        $data['date_edit'] = date('Y-m-d H:i:s');
        return $this->db->update( $this->my_table, $data, array('id' => $id) );
    }
    /**
     * 新增
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_insert( $data = '' ){
        if( empty($data) ){
            return false;
        }
        $data['date_add'] = date('Y-m-d H:i:s');
        return $this->db->insert( $this->my_table, $data );
    }
    /**
     * 批量新增
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_inserts( $data = '' ){
        if( empty($data) ){
            return false;
        }
        foreach( $data as $key=>$value ){
            $data[$key]['date_add'] = date('Y-m-d H:i:s');
        }
        return $this->db->insert_batch( $this->my_table, $data );
    }
    /**
     * 删除
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public function my_delete( $id = '' ){
        if( empty($id) ){
            return false;
        }
        return $this->db->delete( $this->my_table, array('id' => $id) );
    }
}