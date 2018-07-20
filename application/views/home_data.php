<!-- start: PAGE -->
<div class="modal fade" id="importantwordsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php if($cat == 'document') echo 'Important Words'; else echo 'Document'; ?></h4>
      </div>
      <div class="modal-body">
        <p id="wordId" style="text-align : justify;"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="main-content">
	<div class="container">
		<!-- start: PAGE HEADER -->
		<div class="row">
			<div class="col-sm-12">
				<!-- start: PAGE TITLE & BREADCRUMB -->
				<div class="page-header">
					<h1>Social Media Data <small><?php if($cat == 'document') echo 'Documents / Articles'; else echo 'Comments'; ?></small></h1>
				</div>
				<!-- end: PAGE TITLE & BREADCRUMB -->
			</div>
		</div>
		<!-- end: PAGE HEADER -->

		<!-- start: PAGE CONTENT -->
		<?php if($show_score) { ?>
		<div class="row">
			<div class="panel-body">
				<div class="col-md-12">
					<p style="font-size: 25px;">Result : <?php echo $sum['vsum']; if($sum['vsum'] == 0) echo ', Objective'; elseif($sum['vsum'] > 0) echo ', Positive'; elseif($sum['vsum'] < 0) echo ', Negative'; ?></p>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-md-12">
				<!-- start: DYNAMIC TABLE PANEL -->
					<div class="panel-body">
						<table class="table table-striped table-bordered table-hover table-full-width" id="table_data">
							<thead>
								<tr>
									<?= $thead; ?>
								</tr>
							</thead>
							<tbody>
								<?php if($cat == 'document') { ?>
									<?php foreach ($result as $data) { if($data['document_title'] != '') { $data['kataPenting'] = implode(' - ', json_decode($data['kataPenting'])); ?>
										<tr>
											<td><?= $data['date_published'] ?></td>
											<td><?php if(strlen($data['document_title']) > 70) 
														echo substr($data['document_title'], 0,70).'...';
														else echo $data['document_title']; ?></td>
											<td class="center">
												<button type="button" class="btn btn-info importantwordsModal" data-word="<?= $data['kataPenting'] ?>" data-toggle="modal" data-target="#importantwordsModal">
													View Words
												</button>
											</td>
											<td><?= $data['name'] ?></td>
											<td class="center">
												<a target="_blank" class="btn btn-dark-grey btn-sm" href="<?= $data['link'] ?>">
													Go to link <i class="icon-circle-arrow-right"></i>
												</a>
											</td>
											<td class="center">
												<a target="_blank" class="btn btn-green btn-sm" href="<?= base_url().'index.php/home/view_comment/'.$data['document_id'] ?>">
													View Comments
												</a>
											</td>
										</tr>
								<?php } } } ?>
								<?php if($cat == 'comment') { ?>
									<?php foreach ($result as $data) { if($data['content'] != '') {  
										if($data['value'] == 0) $sentiment = 'Objective'; elseif($data['value'] > 0) $sentiment = 'Positive'; elseif($data['value'] < 0) $sentiment = 'Negative';?>
										<tr>
											<?php if($value == 2) { ?>
												<td><?= $data['document_id'] ?></td>
												<td class="center">
													<button type="button" class="btn btn-info importantwordsModal" data-word="<?= $data['html'] ?>" data-toggle="modal" data-target="#importantwordsModal">
														View Doc
													</button>
												</td>
											<?php } ?>
											<td><?= $data['content'] ?></td>
											<td><?= $data['value'] ?></td>
											<td><?= $sentiment ?></td>
										</tr>
								<?php } } } ?>
							</tbody>
						</table>
					</div>
				
				<!-- end: DYNAMIC TABLE PANEL -->
			</div>
		</div>
	</div>
	
</div>
			<!-- end: PAGE -->
</div>
<!-- end: MAIN CONTAINER -->
</div>