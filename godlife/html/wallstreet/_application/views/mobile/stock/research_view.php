            <!--<div class="view_top">
                <h4>미국주식 탐구생활(상세)</h4>
                <a href="javascript:((function(){history.back(1);})())" class="back">뒤로가기</a>
            </div>-->
        <div id="header" class="header sch_heaher">
            <!-- 헤더 타입 3 (ex 종목추천(개별종목) 등) -->
            <div class="history_back" title="이전페이지">
				<a class="menu-trigger" href="#" onclick="location.href='/stock/research';" class="back">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
            </div>
            <!-- //history_back -->
            <div class="headerTop">
                <h1 class="headerLogo"><a href="/stock/research">미국주식 탐구생활</a></h1>
            </div>
            <!-- //headerTop -->
        </div>

		<!-- 미국주식 탐구생활 class = sub_research-->
        <div id="container" class="sub_research">
            <div class="view_con">
                <div class="top">
                    <h5 class="title"><?=$row['e_title']?></h5>
                    <span class="day"><?=date('y.m/d', strtotime($row['e_display_date']))?></span>
                    <a href="/stock/research" class="go_list">목록보기</a>
                </div>
                <div class="mid">
                <?=$row['e_content']?>
                </div>
                <a href="/stock/research" class="go_list">목록보기</a>
            </div>

            <?php if(sizeof($explore) > 0) : ?>
            <!-- 인기글 -->
            <div class="popularity">
                <h5 class="title">인기글</h5>
                <ul class="lst_type">
                    <?php foreach($explore as $exp) : ?>
                    <li>
                        <dl class="lst_type2">
                            <dt class="tit"><a href="/stock/research_view/<?=$exp['e_id']?>"><strong><?=nl2br($exp['e_title'])?></strong></a></dt>
                            <?php if(strlen($exp['e_thumbnail']) > 0) : ?>
                            <dd class="photo"><a href="/stock/research_view/<?=$exp['e_id']?>"><img src="<?=$exp['e_thumbnail']?>" alt=""></a></dd>
                            <?php endif; ?>
                        </dl>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <!-- //popularity -->
            <?php endif; ?>

        </div>
        <!-- //container -->
        
