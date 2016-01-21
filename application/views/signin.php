<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('base/header_base'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        欢迎登录抽奖系统
    </div>
    <div class="panel-body">
        <form class="form-inline" role="form" action="<?php echo site_url('sign/signin_do');?>" method="post">
           <div class="form-group">
              <label class="sr-only" for="username">用户名</label>
              <input type="text" class="form-control" name="username" id="username" 
                 placeholder="请输入用户名">
           </div>
           <div class="form-group">
              <label class="sr-only" for="password">密码</label>
              <input type="password" class="form-control" name="password" id="password" 
                 placeholder="请输入密码">
           </div>
           <div class="form-group" style="color:red;">
              <?php echo empty($msg)?'':$msg; ?>
           </div>
           <button type="submit" class="btn btn-default">提交</button>
        </form>
   </div>
</div>
</body>
</html>