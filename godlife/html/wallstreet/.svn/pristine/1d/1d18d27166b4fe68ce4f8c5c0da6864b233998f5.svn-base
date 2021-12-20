
            <div class="sub_top">
                <div class="swiper-container swiper-research">
                    <div class="swiper-wrapper">
                        <?php foreach($top_research as $key => $val) : ?>
                        <div class="swiper-slide">
                            <div class="best_research">
                                <?php /*
                                <h4 class="title">지난 10년간 시가총액이 가장 높은 기업은 어디일까요?</h4>
                                <h4 class="txt">그래프로 보는<br>시가총액 TOP 15</h4>
                                */?>
                                <a href="/stock/research_view/<?=$val['e_id']?>"><h4 class="title"><?=nl2br($val['e_title'])?></h4></a>
                            </div>
                            <!-- //best_research -->
                        </div>
                        <!-- //swiper-slide -->
                        <?php endforeach; ?>
                    </div>
                    <!-- //swiper-wrapper -->
                    <div class="swiper-pagination"></div>
                </div>
                <!-- //swiper-container -->
            </div>
            <!-- //sub_top -->

            <div class="sub_mid research_board">
                <div class="set">
                    <span onclick="location.href='/stock/research/created_at';" class="<?=$order_by == 'created_at' ? 'active' : ''?>">최신글</span>
                    <span onclick="location.href='/stock/research/view_count';" class="<?=$order_by == 'view_count' ? 'active' : ''?>">인기글</span>
                </div>
                <ul id="analysis_list" class="lst_type">
                    <?php foreach($explore as $exp) : ?>
                    <li>
                        <dl class="lst_type2">
                            <dt class="tit"><a href="/stock/research_view/<?=$exp['e_id']?>"><strong><?=nl2br($exp['e_title'])?></strong></a></dt>
                            <dd class="photo">
							<?php if(strlen($exp['e_thumbnail']) > 0) : ?>
                            <a href="/stock/research_view/<?=$exp['e_id']?>"><img src="<?=$exp['e_thumbnail']?>" alt=""></a>
                            <?php endif; ?>
							</dd>
                        </dl>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="btn_more">
                    <a href="javascript:;" onclick="view_more()"><i></i>더보기</a>
                </div>
                <!-- //btn_more -->
            </div> <!-- //sub_mid -->


            <script>
            var page = 1;
            var is_loading = false;
            function view_more() {
                if(is_loading) {
                    return;
                }
                is_loading = true;
                
                page += 1;
                $.post('/stock/ajax_get_research_list', {'page': page, 'order_by':'<?=$order_by?>'}, function(res) {
                    if($.trim(res).length) {
                        $('#analysis_list').append(res);
                    } else {
                        $('.btn_more').hide();
                    }
                    is_loading = false;
                });
            }

            </script>

