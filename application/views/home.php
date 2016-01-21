<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('base/header'); ?>
<div id="page">
    <div class="header">
        <a href="#menu"></a>
        年会抽奖系统
    </div>
    <div class="container">
        <p>
            <select class="form-control" name="grade" id="grade">
                <?php if(!empty($grade_data)){ ?>
                <?php foreach($grade_data as $value){ ?>
                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
        </p>
        <div id="user_list" class="user_list">
            <canvas id="tutorial"></canvas>
            <div id="stop_button"><a class="btn btn-danger btn-lg" href="javascript:void(0);">走</a></div>
        </div>
    </div>
    <?php $this->load->view('base/menu'); ?>

</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true" data-backdrop="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" 
               aria-hidden="true">×
            </button>
            <h4 class="modal-title" id="msgModalLabel">
               中奖信息
            </h4>
         </div>
         <div class="modal-body" id="msgShow">
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">
               关闭
            </button>
         </div>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    var users = '<?php echo empty($users)?'':$users; ?>';
    var winnerAddUrl = '<?php echo site_url('home/winner_add'); ?>';
</script>
<script type="text/javascript" src="<?php echo base_url('application/js/luck/lottery.js');?>"></script>
</body>
</html>