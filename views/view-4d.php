<?php
if(!function_exists('add_action')) {
	exit;
}

$date_default = list_option_4d();
foreach ($date_default as $value) {
	$date_default = $value->date;
}

$date = isset($_POST["date_select"]) ? $_POST["date_select"] : $date_default;
$tables = view_4d($date);
$tables_schedule = list_option_4d();
$count = 0;
?>
<section class="show-data-table container">
	<div class="row background-4d">
		<div class="col-md-8 col-sm-4 col-xs-4 col-lg-8">
			<div class="next-draw">
				<p>Next Draw <?php if($date) echo $date; ?> , 6.30pm</p>
			</div><!--end next-draw-->
		</div><!--end col-->
		<div class="col-md-4 col-sm-8 col-xs-8 col-lg-4">
			<div class="draw-date">
				<form method="POST" id="my_FORM">
					<div class="form-group">
						<label for="exampleFormControlSelect1">Draw Date</label>
						<select class="form-control" id="mySelect" name="date_select">
							<?php foreach($tables_schedule as $list): ?>
								<option value="<?php echo $list->date; ?>"<?php if($date == $list->date) echo "selected"; else echo "";?>>
									<?php $listDate = strtotime($list->date); echo date("D, d M Y", $listDate); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</form>
			</div><!--end draw-date-->
		</div><!--end col-md-4 col-sm-8 col-xs-8 col-lg-4-->
	</div><!--end row-->
	<?php foreach($tables as $value): $count++; if($count == 1): ?>
	<div class="prizes-wrapper">
		<div class="row">
			<div class="col-md-4 col-sm-12 col-xs-12 col-lg-4">
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 prizes">
						<span>1st Prize</span>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 prizes">
						<span>2nd Prize</span>	
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 prizes">
						<span>3rd Prize</span>
					</div>	
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 2): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 3): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
				</div>
			</div>
			<?php elseif($count == 4): ?>
			<div class="col-md-4 col-sm-12 col-xs-12 col-lg-4">
				<div class="row prizes">
					<div class="col-md-12">
						<span>Starter Prizes</span>
					</div>
				</div>	
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 5): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 6): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 7): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 8): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 9): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 10): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 11): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 12): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step2">

					</div>
					<?php elseif($count == 13): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step2">	
					</div>
				</div>	
			</div><!--end col-md-4 col-sm-12 col-xs-12 col-lg-4-->
			<?php elseif($count == 14): ?>
			<div class="col-md-4 col-sm-12 col-xs-12 col-lg-4">
				<div class="row prizes">
					<div class="col-md-12">
						<span>Consolation Prizes</span>
					</div>
				</div>	
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 15): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 16): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 17): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 18): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 19): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 20): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 21): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<?php elseif($count == 22): ?>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step2">

					</div>
					<?php else: ?>
						<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step">
						<span><?php echo $value->value; ?></span>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-step2">

					</div>
				</div>
			</div>
		</div>
	</div><!--end prizes-wrapper-->
	<?php endif; endforeach; ?>
</section><!--end show-data-table-->
<script>
	const form = document.getElementById("my_FORM");
	form.addEventListener('change', () => form.submit());
</script>