<?php $this->load->view('base/header_base'); ?>
        <div id="page">
            <div class="header">
                <a href="#menu"></a>
                年会抽奖系统
            </div>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-inline" role="form" action="<?php echo site_url('home/user');?>" method="get">
                            <div class="form-group">
                                <label class="sr-only" for="name">参与人员</label>
                                <select class="form-control" name="grade" id="grade">
                                    <option value="all" <?php if(!empty($filter['grade']) && $filter['grade']=='all' ){ ?>selected<?php } ?> >全部人员</option>
                                    <option value="all_winner" <?php if(!empty($filter['grade']) && $filter['grade']=='all_winner' ){ ?>selected<?php } ?> >全部获奖人员</option>
                                    <?php if(!empty($grade_data)){ ?>
                                    <?php foreach($grade_data as $value){ ?>
                                    <option value="<?php echo $value['id']; ?>" <?php if(!empty($filter['grade']) && $filter['grade']==$value['id'] ){ ?>selected<?php } ?> ><?php echo $value['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group" style="color:red;">
                                <?php echo empty($msg)?'':$msg; ?>
                            </div>
                            <button type="submit" class="btn btn-default">查询</button>
                        </form>
                    </div>
                </div>
                <table class="table">
                   <thead>
                      <tr>
                         <th>编号</th>
                         <th>姓名</th>
                         <th>手机号</th>
                         <th>状态</th>
                      </tr>
                   </thead>
                   <tbody>
                      <?php if(!empty($user_data)){ ?>
                      <?php foreach( $user_data as $key=>$value){ ?>
                      <tr <?php if($value['status'] == 0){echo 'class="active"';}else if( !empty($value['grade']) ){echo 'class="danger"';} ?>>
                         <td><?php echo $value['id']?></td>
                         <td><?php echo $value['name']?></td>
                         <td><?php echo $value['phone']?></td>
                         <td><?php
                            if($value['status'] == 0){
                                echo '<a href="javascript:void(0);" onclick="status_edit('.$value['id'].',1);">无效</a>';
                            }else if( $value['status'] == 1 && empty($value['grade']) ){
                                echo '<a href="javascript:void(0);" onclick="status_edit('.$value['id'].',0);">有效</a>';
                            }else if( $value['status'] == 1 && !empty($value['grade']) ){
                                foreach( $grade_data as $gd ){
                                    if( $gd['id'] == $value['grade'] ){
                                        echo '<a href="javascript:void(0);" onclick="winner_del('.$value['id'].');" class="red">'.$gd['name'].'</a>';
                                    }
                                }
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
        function status_edit(id, val){
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
                url : "<?php echo site_url('home/status_edit_aj');?>",
                data : { id:id,status:val },
                success : function(msg){
                    if(msg){
                        window.location.href="<?php echo site_url('home/user'); ?>"; 
                    }
                }
            });
        }
        function winner_del(id){
            if(!confirm('确定取消中奖？')){
                return true;
            }
            $.ajax({
                type : "GET",
                async : false,
                url : "<?php echo site_url('home/winner_del_aj');?>",
                data : { id:id },
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