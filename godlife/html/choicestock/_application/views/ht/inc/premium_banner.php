                <!-- 프리미엄 서비스 부분 -->
                <div class="prm_div weeks_free">
                    <div class="box">
                        <div class="left">
                            <p class="title"><i></i>초이스스탁US 프리미엄</p>
                            <p class="txt">모든 서비스를 제한없이 이용하실 수 있습니다.</p>
                        </div>
                        <div class="right">
                            <p>
                            <?php if($move=='Y') :?>
                                <a href="/<?=HT?>_main/service" class="btn_free">
                            <?php else :?>    
                                <a href="javascript:fnSinChung();" class="btn_free">
                            <?php endif;?>
                            2주 무료 이용<i></i></a></p>
                            <p><a href="/<?=HT?>_main/service" class="go_link">[서비스 안내]</a></p>                        
                        </div>                       
                    </div>
                </div>
                <!-- //프리미엄 서비스 부분 -->