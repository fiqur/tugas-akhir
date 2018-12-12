<style>
    #table_new td, #table_new th {
        border: 0px solid #ddd;
        padding: 8px;
    }

    #table_new tr:nth-child(even){background-color: #f2f2f2;}

    #table_new tr:hover {background-color: #ddd;}

    #table_new th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #3c8dbc;
        color: white;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
   <div style="margin-left: 20px;">
		<h1>Data feedback<small> Ulasan produk</small></h1>
	</div>
</section>

<!-- Main content -->
<section class="content">
	<div class="row" style="margin-left: 10px; margin-right: 10px;" >
	<div class="panel-body">
	  <table id="table_new" width="100%">
	    <thead>
	      <tr>
	      	<!-- <th>No</th>
	      	<th>Id Barang</th> -->
	      	<th>Tanggal</th>
	        <th>Feedback</th>   
	        <th>Kata Penting</th>
	        <th>Score</th>
	      </tr>
	    </thead>
	    <tbody>
	    <?php
	    foreach($feedback as $f){ ?>
	      <tr>
	        <td width="15%"><?php echo $f->tanggal ?></td>
	        <td><?php echo $f->feedback_product ?></td>
	        <td><?php echo $kata_penting = implode(' - ', json_decode($f->kata_penting));?></td>
	        <td width="15%"><?php echo $f->score ?></td>
	      </tr>
	      <?php } ?>
	    </tbody>
	  </table>
	  </div>
	</div>
</section><!-- /.content -->

