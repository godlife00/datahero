


            <!-- 주요 콘텐츠 -->
            <div class="search_top searching">

                <!-- 종목명 검색창 -->
                <div class="searchArea">
                    <form action="" name="topsearch" onsubmit="var v = $('#autocomplete_list li a._on span.schCode').html(); if(v.length > 0 && $('#autocomplete_list').get(0).children.length > 0) { this.action='/search/invest_charm/'+v; setSearchHistory(v); return true; }; return false;">
                        <fieldset>
                            <input type="text" name='keyword' autocomplete="off" placeholder="종목명 또는 심볼을 입력하세요.." class="searchInput searchInput_fixed">
                            <input type="image" src="/img/icon_searchB@2x.png" alt="검색" class="searchBtn">
                        </fieldset>
                    </form>
                </div>
                <!-- //종목명 검색창 -->

                <!-- 검색어 입력시 자동완성 -->
                <div class="sch_autocomplete">
                    <!-- //자동완성 결과 노출 class : _show -->
                    <!-- 검색결과 있을경우 -->
                    <ul id='autocomplete_list'>
                    </ul>

                    <!-- 검색결과 없을경우 -->
                    <div class="no_result" >
                        <p>"<strong></strong>"에 대한 검색결과가 없습니다.</p>
                    </div>
                    <!-- //no_result -->
                </div>
                <!-- //sch_autocomplete -->
            </div>
            <!-- //sub_top -->

            <div class="sub_mid latest_results">
                <!-- 최신검색결과 있는 경우 -->
                <h2 class="title"><?=$tab_text?></h2>
                <span class="set"><!--<i></i> 설정--></span>
                <table cellspacing="0" border="1" class="tableRanking">
                    <colgroup>
                        <col width="100px">
                        <col width="80px">
                        <col width="60px">
                        <col width="60px">
                        <col width="">
                    </colgroup>
                    <tbody>
                                <?php 
                                    foreach($tab_stock_data as $val) : 
                                        $class = 'decrease';
                                        if($val['ticker']['tkr_rate'] > 0) {
                                            $class = 'increase';
                                        }
                                ?>
                                    <tr>
                                        <td class="title"><a href="/search/invest_charm/<?=$val['ticker']['tkr_ticker']?>"><?=$val['ticker']['tkr_name']?><span class="ticker"><?=$val['ticker']['tkr_ticker']?></span></a></td>
                                        <td class="num">
                                            <span><?=$val['ticker']['tkr_close']?></span>
                                        </td>
                                        <td class="per"><span class="<?=$class?>"><?=$val['ticker']['tkr_rate_str']?></span></td>
                                        <!-- increase 증가, decrease 감소 -->
                                        <td class="score"><span><?=$val['m_biz_total_score']?>점</span></td>
                                        <td class="recom"><span><?=$this->mri_tb_model->getInvestOpinionByStar($val['an_opinion'])?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                    </tbody>
                </table>

            </div> <!-- //sub_mid -->
