<?php if($part_page!='et') :?>
          <!-- 주요 콘텐츠 -->
            <div class="search_top">
                <div class="data_area">
                    <h2 class="title" title="<?=$ticker['tkr_name_en']?>"><a href="/search/invest_charm/<?=$ticker['tkr_ticker']?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>"><?=($sec_ticker != '' && $pri_ticker != '') ? $pri_ticker_name:$ticker['tkr_name']?></a></h2>
                    <ul class="info">
                        <li class="sum"><span class="eng"><?=$ticker['tkr_ticker']?></span> </li>
                    </ul>
                    <ul class="detail">
                        <li class="num">
                            <span class="<?=$ticker['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$ticker['tkr_close']?></span> <!-- increase 증가, decrease 감소 -->
                        </li>
                        <li class="per">
                            <span class="<?=$ticker['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$ticker['tkr_diff_str']?> <span>(<?=$ticker['tkr_rate_str']?>)</span></span> <!-- increase 증가, decrease 감소 -->
                        </li>
                        <li class="day"><?php if(isset($ticker['tkr_lastpricedate'])&&$ticker['tkr_lastpricedate']) echo date('y.m/d', strtotime($ticker['tkr_lastpricedate'])).', ';?> USD</li>
                    </ul>

                    <?php if($is_recom_ticker) : ?>
                    <div class="go_page">
                        <a href="<?=$recom_link?>"><span class="quarter recom">추천</span></a>
                        <!-- 추천 class = recom, 분석 class = analysis -->
                        <a class="menu-trigger" href="<?=$recom_link?>">
                            <span></span>
                            <span></span>
                        </a>
                    </div>
                    <?php elseif($is_analysis_ticker) : ?>
                    <div class="go_page">
                        <a href="<?=$analysis_link?>"><span class="quarter analysis">분석</span></a>
                        <!-- 추천 class = recom, 분석 class = analysis -->
                        <a class="menu-trigger" href="<?=$analysis_link?>">
                            <span></span>
                            <span></span>
                        </a>
                    </div>
                    <?php endif ; ?>
                </div>
                <!-- //data_area -->
                <ul class="info_icon">
                    <li><?=$ticker['tkr_exchange']?></li>

                    <?php if($mri_data['m_biz_dividend_score'] >= 16) : ?>
                    <li>배당매력주</li>
                    <?php endif; ?>

                    <?php if($mri_data['m_biz_growth_score'] >= 16) : ?>
                    <li>이익성장</li>
                    <?php endif; ?>

                    <?php if($mri_data['m_biz_moat_score'] >= 16) : ?>
                    <li>소비자독점주</li>
                    <?php endif; ?>

                </ul>
                <!-- //info_icon -->
            </div>
            <!-- //sub_top -->
<?php endif;?>
<?php if($sec_ticker=='' && $pri_ticker=='') :?>

            <div class="sub_mid tabs_area">
				<?php if($part_page == 'et' && $is_shinhan === TRUE) :?>
				<?php else :?>
                <ul class="tabs_5">
                    <?php
                    $search_tabs = array(
                        'summary'       => '종목진단',
                        'financials'    => '재무제표',
                        'invest'        => '투자지표',
                        'alloca'      => '배당',
                    );
                    foreach($search_tabs as $seg => $tab_tit) : 
                        $tab_active = ($current_tab == $seg) ? 'active' : '';
                    ?>
                    <li class="<?=$tab_active?>"><a href="/search/<?=($seg=='summary')? 'invest_charm' : $seg?>/<?=$ticker_code?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>"><?=$tab_tit?></a></li>
                    <?php endforeach; ?>
                </ul>
                <!-- //tabs_5 -->
				<?php endif;?>

		<?php if($current_tab == 'summary') : // 종목진단은 내부 텝 3개 공통 상단을 여기서 그려줌 ?>
				<?php if($part_page == 'et' && $is_shinhan === TRUE) :?>
				<?php else :?>
				<ul class="tabs_3 tabs_depth2">
					<li class="<?=$current_subtab=='invest_charm' ? 'active' : ''?>"><a href="/search/invest_charm/<?=$ticker_code?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>&pm=m">투자매력</a></li>
					<li class="<?=$current_subtab=='summary' ? 'active' : ''?>"><a href="/search/summary/<?=$ticker_code?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>&pm=m">기업개요</a></li>
					<li class="<?=$current_subtab=='finance_chart' ? 'active' : ''?>"><a href="/search/finance_chart/<?=$ticker_code?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>&pm=m">재무차트</a></li>
				</ul>
				<?php endif;?>
				<?php if($current_subtab=='invest_charm') :?>
                <div class="chart_area diagnosis">
                    <div class="chartData">
                        <dl>
                            <dt class="charm">투자매력
                            <span class="txt_guide"><img src="/img/txt_guide@2x.png" alt="가이드보기"></span>
                            <div class="guide_box">
                                <span class="clse">닫기</span>                                    
                                <ul>
                                    <li>투자매력 점수(스파이더 종합점수)는 빅 데이터 전문 테크핀 기업인 (주)데이터히어로가 개발한 기업분석 알고리즘에 따라 계산합니다. 기업의 10년 이상의 재무 데이터와 통계적 분석 방법을 적용한 알고리즘에 따라  계산합니다. 기업을 수익 성장성, 재무 안전성, 사업 독점력, 배당매력, 현금창출력 등 5개 기준에 따라 각 항목별로 자세히 분석합니다. </li>
                                </ul>
                            </div> <!-- //guide_box -->
                            <div class="guide_layer"></div>
                            </dt>
                            <dd class="charm_num"><?=$mri_data['m_biz_total_score']?></dd>
							<?php if($pre_mriscore>0) :?>
							<?php $score_diff = $mri_data['m_biz_total_score']-$pre_mriscore;?>
                            <dd class="charm_lw">전월대비 <strong><?=($score_diff>0) ? '+'.$score_diff : $score_diff?></strong></dd>
							<?php endif;?>
                        </dl>
                    </div>
                    <!-- //chartData -->
                    <div class="difference">
                        <p class="dt">전체 <strong><?=number_format($high_count+1);?></strong>위, 상위 <strong><?=$total_rank_rate;?></strong>%</p>
                        <span class="dd">(<?=number_format($sp_totalcount);?>개 평가기업 중)</span>
                        <p class="dt">동일업종 <?=$industry_high_count+1?>위, 상위 <?=$industry_rank_rate;?>%</p>
                        <span class="dd">업종 <?=$ticker['tkr_industry']?> </span><!--tkr_sector-->
                    </div>
                    <!-- //difference -->
                </div>

                <div class="investCharm_area">
                    <div class="chart_sum">
                        <div class="star_area">
                            <strong>배당매력</strong>
                            <div class="starRev">
								<?php 
								for($i = 1 ; $i <= 5 ; $i++) { 
									if($mri_data['m_biz_dividend_stars'] >= $i) {
										echo '<span class="starR on">별1</span>';
									}
									else {
										if($i-$mri_data['m_biz_dividend_stars'] <= 0.5) {
											echo '<span class="starR on half">별1</span>';
										}
										else {
											echo '<span class="starR">별1</span>';
										}
									}
								}
								?>
                            </div>
                        </div>
                        <!-- //star_area -->
                        <div class="star_area">
                            <strong>수익성장성</strong>
                            <div class="starRev">
								<?php 
								for($i = 1 ; $i <= 5 ; $i++) { 
									if($mri_data['m_biz_growth_stars'] >= $i) {
										echo '<span class="starR on">별1</span>';
									}
									else {
										if($i-$mri_data['m_biz_growth_stars'] <= 0.5) {
											echo '<span class="starR on half">별1</span>';
										}
										else {
											echo '<span class="starR">별1</span>';
										}
									}
								}
								?>
                            </div>
                        </div>
                        <!-- //star_area -->
                        <div class="star_area">
                            <strong>애널리스트 컨센서스<span class="txt_guide"><img src="/img/txt_guide@2x.png" alt="가이드보기"></span></strong>
                            <!-- 투자의견, 투자매력 가이드 레이어 -->
                            <div class="guide_box hide">
                                <span class="clse">닫기</span>
                                <strong class="title">애널리스트 컨센서스</strong>
                                <ul>
                                    <li>미국 증권사 애널리스트들이 제시한 투자의견 컨센서스(평균)를 표시합니다. 최소 10개 이상 증권사에서 투자의견을 제시한 종목만 제공합니다. 투자의견 컨센서스는 강력매수, 매수, 보유, 매도, 강력매도 5단계로 제시합니다. </li>
                                </ul>
                            </div>
                            <!-- //guide_box -->
                            <!--<span class="recom buying"><?=$this->common->get_valuation_stars_text($mri_data['expected_star'])?></span>-->
							<span class="recom <?=$this->mri_tb_model->getInvestOpinionByStar($mri_data['an_opinion'], $divide=2)?>"><?=$this->mri_tb_model->getInvestOpinionByStar($mri_data['an_opinion'])?></span>
							<!-- 매수 : class = buying, 매도 : class = sell -->
                        </div>
                        <!-- //star_area -->

                        <div class="summary small">
                            <!-- div 사이즈는 big, small  -->
                            <div class="view_box">
                                <p><?=nl2br($ticker['tkr_description'])?></p>
                                <ul class="etc">
                                    <?php /*
                                    <li class="industry">산업 <?=$api_ticker['tkr_industry']?></li>
                                    <li class="home"><a href="<?=$api_ticker['tkr_companysite']?>" target="_blank"><?=$api_ticker['tkr_companysite']?></a></li>
                                    <li class="competitors">SEC 전자공시</li>
                                    <li class="link"><a href="<?=$api_ticker['tkr_secfilings']?>" target="_blank">바로가기</a></li>
                                    */ ?>
                                    <li class="industry"> <span>산업</span><?=$ticker['tkr_industry']?></li>
									<?php
										$ticker['tkr_companysite'] = str_replace('http://','',$ticker['tkr_companysite']);
										$ticker['tkr_companysite'] = str_replace('https://','',$ticker['tkr_companysite']);
									?>
                                    <li class="home"></span><span>홈</span><!--<a href="<?=$ticker['tkr_companysite']?>" target="_blank">--><?=$ticker['tkr_companysite']?><!--</a>--></li>
                                    <!-- <li class="link"><span>sec전자공시</span> <a href="<?=$api_ticker['tkr_secfilings']?>" target="_blank">바로가기</a></li> -->

                                </ul>
                                <!-- //etc -->
                                <!-- <span class="close show">펼치기 <i></i></span> -->
                            </div>
                            <!-- //view_box -->
                        </div>
                    </div>
                    <!-- //chart_sum -->
				<div class="tab_diagnosis_area">
			<?php else:?>
                <div class="investCharm_area">                    
				<div class="tab_diagnosis_area">
			<?php endif; ?>
		<?php endif; // 종목진단은 내부 텝 3개 공통 상단을 여기서 그려줌 ?>

<?php endif; ?>

<?php //if($part_move=='m') :?>
<!--<script>
    //종목진단 탭메뉴 클릭시 화면 스크롤 고정    
	$(document).ready(function(){
	    if ($('.tab_diagnosis_area').length) {   
			var tab_scrTop = $('.tab_diagnosis_area .tabs_3').offset();
			var summarySHeight = $('.summary.small').height();                        
			$('html, body').animate({
				scrollTop: tab_scrTop.top - summarySHeight + 0
			}, 0);
		}
    });
</script>-->
<?php //endif; ?>