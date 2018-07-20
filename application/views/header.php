<!DOCTYPE html>
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- start: HEAD -->
	<head>
		<!-- start: META -->
		<meta charset="UTF-8">
        <title>Tugas Akhir</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

		<link rel="stylesheet" href="<?= base_url().'assets/plugins/font-awesome/css/font-awesome.min.css' ?>">
		<link rel="stylesheet" href="<?= base_url().'assets/fonts/style.css' ?>">
		<link rel="stylesheet" href="<?= base_url().'assets/css/datepicker.css' ?>">
		<link rel="stylesheet" href="<?= base_url().'assets/css/style.css' ?>">

		
		<link rel="stylesheet" href="<?= base_url().'assets/css/main-responsive.css' ?>">
		<link rel="stylesheet" href="<?= base_url().'assets/plugins/iCheck/skins/all.css' ?>">
		<link rel="stylesheet" href="<?= base_url().'assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css' ?>">
		<link rel="stylesheet" href="<?= base_url().'assets/css/theme_black_and_white.css" id="skin_color' ?>">
		<!-- end: MAIN CSS -->
		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
		<link rel="stylesheet" href="<?= base_url().'assets/plugins/fullcalendar/fullcalendar/fullcalendar.css' ?>">
		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
		<link rel="shortcut icon" href="<?= base_url().'favicon.ico' ?>" />

		<!-- DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="<?= base_url().'assets/plugins/DataTables-1.10.4/media/css/jquery.dataTables.css' ?>">

        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo base_url('assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="<?php echo base_url('assets/font-awesome-4.3.0/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="<?php echo base_url('assets/ionicons-2.0.1/css/ionicons.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url('assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css') ?>" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="<?php echo base_url('assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css') ?>" rel="stylesheet" type="text/css" />
		<!-- end: META -->
		<!-- start: MAIN CSS -->  

  		<!--tambahkan custom css disini-->
		<!-- iCheck -->
		<link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
		<!-- Morris chart -->
		<link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
		<!-- jvectormap -->
		<link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
		<!-- Date Picker -->
		<link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
		<!-- Daterange picker -->
		<link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
		<!-- bootstrap wysihtml5 - text editor -->
		<link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />

		<!-- jQuery -->
		<script type="text/javascript" charset="utf8" src="<?= base_url().'assets/plugins/DataTables-1.10.4/media/js/jquery.js' ?>"></script>
		<!-- DataTables -->
        <script type="text/javascript" charset="utf8" src="<?= base_url().'assets/plugins/DataTables-1.10.4/media/js/jquery.dataTables.js' ?>"></script>
		<script type="text/javascript" charset="utf8" src="<?= base_url().'assets/js/bootstrap-datepicker.js' ?>"></script>
		<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				$('#table_new').DataTable();
			} );
	    </script>
	</head>
	<!-- end: HEAD -->
	<!-- start: BODY -->
		<!-- start: BODY -->
	<body class="skin-blue">
		<!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <a href="#" class="logo">Tugas Akhir</a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
            
            </nav>
        </header>

		<!-- Left side column. contains the sidebar -->
		<aside class="main-sidebar">
		    <!-- sidebar: style can be found in sidebar.less -->
		    <section class="sidebar">
		        <!-- Sidebar user panel -->
		        <div class="user-panel">
		            <div class="pull-left image">
		                <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image" />
		            </div>
		            <div class="pull-left info">
		                <p>Taufiqur Rahman</p>

		                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
		            </div>
		        </div>
		        <!-- search form -->
		        <form action="#" method="get" class="sidebar-form">
		            <div class="input-group">
		                <input type="text" name="q" class="form-control" placeholder="Search..."/>
		                <span class="input-group-btn">
		                    <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
		                </span>
		            </div>
		        </form>
		        <!-- /.search form -->
		        <!-- sidebar menu: : style can be found in sidebar.less -->
		        <ul class="sidebar-menu">
		            <li class="header">MAIN NAVIGATION</li>
		            
		            <li class="<?php if($value == 'dashboard') echo 'active open'; ?>">
		            	<a href="<?= base_url().'index.php/home/dashboard'?>"><i class="fa fa-dashboard"></i> Dashboard</a>
		            </li>

		            <!-- <li class="<?php if($value == 'result') echo 'active open'; ?>">
		            	<a href="<?= base_url().'index.php/home/hasil'?>"><i class="fa fa-dashboard"></i> Result</a>
		            </li>   -->

		            <li class="<?php if($value == 'feedback') echo 'active open'; ?>">
		            	<a href="<?= base_url().'index.php/home/feedback'?>"><i class="fa fa-server"></i> Data Feedback</a>
		            </li>

		            <li class="<?php if($value == 'trend') echo 'active open'; ?>">
		            	<a href="<?= base_url().'index.php/home/trend'?>"><i class="fa fa-line-chart"></i> Trend Komentar</a>
		            </li>     

		        <!-- 	<li class="<?php if($value == 'literature') echo 'active open'; ?>">
		            	<a href="<?= base_url().'index.php/home/literature'?>"><i class="fa fa-book"></i> About Annikuper</a>
		            </li>   -->  

		             <!-- <li class="<?php if($value == 1 || $value == 2 || $value == 3) echo 'active open'; ?>">
		                <a href="#">
		                    <i class="fa fa-laptop"></i>
		                    <span>Data</span>
		                    <i class="fa fa-angle-left pull-right"></i>
		                </a>
		                <ul class="treeview-menu">
		                    <li class="<?php if($value == 1) echo 'active'; ?>"><a href="<?= base_url(). 'index.php/home/data/document'?>"><i class="fa fa-circle-o"></i> Social Media</a></li>
		                    <li class="<?php if($value == 2 || $value == 3) echo 'active'; ?>"><a href="<?= base_url(). 'index.php/home/data/comment'?>"><i class="fa fa-circle-o"></i> Comments</a></li>
		                </ul>
		            </li>  -->

		            <li class="<?php if($value == 4 || $value == 5 || $value == 6 || $value == 7) echo 'active open'; ?>">
		                <a href="#">
		                    <i class="fa fa-edit"></i> <span>TESTING</span>
		                    <i class="fa fa-angle-left pull-right"></i>
		                </a>
		                <ul class="treeview-menu">
		                 <!--   <li class="<?php if($value == 6) echo 'active'; ?>"><a href="<?= base_url(). 'index.php/home/trend_news'?>"><i class="fa fa-circle-o"></i> News Trend</a></li>
		                    <li class="<?php if($value == 4) echo 'active'; ?>"><a href="<?= base_url(). 'index.php/home/research/start'?>"><i class="fa fa-circle-o"></i> Commnets Trend</a></li>  -->
		                    <li class="<?php if($value == 5) echo 'active'; ?>"><a href="<?= base_url(). 'index.php/home/research/test'?>"><i class="fa fa-circle-o"></i> Sentiment Analysis</a></li> 


		                    <li class="<?php if($value == 7) echo 'active'; ?>"><a href="<?= base_url(). 'index.php/home/test_textmining'?>"><i class="fa fa-circle-o"></i> Text Mining</a></li>
		                </ul>
		            </li>
		           
		        </ul>
		    </section>
		    <!-- /.sidebar -->
		</aside>
<div class="content-wrapper">