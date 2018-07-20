<!DOCTYPE html>
<html>
<head>
	<!-- <title></title> -->
<style>
#string {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#string td, #string th {
    border: 1px solid #ddd;
    padding: 8px;
}

/*#string tr:nth-child(even){background-color: #f2f2f2;}*/

#string th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #367fa9;
    color: white;
}
</style>


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


</head>
<body>


<!-- Content Header (Page header) -->
<section class="content-header">
   <div style="margin-left: 20px;">
		<h1>TESTING <small>Sentiment Analysis</small></h1>
	</div>
</section>



<!-- Main content -->
<section class="content">

		<?= form_open('textmining_comment/result'); ?>
		<!-- start: PAGE CONTENT -->
		<div class="row">
			<div class="panel-body" style="margin-left:10px;">
				<div class="col-md-12 form-group">
					<textarea name="text_input"  style="height: 150px; width: 700px;" class="textarea-test" placeholder="Masukkan Kalimat disini..." id="form-field-22" class="form-control"></textarea>
					<span class="help-block"><i class="icon-info-sign"></i> Gunakan Bahasa Indonesian!</span>
				</div>
				<div class="col-md-12 form-group" style="margin-top: -15px;">
					<button type="submit" name="submit" value="Test" class="btn btn-primary">
						Test
					</button>
				</div>
			</div>
		</div>
		<?= form_close(); ?>

		<?php if($show_result) { ?>
			<div class="row">
				<div class="panel-body" style="margin-left: 20px; margin-right:20px; ">
					<h4><?= 'STRING : '.$string; ?></h4><BR/>
					<table id="string">
						<thead>
				        	<tr>
				        	  <th>No.</th>
				        	  <th>String</th>
				        	  <th>Word Type</th>
				        	  <th>Value</th>
				        	</tr>
				      	</thead>
				      	<tbody>
				      		<?php $i = 0; foreach ($array_info as $data) { ?>
					        <tr class="<?php if($valued_index_array[$i] == 's') echo 'single-result'; 
					        elseif($valued_index_array[$i] == 'l') echo 'logic-result'; ?>">
					          	<td><?= $i+1; ?></td>
					          	<td><?= $data['word']; ?></td>
					          	<td><?= $data['type']; ?></td>
					          	<td><?= $data['value']; ?></td>
					        </tr>
					        <?php $i++; } ?>
					    </tbody>
				    </table>
				    <div class="well" style="margin-top: 30px;">
					    <h4>Result:</h4>
					    <h4>
					    <?php for($i = 0; $i < count($array_score); $i++) {
					    	if(count($array_score) == 1)
								echo $score;
							elseif($i != count($array_score)-1)
								echo $array_score[$i].' + ';
							else
								echo $array_score[$i].' = '.$score;
						} 
						echo '</h4>'; 
						if($score > 0) 
							echo '<h4 class="text-info">Positive</h4>'; 
						elseif($score < 0) 
							echo '<h4 class="text-danger">Negative</h4>'; 
						else 
							echo '<h4 class="text-success">Objective</h4>'; ?>
					</div>
				</div>
			</div>
		<?php } ?>


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

</body>
</html>