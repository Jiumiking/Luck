<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 登录、注册控制器
 * @package     CI
 * @subpackage  Controllers
 * @category    Controllers
 * @author      ming.king
 */
class Sign extends P_Controller{
    /**
     * 构造函数
     * @access  public
     * @return  void
     */
    public function __construct(){
        parent::__construct();
        $this->load->model('Mdl_member');
    }
    /**
     * 默认首页
     * @access  public
     * @return  void
     */
    public function index(){
        $this->signin();
    }
    /**
     * 登录
     * @access  public
     * @return  void
     */
    public function signin(){
        if ( $this->session->this_user ){
            redirect( site_url('home/index') );
        }
        $this->this_view_data['msg'] = $this->session->flashdata('msg');
        $this->load->view('signin',$this->this_view_data);
    }
    /**
     * 登录
     * @access  public
     * @return  void
     */
    public function signin_do(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username && $password){
            $this_user = $this->Mdl_member->my_select_username($username);
            if ( $this_user ){
                if( $this_user['status'] == '1' ){
                    if( $this_user['password'] == password_encrypt($password) ){
                        $this->session->this_user = $this_user;
                        redirect( site_url('home'));
                    }else{
                        $this->session->set_flashdata('msg', '密码错误');
                    }
                }else if( $this_user['status'] == '2' ){
                    $this->session->set_flashdata('msg', '该账号已锁定');
                }else if( $this_user['status'] == '3' ){
                    $this->session->set_flashdata('msg', '该账号已注销');
                }
            }else{
                $this->session->set_flashdata('msg', '账号不存在');
            }
        }else{
            $this->session->set_flashdata('msg', '用户名密码不能为空');
        }
        redirect( site_url('sign/signin') );
    }
    /**
     * 登出
     * @access  public
     * @return  void
     */
    public function signout(){
        $this->session->sess_destroy();
        redirect( site_url('sign/signin'));
    }
    public function joinus(){
        $this->this_view_data['msg'] = $this->session->flashdata('msg');
        $this->load->view('joinus',$this->this_view_data);
    }
    public function user_add(){
        if( empty($_POST['name']) ){
            $this->session->set_flashdata('msg', '姓名必须填写');
            redirect( site_url('sign/joinus') );
        }
        if( empty($_POST['phone']) ){
            $this->session->set_flashdata('msg', '手机号必须填写');
            redirect( site_url('sign/joinus') );
        }
        $data['name'] = $_POST['name'];
        $data['phone'] = $_POST['phone'];
        if(!preg_match("/^1[0-9][0-9]\\d{8}$/",$data['phone'])){
            $this->session->set_flashdata('msg', '请输入正确的手机号');
            redirect( site_url('sign/joinus') );
        }
        $user = $this->Mdl_user->my_select_by_phone($data['phone']);
        if( $user ){
            $this->session->set_flashdata('msg', '您的编号是:'.$user['id'].',Good Luck');
            redirect( site_url('sign/joinus') );
        }
        if($this->Mdl_user->my_insert($data)){
            $id = $this->db->insert_id();
            $this->session->set_flashdata('msg', '您的编号是:'.$id.',Good Luck');
            redirect( site_url('sign/joinus') );
        }else{
            $this->session->set_flashdata('msg', '参与失败');
            redirect( site_url('sign/joinus') );
        }
    }
}


