<?php 
class Menu
{
    public function get_header_contents($template='1', $data=array()) {
        // ================ [ hamburger_html ] ================
        $hamburger_html = '
            <div class="ninja-btn navToggle" title="menu">
                <a class="menu-trigger btn_hamburger" href="#">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
            </div>';


        // ================ [ back_html ] ================
        $back_html = '<div class="history_back" title="이전페이지">';
        if($template == '4') {
            $back_html = '<div class="history_back back_sch" title="이전페이지">';
        }

		if(isset($data['header_data']['back_url']) && $data['header_data']['back_url']) {
			$back_url = 'onclick="location.href=\''.$data['header_data']['back_url'].'\';"';
		}
		else {
			$back_url = 'onclick="history.back(1);"';
		}

        $back_html .= '
                <a class="menu-trigger" href="#" '.$back_url.'>
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
            </div>';

        // ================ [ header_top_html ] ================
        $header_top_html = '<div class="headerTop">';
        if($template == '2') {
            $header_top_html .= '
                <a href="/" class="home"><img src="/img/icon_home@2x.png" alt="홈으로 가기"></a>';
        }

        $header_top_html .= '
            <h1 class="headerLogo">
                    <span>';
        if($template == '1') {
            //$header_top_html .= '<img src="/img/icon_home@2x.png" alt="홈으로 가기" class="home">';
        }
        $header_top_html .= $data['header_data']['head_title'].'
                    </span>
            </h1>';

//echo '<pre>'; print_r($data['header_data']);
		$check_time = date("YmdHis",time()-60*60*36);
		$noti_time = date('YmdHis', strtotime($data['header_data']['noti_list'][0]['nt_display_date']));

		$header_top_html .= '<div class="hm">';
        if($data['header_data']['show_alarm']) {

			$header_top_html .= '
                <!-- 확인안한 알림이 있는 경우 class = alarm 보여짐 -->
                <span class="alarm">
                    <img src="/img/icon_alarm@2x.png" alt="알림">';

            if(sizeof($data['header_data']['noti_list']) > 0 && $check_time <= $noti_time) {
                $header_top_html .= '
                    <i>확인안함</i>';
            }
            $header_top_html .= '
                </span>';
        }

        $search_img_name = 'icon_search@2x.png';
        if(in_array($template, array('3','4'))) {
            $search_img_name = 'icon_searchBlcakc@2x.png';
        }
        if($template != '1' && $template != '5') {
	        $header_top_html .= '<a href="/main/search" class="go_sch"><img src="/img/'.$search_img_name.'" alt="검색하기"></a>';
		
		}

        $header_top_html .= '            
            </div>
            <!-- //hm -->
        </div>';

        // ================ [ search_area_html ] ================
        $search_area_html = '
            <div class="searchArea">
                <form action="">
                    <fieldset>
                        <a href="/main/search" style="display: block;">
                            <input type="text" placeholder="종목명 또는 심볼을 입력하세요.." class="searchInput">
                            <input type="image" src="/img/icon_searchB@2x.png" alt="검색" class="searchBtn">
                        </a>
                    </fieldset>
                </form>
            </div>';


        // ================ [ tag_top_html ] ================
        $tag_top_html = '
            <div class="tagTop">
                <ul>';

        foreach($data['top_popular_ticker'] as $val) {
            $tag_top_html .= '<li><a href="/search/invest_charm/'.$val['ticker'].'">'.$val['name'].'</a></li>';
        }
        $tag_top_html .= '
                </ul>
            </div>';


        // ================ [ sch_summary_html ] ================
        $sch_summary_html = '
            <div class="schSummary navFixed">
                <!--  //검색창 상단고정 class = navFixed -->
                <h2 class="headerLogo"><a href="/">'.SERVICE_NAME.'</a></h2>
                <span>Alphabet</span>
                <a href=""><span>검색</span></a>
            </div>';


        // ================ [ panel_left_html ] ================
        $panel_left_html = '
            <div class="panel left">
                <a href="/"><h3 class="panel_title">월가히어로</h3></a>
                <!-- 메뉴의 내용부분 -->
                <ul class="menu">
                    <li><a href="/stock/recommend">종목추천</a></li>
                    <li><a href="/stock/analysis">종목분석</a></li>
                    <li><a href="/attractiveness/attractive?sort=total&netincome=all&marketcap=over100billion">전종목 투자매력도</a></li>
                    <li><a href="/stock/recipe">투자레시피</a></li>
                    <li><a href="/stock/master">대가의 종목</a></li>
                    <li><a href="/stock/research">미국주식 탐구생활</a></li>
                </ul>
                <div class="btm">
                    <ul>
                        <li>
                            <a href="#"><i class="panel_alarm"><img src="/img/icon_alarm@2x.png" alt="">';
       
		
        if(sizeof($data['header_data']['noti_list']) > 0 && $check_time <= $noti_time) {
            $panel_left_html .= '
                            <strong class="alarm_dot">확인안함</strong>';
        }
/*		
		if(sizeof($data['header_data']['noti_list']) > 0) {
            $panel_left_html .= '
                            <strong class="alarm_dot">확인안함</strong>';
        }
*/		
		$panel_left_html .= '
                        </i> 알림</a>
                        </li>
                        <li><a href="/main/service"><i class="panel_home"><img src="/img/icon_info@2x.png" alt=""></i> 서비스소개</a></li>';
        //if($template == '1') {
            $panel_left_html .= '
                        <li class=""><a href="/"><i class="panel_home"><img src="/img/icon_home@2x.png" alt=""></i> 메인페이지로 가기</a></li>';
        //}
        $panel_left_html .= '
                    </ul>
                </div>
            </div>';


        // ================ [ panel_overlay_html ] ================
        $panel_overlay_html = '
            <div class="panel-overlay"></div>';


        $return_html = '<div id="header" class="header '.$data['header_data']['header_type'].'">';

        switch($template) {
            case '1':
                $return_html .= $hamburger_html;
                $return_html .= $header_top_html;
                $return_html .= $search_area_html;
                $return_html .= $tag_top_html;
                $return_html .= $sch_summary_html;
                $return_html .= $panel_left_html;
                $return_html .= $panel_overlay_html;
                break;

            case '2':
                $return_html .= $hamburger_html;
                $return_html .= $header_top_html;
                $return_html .= $panel_left_html;
                $return_html .= $panel_overlay_html;
                break;

            case '3':
                $return_html .= $back_html;
                $return_html .= $header_top_html;
                break;

            case '4':
                $return_html .= $hamburger_html;
                $return_html .= $back_html;
                $return_html .= $header_top_html;
                $return_html .= $panel_left_html;
                $return_html .= $panel_overlay_html;
                break;

            case '5': // 검색
                $return_html .= $back_html;
                $return_html .= $header_top_html;
                break;
            case '9': // 검색
                $return_html .= $back_html;
			default : 
				break;
        }
        $return_html .= '</div>';
        return $return_html;
    }

}
       
