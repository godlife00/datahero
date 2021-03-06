            <?php include_once KW_INC_PATH.'/find_submenu.php'; ?>
            <div class="sub_top">
                <div class="">
                    <div class="txt_box">
                        <p class="txt">실적 및 배당 발표 기업과 시장에서 관심있는 테마에 대한 종목 리스트를 제공합니다.</p>
                    </div>
                    <!-- //txt_box -->
                </div>
                <div class="swiper-container swiper-research">
                    <div class="swiper-wrapper">
                        <?php foreach($top_research as $key => $val) : ?>
                        <div class="swiper-slide">
                            <div class="best_research">
                                <?php if($val['e_pay']=='Y' && $this->session->userdata('is_paid')===FALSE) :?>
                                <a href="javascript:fnSinChung();">
                                <?php else :?>
                                <a href="/<?=KW?>_stock/research_view/<?=$val['e_id']?>">
                                <?php endif;?>
                                <h4 class="title"><?=nl2br($val['e_title'])?></h4></a>
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
                <div class="video_tabs">
                    <a href="/<?=KW?>_stock/research" class="active">종목톡톡</a>                    
                    <a href="/<?=KW?>_stock/vod" class="">동영상</a>                    
                </div>

                <div class="set">
                    <span onclick="location.href='/<?=KW?>_stock/research/display_date';" class="<?=$order_by == 'display_date' ? 'active' : ''?>">최신글</span>
                    <span onclick="location.href='/<?=KW?>_stock/research/view_count';" class="<?=$order_by == 'view_count' ? 'active' : ''?>">인기글</span>
                </div>
                <ul id="analysis_list" class="lst_type">
                    <?php foreach($explore as $exp) : ?>
                    <li>
                        <dl class="lst_type2<?=($exp['e_pay']=='Y' && $this->session->userdata('is_paid')===FALSE) ? ' lst_lock':''?>">
                            <dt class="tit">                            
                            <?php if($exp['e_pay']=='Y' && $this->session->userdata('is_paid')===FALSE) :?>
                            <a href="javascript:fnSinChung();">
                            <?php else :?>
                            <a href="/<?=KW?>_stock/research_view/<?=$exp['e_id']?>">
                            <?php endif;?>
                            <strong><?=nl2br($exp['e_title'])?></strong></a></dt>
                            <dd class="photo">
                            <?php if(strlen($exp['e_thumbnail']) > 0) : ?>
                                <?php if($exp['e_pay']=='Y' && $this->session->userdata('is_paid')===FALSE) :?>
                                <a href="javascript:fnSinChung();">
                                <?php else :?>
                                <a href="/<?=KW?>_stock/research_view/<?=$exp['e_id']?>">
                                <?php endif;?>
                            <img src="<?=(strstr($exp['e_thumbnail'], 'http')) ? '':'https://hero.datahero.co.kr'?><?=$exp['e_thumbnail']?>" alt=""></a>
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
            </div>
            <!-- //sub_mid -->

            <script>
            var page = 1;
            var is_loading = false;
            function view_more() {
                if(is_loading) {
                    return;
                }
                is_loading = true;
                
                page += 1;
                $.post('/<?=KW?>_stock/ajax_get_research_list', {'page': page, 'order_by':'<?=$order_by?>'}, function(res) {
                    if($.trim(res).length) {
                        $('#analysis_list').append(res);
                    } else {
                        $('.btn_more').hide();
                    }
                    is_loading = false;
                });
            }

            </script>