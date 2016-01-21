<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CI后台控制器基类
 *
 * @package     CI
 * @subpackage  core
 * @category    core
 * @author      ming.king
 * @link
 */
class M_Controller extends P_Controller{
    /**
     * 构造函数
     * @access  public
     * @return  void
     */
    public function __construct(){
        parent::__construct();
        if ( !$this->session->this_user ){
            redirect( site_url('sign/signin') );
        }
    }
}
/**
 * 基类
 *
 * @package     CI
 * @subpackage  core
 * @category    core
 * @author      ming.king
 * @link
 */
class P_Controller extends CI_Controller{
    /**
     * 保存当前登录用户的信息
     *
     * @var object
     * @access  public
     **/
    protected $this_user = NULL;
    /**
     * ajax返回数组
     *
     * @var string
     * @access  protected
     **/
    protected $ajax_data = array(
        'sta' => '0',
        'msg' => '操作失败',
        'dat' => '',
    );
    /**
     * 当前控制器
     * @access  protected
     **/
    protected $this_controller = '';
    /**
     * 当前model
     * @access  protected
     **/
    protected $this_model = '';
    /**
     * 每页数量
     * @access  protected
     **/
    protected $this_page_size = '';
    /**
     * 输出变量
     * @var object
     * @access  public
     **/
    protected $this_view_data = array();
    /**
     * 保存当前设置信息
     * @var object
     * @access  public
     **/
    protected $this_setting = array();
    /**
     * 构造函数
     * @access  public
     * @return  void
     */
    public function __construct(){
        parent::__construct();
        require_once('MY_Function.php');
        $this->load->model( 'Mdl_user' );

        $this->this_controller = $this->uri->rsegment(1);
        $this->this_model = 'Mdl_'.$this->this_controller;
        if( file_exists(APPPATH.'models/'.$this->this_model.'.php') ){
            $this->load->model( $this->this_model );
        }
        $this->this_view_data['this_controller'] = $this->this_controller;
        $this->this_view_data['_js'][] = 'jquery';
        $this->this_view_data['_js'][] = 'authen';
        $this->this_view_data['_css'][] = 'reset';
        $this->this_view_data['_css'][] = 'style';

        $this->this_user_set();
    }
    /**
     * 检查用户是否登录
     * @access  protected
     * @return  void
     */
    private function this_user_set(){
        if ( $this->session->this_user ){
            $this->this_user = $this->session->this_user;
            $this->this_view_data['this_user'] = $this->session->this_user;
        }
    }
    /**
     * 用户信息更新
     * @access  protected
     * @return  bool
     */
    protected function this_user_reset(){
        if ( $this->session->this_user ){
            $this->load->model('mdl_member');
            $this_user = $this->mdl_member->my_select($this->session->this_user['id']);
            if( !empty($this_user) ){
                $this->session->this_user = $this_user;
                $this->this_user_set();
            }
        }
    }
    /**
     * 接口结束返回
     * @access  protected
     * @return  bool
     */
    protected function ajax_end(){
        echo json_encode($this->ajax_data);
        exit;
    }
    /**
     * 配置信息
     * @access  protected
     * @return  void
     */
    private function get_this_setting(){
        $this->this_setting = $this->mdl_setting->get_settings();
        $this->this_view_data['this_setting'] = $this->this_setting;
    }
    /**
     * 跳转方法
     * @access  protected
     * @return  void
     */
    protected function jump_url($url='', $message='跳转中！', $time=3){
        $this_view_data['url'] = empty($url)?$_SERVER['HTTP_REFERER']:$url;
        $this_view_data['message'] = $message;
        $this_view_data['time'] = $time;
        $retrun = $this->load->view('base/jump_url',$this_view_data,true);
        echo $retrun;
        exit;
    }
}