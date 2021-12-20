<!-- PAGE FOOTER -->
<div class="page-footer">
	<div class="row">
		<div class="col-xs-12 col-sm-6">
			<span class="txt-color-white"><?=ALL_RIGHT_RESERVED?></span>
		</div>

		<div class="col-xs-6 col-sm-6 text-right hidden-xs">
			<div class="txt-color-white inline-block">
				<i class="txt-color-white hidden-mobile" style="padding-right:30px;"><strong></strong> </i>
				<i class="txt-color-blueLight hidden-mobile"><strong><span style='font-size:1.7em;'><?=SERVICE_NAME?></span> Admin Panel</strong> </i>
				<div class="btn-group dropup">
					<button class="btn btn-xs dropdown-toggle bg-color-blue txt-color-white" data-toggle="dropdown">
						<i class="fa fa-link"></i> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-right text-left">
						<li class="divider"></li>
						<li>
							<div class="padding-5">
								<p class="txt-color-darken font-sm no-margin"><a href='/adminpanel'>Home</a></p>
								<p class="txt-color-darken font-sm no-margin"><a href='javascript:history.back(1);'>Go to back</a></p>
							</div>
						</li>
						<li class="divider"></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END PAGE FOOTER -->


<script>
$(".numeric").numeric();
$(".integer").numeric(false, function() { alert("Integers only"); this.value = ""; this.focus(); });
</script>
