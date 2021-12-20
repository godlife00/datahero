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


function get_ticker_info(obj, target) {
    var obj = $(obj);
    var ticker = $.trim(obj.val());

    if(ticker.length <= 0) {
        if(typeof target != 'undefined') {
            $('.'+target).html('');
        }
        obj.attr('data-id', '');
        obj.removeClass('ticker_fail');
        return;
    }

    $.post('/adminpanel/main/ajax_get_ticker_info', {'ticker': ticker}, function(res) {
        if(res.is_success) {
            if(typeof target != 'undefined') {
                $('.'+target).html('<span style="text-decoration:underline;"><span class="txt-color-blue"><i class="fa fa-check"></i></span> 확인('+res.data.tkr_name+' '+res.data.tkr_ticker+') / 현재가 : '+res.data.tkr_close+'</span>');
            }
            obj.attr('data-id', res.data.tkr_close);
            obj.removeClass('ticker_fail');

        } else {
            if(typeof target != 'undefined') {
                $('.'+target).html('<span style="text-decoration:underline;"><span class="txt-color-red"><i class="fa fa-times"></i></span> '+res.msg+'</span>');
            }
            obj.attr('data-id', '');
            obj.addClass('ticker_fail');
        }
    }, 'json');

    obj.val(ticker);
}
</script>
