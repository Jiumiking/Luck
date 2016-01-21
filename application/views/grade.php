<?php $this->load->view('base/header_base'); ?>
        <div id="page">
            <div class="header">
                <a href="#menu"></a>
                年会抽奖系统
            </div>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        新增奖项
                    </div>
                    <div class="panel-body">
                        <form class="form-inline" role="form" action="<?php echo site_url('home/grade_add');?>" method="post">
                           <div class="form-group">
                              <label class="sr-only" for="name">奖项名称</label>
                              <input type="text" class="form-control" name="name" id="name" 
                                 placeholder="请输入奖项名称">
                           </div>
                           <div class="form-group" style="color:red;">
                              <?php echo empty($msg)?'':$msg; ?>
                           </div>
                           <button type="submit" class="btn btn-default">新增</button>
                        </form>
                   </div>
                </div>
                <table class="table">
                   <thead>
                      <tr>
                         <th>编号</th>
                         <th>奖项名称</th>
                         <th>操作</th>
                      </tr>
                   </thead>
                   <tbody>
                      <?php if(!empty($grade_data)){ ?>
                      <?php foreach( $grade_data as $key=>$value){ ?>
                      <tr>
                         <td><?php echo $value['id']?></td>
                         <td><?php echo $value['name']?></td>
                         <td><?php
                            if($value['status'] == 1){
                                echo '<a href="javascript:void(0);" onclick="status('.$value['id'].',0);">有效</a>';
                            }else if($value['status'] == 0){
                                echo '<a href="javascript:void(0);" onclick="status('.$value['id'].',1);">无效</a>';
                            }else if($value['status'] == 5){
                                echo '<a href="javascript:void(0);" onclick="status('.$value['id'].',1);" class="red">一等奖</a>';
                            }else if($value['status'] == 6){
                                echo '<a href="javascript:void(0);" onclick="status('.$value['id'].',1);" class="red">二等奖</a>';
                            }else if($value['status'] == 7){
                                echo '<a href="javascript:void(0);" onclick="status('.$value['id'].',1);" class="red">三等奖</a>';
                            }else if($value['status'] == 8){
                                echo '<a href="javascript:void(0);" onclick="status('.$value['id'].',1);" class="red">幸运奖</a>';
                            } ?>
                         </td>
                      </tr>
                      <?php } ?>
                      <?php } ?>
                   </tbody>
                </table>
            </div>
            <?php $this->load->view('base/menu'); ?>
        </div>
        <script type="text/javascript">
        function status(id, val){
            if(val == 0){
                if(!confirm('确定拉黑？')){
                    return true;
                }
            }
            if(val == 1){
                if(!confirm('确定恢复正常状态？')){
                    return true;
                }
            }
            $.ajax({
                type : "GET",
                async : false,
                url : "<?php echo site_url('home/status');?>",
                data : { id:id,status:val },
                success : function(msg){
                    if(msg){
                        window.location.href="<?php echo site_url('home/user'); ?>"; 
                    }
                }
            });
        }
        </script>
    </body>
</html>