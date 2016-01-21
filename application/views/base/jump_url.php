<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Error</title>
<style type="text/css">

::selection{ background-color: #E13300; color: white; }
::moz-selection{ background-color: #E13300; color: white; }
::webkit-selection{ background-color: #E13300; color: white; }

body {
    background-color: #fff;
    margin: 40px;
    font: 13px/20px normal Helvetica, Arial, sans-serif;
    color: #4F5155;
}

a {
    color: #003399;
    background-color: transparent;
    font-weight: normal;
}

h1 {
    color: #444;
    background-color: transparent;
    border-bottom: 1px solid #D0D0D0;
    font-size: 19px;
    font-weight: normal;
    margin: 0 0 14px 0;
    padding: 14px 15px 10px 15px;
}
h1 .red {
    color: red;
}

code {
    font-family: Consolas, Monaco, Courier New, Courier, monospace;
    font-size: 12px;
    background-color: #f9f9f9;
    border: 1px solid #D0D0D0;
    color: #002166;
    display: block;
    margin: 14px 0 14px 0;
    padding: 12px 10px 12px 10px;
}

#container {
    margin: 10px;
    border: 1px solid #D0D0D0;
    -webkit-box-shadow: 0 0 8px #D0D0D0;
}

p {
    margin: 12px 15px 12px 15px;
}
</style>
<script src="<?php echo base_url();?>application/js/jquery.js"></script>
</head>
<body>
    <div id="container">
        <h1><span class="red"><?php echo $message; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span id="timeshow"><?php echo $time;?></span>秒后跳转，<a href="<?php echo $url;?>">或点击这里跳转</a></h1>
    </div>
</body>
<script type="text/javascript">
$(document).ready(function(){
    
    timeShow(<?php echo $time;?>);
});
function timeShow(time){
    if(time>0){
        $("#timeshow").html(time);
        time--;
        setTimeout("timeShow("+time+");",1000);
    }else{
        window.location.href= '<?php echo $time;?>';
    }
}
</script>
</html>