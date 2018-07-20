
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

	<div class="" style="margin-left: 15px; margin-top: 30px;" >
            <form  class="form-inline" action="<?php echo base_url('index.php/home/hasil')?>" action="GET">
                <div class="form-group">
                <input type="text" class="form-control" id="cari" name="cari" placeholder="cari barang..." style="width: 240px;"> <input class="btn btn-primary" type="submit" value="Cari">
               
                </div>
                
            </form>
        </div>
   
</section>



<!-- Main content -->
<section class="content">
	
	
 
		<?php
 
		if(count($cari)>0)
		{ ?>

	 	<div class="row" style="margin-left: -15px; margin-top: 10px; margin-right: 20px;">
        <div class="panel-body">
			<?php foreach ($cari as $data) { ?>
			        
			          <div class="col-md-3 col-sm-4">
			            <span class="thumbnail">
			                <img src="<?php echo $data->url_img ?>" alt="...">
			                <h6><?php echo $data->nama_barang  ?></h6>
			               <!--  <p><?php echo $data->gender ?> -> <?php echo $data->kategori ?></p> -->
			                 <p class="price"><?php echo $data->harga  ?> </p>
			                <hr class="line">
			                <div class="row">
			        
			                 <div class="col-md-6 col-sm-6">
                        		<button class="btn btn-info right" ><?php echo $data->total_score ?></button>
                    		</div>
			                <div class="col-md-6 col-sm-6">
			                   <a href="<?php echo $data->url_site  ?>" target="_blank" >  <button class="btn btn-info right" > Go to link</button></a>
			                </div>
			                  
			                </div>
			            </span>
			          </div>
			    
		 <?php } ?>
		 </div>
		 </div>

			<?php } 

				else
				{
					echo "Barang tidak ditemukan";
				}
			?>

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

