<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo base_url('application/images/icon.ico');?>">
    <title>good luck</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('application/styles/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('application/styles/jquery.mmenu.all.css');?>" rel="stylesheet"/>
    <link href="<?php echo base_url('application/styles/demo.css');?>" rel="stylesheet"/>
    <!--<script src="<?php echo base_url('application/js/luck/jquery-1.7.1.min.js');?>"></script>-->
    <script src="<?php echo base_url('application/js/jquery.js');?>"></script>
    <script src="<?php echo base_url('application/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('application/js/jquery.mmenu.min.all.js');?>"></script>
    <script type="text/javascript">
        $(function() {
            $('nav#menu').mmenu({
                slidingSubmenus: false,
                onClick: {
                    close: false,
                    setSelected: false,
                },
            });
        });
    </script>
  </head>
  <body>