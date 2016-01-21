<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('base/header_base'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        欢迎参与抽奖
    </div>
    <div class="panel-body">
        <form class="form-inline" role="form" action="<?php echo site_url('sign/user_add');?>" method="post">
           <div class="form-group">
              <label class="sr-only" for="name">姓名</label>
              <input type="text" class="form-control" name="name" id="name" 
                 placeholder="请输入姓名">
           </div>
           <div class="form-group">
              <label class="sr-only" for="phone">手机号</label>
              <input type="text" class="form-control" name="phone" id="phone" 
                 placeholder="请输入手机号">
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