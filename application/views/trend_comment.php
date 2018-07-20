<div class="modal fade" id="importantwordsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= 'Important Words' ?></h4>
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
<!-- start: PAGE -->
<div class="main-content">
	<div class="container">
		<?= form_open('research'); ?>
		<div class="row" style="margin-bottom: -35px;">
			<div class="panel-body">
				<div class="col-md-7">
					<div class="form-group">
						<div class="input-group">
			  				<div class="input-group-addon">Keyword</div>
			  				<input name="keyword" class="form-control" placeholder="Insert keyword here">
						</div>
					</div>
      			</div>
			</div>
		</div>
		<div class="row" style="margin-bottom: -40px;">
			<div class="panel-body">
				<div class="col-md-7">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="clip-stats"></i>
								Advanced Search
							<div class="panel-tools">
								<a class="btn btn-xs btn-link panel-collapse expand" href="#">
								</a>
							</div>
						</div>
						<div class="panel-body" style="display: none;">
							<div class="flot-xsmall-container">
								<div class="form-horizontal">
									<div class="form-group" style="margin-bottom: 0px;">
										<label class="col-sm-2 control-label label-form" for="form-field-1" style="margin-top: 5px;">
											Portal
										</label>
										<div class="col-sm-9">
											<?php foreach($portal_list as $portal) { ?>
												<label class="checkbox-inline">
													<input name="checkboxPortal[]" type="checkbox" value="<?= $portal['portal_id'] ?>" checked> <?= $portal['name'] ?>
												</label>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="form-horizontal">
									<div class="form-group" style="margin-bottom: 0px;">
										<label class="col-sm-2 control-label label-form" for="form-field-1" style="margin-top: 5px;">
											Sentiment
										</label>
										<div class="col-sm-9">
											<label class="radio-inline">
												<input name="radioSentiment" type="radio" id="radioSentiment1" value="1"> Positive only
											</label>
											<label class="radio-inline">
												<input name="radioSentiment" type="radio" id="radioSentiment2" value="2"> Negative only
											</label>
											<label class="radio-inline">
												<input name="radioSentiment" type="radio" id="radioSentiment3" value="3" checked> All
											</label>
										</div>
									</div>
								</div>
								<div class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-2 control-label label-form" for="form-field-1">
											Time
										</label>
										<div class="col-sm-9">
											<label class="radio-inline" style="padding-top: 3px">
  												<input type="radio" name="radioTime" id="inlineRadio1" value="1"> By Interval
											</label>
											<label class="radio-inline" style="padding-top: 3px">
												<input type="radio" name="radioTime" id="inlineRadio2" value="2"> By Year
											</label>
											<label class="radio-inline" style="padding-top: 3px">
												<input type="radio" name="radioTime" id="inlineRadio2" value="3" checked> All Year
											</label>
											<br/>
											<div class="row byYear hide">
												<div class="col-md-5">
													<label class="control-label" style="padding-top: 3px">
		  												From
													</label>
													<div class="input-group" style="width: 150px;">
														<input name="interval_date_from" type="text" class="form-control span2 datepicker" value="<?= date('m/d/Y'); ?>">
														<span class="input-group-addon"> <i class="icon-calendar"></i> </span>
													</div>
												</div>
												<div class="col-md-5">
													<label class="control-label" style="padding-top: 3px">
		  												Until
													</label>
													<div class="input-group" style="width: 150px;">
														<input name="interval_date_until" type="text" class="form-control span2 datepicker" value="<?= date('m/d/Y'); ?>">
														<span class="input-group-addon"> <i class="icon-calendar"></i> </span>
													</div>
												</div>
											</div>
											<div class="row byMonth hide">
												<div class="col-md-5">
													<label class="control-label" style="padding-top: 3px">
		  												Choose Year
													</label>
													<div class="input-group">
														<select name="byYear_year" class="form-control">
														<?php foreach($year_list as $list) { ?>
															<option value="<?= $list; ?>"><?= $list; ?></option>
														<?php } ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="panel-body">
					<button type="submit" name="submit" value="Test" class="btn btn-bricky btn-md">
							Start
					</button>
				</div>
			</div>
		</div>
		<?= form_close(); ?>
		<?php if($show_graph == 1) { ?>
		<div class="row">
			<script type="text/javascript">
            $(function () {
                $('#container').highcharts({
                    chart: {
                    	<?php if(!$inv) { ?>
                        	type: 'line'
                        <?php } else { ?>
                        	type: 'line'
                        <?php } ?>
                    },
                    title: {
                        text: 'AFTA Sentiment Analysis'
                    },
                    subtitle: {
                        text: '<?= "Keyword: ".$keyword; ?>'
                    },
                    xAxis: {
                    	<?php if(!$inv)  { ?>
                        	categories: <?= $axis; ?>
                        <?php } else { ?>
                        	type: 'datetime',
				            dateTimeLabelFormats: { // don't display the dummy year
				                month: '%e %b',
				                year: '%b'
				            },
				            title: {
				                text: 'Date'
				            }
                        <?php } ?>
                    },
                    yAxis: {
                        title: {
                            text: 'Score'
                        }
                    },
                    <?php if(!$inv) { ?>
	                    plotOptions: {
	                        line: {
	                            dataLabels: {
	                                enabled: true
	                            },
	                            enableMouseTracking: false
	                        }
	                    },
                    <?php } else { ?>
	                    tooltip: {
				            headerFormat: '<b>{series.name}</b><br>',
				            pointFormat: '{point.x:%e. %b}: {point.y}'
				        },	
                    <?php } ?>
                    series: [{
                        name: 'Score',
                        data: <?php if(!$inv) echo $score; else echo '['.$score.']'; ?>
                    }]
                });
            });
    		</script>
			<script src="<?= base_url().'assets/js/highcharts.js' ?>"></script>
			<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		</div>
		<br/>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="clip-data"></i>
						Data Result - Document List
						<div class="panel-tools">
							<a class="btn btn-xs btn-link panel-collapse expand" href="#">
							</a>
						</div>
					</div>
					<div class="panel-body" style="display: none;">
						<table class="table table-striped table-bordered table-hover table-full-width" id="table_data">
							<thead>
								<tr>
									<?= $thead; ?>
								</tr>
							</thead>
							<tbody>
									<?php foreach ($result as $data) {  ?>
										<tr>
											<?php $data['kataPenting'] = implode(' - ', json_decode($data['kataPenting'])); ?>
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
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	
</div>
			<!-- end: PAGE -->
</div>
<!-- end: MAIN CONTAINER -->
</div>