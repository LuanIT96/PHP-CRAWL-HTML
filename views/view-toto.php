<?php
if(!function_exists('add_action')) {
	exit;
}

$date_default = list_option_toto();
foreach ($date_default as $value) {
	$date_default = $value->date;
}
$date = isset($_POST["date_select2"]) ? $_POST["date_select2"] : $date_default;
$tables = view_toto($date);
$table_schedule = list_option_toto();
$count = 0;
?>
<section class="show-data-table container">
	<div class="row background-toto">
		<div class="col-md-8 col-sm-4 col-xs-4 col-lg-8">
			<div class="next-draw">
				<p>Next Draw <?php if($date) echo $date; ?> , 6.30pm</p>
			</div><!--end next-draw-->
		</div><!--end col-->
		<div class="col-md-4 col-sm-8 col-xs-8 col-lg-4">
			<div class="draw-date">
				<form method="POST" id="my_FORM2">
					<div class="form-group">
						<label for="exampleFormControlSelect1">Draw Date</label>
						<select class="form-control" id="mySelect" name="date_select2">
							<?php foreach($table_schedule as $list): ?>
								<option value="<?php echo $list->date; ?>"<?php if($date == $list->date) echo "selected"; else echo "";?>>
									<?php $listDate = strtotime($list->date); echo date("D, d M Y", $listDate); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</form>
			</div><!--end draw-date-->
		</div><!--end col-->
	</div><!--end row-->
<?php foreach($tables as $value): $count++; if($count == 1): ?>
	<div class="toto-wrapper">
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12 col-lg-6">
				<div class="row prizes">
					<div class="col-md-12">
						<span>Winning Numbers</span>
					</div>
				</div>	
				<div class="row">
					<div class="col-md-2 col-sm-4 col-xs-4 col-lg-2 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 2): ?>
					<div class="col-md-2 col-sm-4 col-xs-4 col-lg-2 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 3): ?>
					<div class="col-md-2 col-sm-4 col-xs-4 col-lg-2 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 4): ?>
					<div class="col-md-2 col-sm-4 col-xs-4 col-lg-2 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 5): ?>
					<div class="col-md-2 col-sm-4 col-xs-4 col-lg-2 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 6): ?>
					<div class="col-md-2 col-sm-4 col-xs-4 col-lg-2 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
				</div>	
			</div><!--end col-md-6 col-sm-12 col-xs-12 col-lg-6-->
			<?php else: ?>
			<div class="col-md-6 col-sm-12 col-xs-12 col-lg-6">
				<div class="row prizes">
					<div class="col-md-12">
						<span>Additional Number</span>
					</div>
				</div>	
				<div class="row">
					<div class="col-md-12 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
				</div>
			</div><!--end col-md-6 col-sm-12 col-xs-12 col-lg-6-->
		</div><!--end row-->	
	</div><!--end toto-wrapper-->
	<?php endif; endforeach; ?>
</section><!--show-data-table-->
<script>
	const form2 = document.getElementById("my_FORM2");
	form2.addEventListener('change', () => form2.submit());
</script>