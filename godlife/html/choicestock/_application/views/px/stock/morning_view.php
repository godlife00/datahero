			<div class="headerTop">
                <h1 class="headerLogo"><span>모닝브리핑</span></h1>
                <a href="javascript:history.back();" class="his_back"><img src="/img/icon_back.png" alt="뒤로가기"></a>
            </div>
			<div class="view_con">
                <div class="top">
                    <h5 class="title"><?=$row['mo_title']?></h5>
                    <span class="day"><?=date('y.m/d', strtotime($row['mo_display_date']))?></span>
                    <a href="/<?=PX?>_stock/morning" class="go_list">목록보기</a>
                </div>
                <div class="mid">
                <?=nl2br($row['mo_contents'])?>
                </div>
                <a href="/<?=PX?>_stock/morning" class="go_list">목록보기</a>
            </div>
        </div>
        <!-- //container -->
        
