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



<!-- Content Header (Page header) -->
<section class="content-header">
	<div style="margin-left: 20px;">
		<h1>TESTING <small>Text Mining</small></h1>
	</div>
			
</section>



<!-- Main content -->
<section class="content">

		<!-- end: PAGE HEADER -->
		<?= form_open('textmining_document/test_textmining'); ?>
		<!-- start: PAGE CONTENT -->
		<div class="row" >
			<div class="panel-body"  style="margin-left:10px;">
				<div class="col-md-12">
					<textarea name="text_input" style="height: 150px; width: 700px;" class="textarea-test" placeholder="Masukkan Kalimat disini..." id="form-field-22" class="form-control"></textarea>
					<span class="help-block"><i class="icon-info-sign"></i> Gunakan Bahasa Indonesian!</span>
				</div>
				<!-- <div class="col-md-12">
					<label class="checkbox-inline">
						<input name="checkboxComment" type="checkbox" value="1"> This is comment sentence
					</label>
				</div> -->
				<div class="col-md-12">
					<button type="submit" name="checkboxComment" value="1" class="btn btn-primary">
						Test
					</button>
				</div>
			</div>
		</div>
		<?= form_close(); if($get_result == 1) { ?>
		<div class="row" style="margin-bottom: -30px;">
			<div class="panel-body">
				<div class="col-md-12">
					<p class="textmining-text"><?=  'STRING : '. $text; ?></p>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom: -30px; margin-top: -30px;">
			<div class="panel-body">
				<div class="col-md-12">
					<p class="textmining-text" style="text-align: center;">KEYWORDS</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<div class="panel-body">
						<?php if(!is_null($result)) { foreach($result as $res) { ?>
							<div class="col-xs-2 textmining-table textmining-result"><?= $res; ?></div>
						<?php }} else echo 'Empty'; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom: -30px; margin-top: -30px;">
			<div class="panel-body">
				<div class="col-md-12">
					<p class="textmining-text" style="text-align: center;">STOPLIST</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<div class="panel-body">
						<?php if(!is_null($stoplist)) { foreach($stoplist as $res) { ?>
							<div class="col-xs-2 textmining-table textmining-result"><?= $res; ?></div>
						<?php } } else echo 'Empty'; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom: -30px; margin-top: -30px;">
			<div class="panel-body">
				<div class="col-md-12">
					<p class="textmining-text" style="text-align: center;">STEMMING</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<div class="panel-body">
						<?php if(!is_null($imbuhan)) { foreach($imbuhan as $res) { ?>
							<div class="col-xs-3 textmining-table textmining-result"><?= $res; ?></div>
						<?php } } else echo 'Empty'; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if($this->session->userdata('behaviour') == 'comment') { ?>
		<div class="row" style="margin-bottom: -30px; margin-top: -30px;">
			<div class="panel-body">
				<div class="col-md-12">
					<p class="textmining-text" style="text-align: center;">KATA TIDAK BAKU</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<div class="panel-body">
						<?php if(!is_null($tidakBaku)) { foreach($tidakBaku as $res) { ?>
							<div class="col-xs-4 textmining-table textmining-result"><?= $res; ?></div>
						<?php } } else echo 'Empty'; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }} ?>
	

</section><!-- /.content -->




<!--tambahkan custom js disini-->
<!-- jQuery UI 1.11.2 -->
<script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Morris.js charts -->
<script src="<?php echo base_url('assets/js/raphael-min.js') ?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.min.js') ?>" type="text/javascript"></script>
<!-- Sparkline -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/sparkline/jquery.sparkline.min.js') ?>" type="text/javascript"></script>
<!-- jvectormap -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') ?>" type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/knob/jquery.knob.js') ?>" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js') ?>" type="text/javascript"></script>
<!-- datepicker -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js') ?>" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>" type="text/javascript"></script>
<!-- iCheck -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js') ?>" type="text/javascript"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/js/pages/dashboard.js') ?>" type="text/javascript"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/js/demo.js') ?>" type="text/javascript"></script>

