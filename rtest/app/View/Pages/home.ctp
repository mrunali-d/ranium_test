<?php echo $this->Html->css('../bootstrap-datepicker/css/bootstrap-datepicker.min'); ?>
<style>
		canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
		.butcl {
			    margin: 16px;
		}
</style>	 
<div class="container">
	<div class="col-md-12">

	<?php 
			echo $this->Form->create('DateRange', array(
			'class' => 'form-horizontal',
			'id'=>'addDateRange',		
			'novalidate' => true,		
			'role' => 'form',
			));			
	?>

		<div class="row">
		   <div class='col-md-4'>
			  <div class="form-group">      
					<?php echo $this->Form->input('start_date',array('type'=>'text','class'=>'date-picker form-control','placeholder'=>'Start Date','label'=>false)); ?>         
				
			  </div>
		   </div>
		   <div class='col-md-4'>
			  <div class="form-group">        
					<?php echo $this->Form->input('end_date',array('type'=>'text','class'=>'date-picker form-control ','placeholder'=>'End Date','label'=>false)); ?>        
			  </div>
		   </div>
		   <div class='col-md-4'>
		   <?php
			echo $this->Form->button('Submit',array('type'=>'submit','class'=>'btn btn-primary butcl'));
			?>
		  </div>
		 </div>
	  <?php echo $this->Form->end(); ?>
	 
	</div> 
</div> 
	<div id="fastAstro"> </div>
	<div id="closeAstro"> </div>
	<div id="messageDiv"> </div>
	<div id="avgAstro"> </div>
	<div style="width:75%;">
		<canvas id="canvas"></canvas>
	</div>
	<?php echo $this->Html->script('../bootstrap-datepicker/js/bootstrap-datepicker.min'); ?>
<script>		

	 $('.date-picker').datepicker({

		orientation: "auto",

		autoclose: true,

		todayHighlight: true,

		format: "dd-mm-yyyy",

	});
		
  $("#addDateRange").on('submit', (function(e) {e.preventDefault();		
		$.ajax({
			url: "<?php echo Router::url(array('controller' => 'pages','action' => 'findNeoFeedData'));?>" ,
			type: "POST",
			data: new FormData(this), 
			contentType: false,
			cache: false, 
			processData: false,
			dataType: 'json',
		
			success: function(data)
			{
				if(data.status == 200)
				{
					$("#fastAstro").html('<div class="alert alert-success"><strong>1.Fastest Asteroid ID -'+data.fast['id']+': </strong> Speed - '+data.fast['speed']+'</div>');
					$("#closeAstro").html('<div class="alert alert-success"><strong>2.Closest Asteroid ID -'+data.close['c_id']+': </strong> Distance -'+data.close['dis']+'</div>');
					$("#avgAstro").html('<div class="alert alert-success"><strong>3.Average Asteroid : </strong> '+data.avg+'</div>');
					var labelData = data.chars;
					var value = data.value;				
					var config = {
						type: 'line',
						data: {
							labels: labelData,
							datasets: [{
								label: 'Asteroids',
								data:value,
								backgroundColor: window.chartColors.red,
								borderColor: window.chartColors.red,
								fill: false,
								borderDash: [5, 5],
								pointRadius: 15,
								pointHoverRadius: 10,
							}]
						},
						options: {
							responsive: true,
							legend: {
								position: 'bottom',
							},
							hover: {
								mode: 'index'
							},
							scales: {
								xAxes: [{
									display: true,
									scaleLabel: {
										display: true,
										labelString: 'Date'
									}
								}],
								yAxes: [{
									display: true,
									scaleLabel: {
										display: true,
										labelString: 'Value'
									}
								}]
							},
							title: {
								display: true,
								text: 'Total Number of Asteroids '
							}
						}
					};
					
					var ctx = document.getElementById('canvas').getContext('2d');
					window.myLine = new Chart(ctx, config);			
				}else{
					$("#messageDiv").html('<div class="alert alert-danger"><strong>Please Try After Some Time : </strong>Can not read from server. It may not have the appropriate access-control-origin settings.</div>');
				}
			}
		});
		
	}));
	

	</script>