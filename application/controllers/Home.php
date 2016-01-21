<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 控制器
 * @package     CI
 * @subpackage  Controllers
 * @category    Controllers
 * @author      ming.king
 */
class Home extends M_Controller{
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct(){
        parent::__construct();
        $this->load->model( 'Mdl_user' );
        $this->load->model( 'Mdl_grade' );
        $this->this_view_data['grade_data'] = $this->Mdl_grade->my_selects();
    }
    /**
     * 后台默认首页
     *
     * @access  public
     * @return  void
     */
    public function index(){
        $user_data = $this->Mdl_user->my_selects();
        $users_array ='';
        if( !empty($user_data) ){
            foreach($user_data as $key=>$value){
                if($value['status']==1 && empty($value['grade'])){
                    $users_array[] = $value['id'];
                }
            }
        }
        //echo '<pre>';print_r($users_array);exit;
        $this->this_view_data['users'] = empty($users_array)?'':implode(',',$users_array);
        $this->load->view('home',$this->this_view_data);
    }
    /**
     * 参与抽奖人员
     * @access  public
     * @return  void
     */
    public function user(){
        $filter = array();
        if( !empty($_GET['grade']) ){
            if( $_GET['grade'] == 'all' ){
            }else if( $_GET['grade'] == 'all_winner' ){
                $filter['custom'] = "grade != 0";
            }else{
                $filter['grade'] = $_GET['grade'];
            }
            $this->this_view_data['filter']['grade'] = $_GET['grade'];
        }
        $this->this_view_data['user_data'] = $this->Mdl_user->my_selects(0,0,$filter);
        $this->load->view('user',$this->this_view_data);
    }
    public function grade(){
        $this->load->view('grade',$this->this_view_data);
    }
    public function grade_add(){
        if( empty($_POST['name']) ){
            $this->session->set_flashdata('msg', '名称必须填写');
        }else{
            $this->Mdl_grade->my_insert( array('name'=>$_POST['name']) );
            $this->session->set_flashdata('msg', '新增成功');
        }
        redirect( site_url('home/grade') );
    }
    public function winner_add(){
        if( empty($_GET['id']) || empty($_GET['grade']) ){
            echo '无人中奖';exit;
        }
        $user = $this->Mdl_user->my_select($_GET['id']);
        if( empty($user) ){
            echo '无人中奖';exit;
        }
        $this->Mdl_user->my_update( $_GET['id'], array('grade'=>$_GET['grade']));
        $grade = $this->Mdl_grade->my_select($_GET['grade']);
        echo '<p>奖项：'.(empty($grade['name'])?'':$grade['name']).'</p><p>编号：'.$user['id'].'</p><p>姓名：'.$user['name'].'</p><p>手机：'.$user['phone'].'</p>';
    }
    public function winner_del_aj(){
        if(empty($_GET['id']) ){
            echo '无效的用户';exit;
        }
        $user = $this->Mdl_user->my_select($_GET['id']);
        if( empty($user) ){
            echo '无效的用户';exit;
        }
        $this->Mdl_user->my_update( $_GET['id'], array('grade'=>0) );
        echo '保存成功';exit;
    }
    public function status_edit_aj(){
        if(!isset($_GET['status']) || ($_GET['status'] != 0 && $_GET['status'] != 1) ){
            echo '无效的状态';exit;
        }
        if(empty($_GET['id']) ){
            echo '无效的用户';exit;
        }
        $user = $this->Mdl_user->my_select($_GET['id']);
        if( empty($user) ){
            echo '无效的用户';exit;
        }
        $this->Mdl_user->my_update( $_GET['id'], array('status'=>$_GET['status']));
        echo '保存成功';exit;
    }
    
}


