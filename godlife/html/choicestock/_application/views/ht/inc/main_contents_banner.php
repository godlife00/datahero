                <?php if($bn1_txt != '' && $bn1_id != '' && $bn2_txt != '' && $bn2_id != '') :?>
                <div class="main_btm banner_area">
                    <div class="banner">
                        <ul>
                            <li>
                                <a href="/<?=HT?>_stock/research_view/<?=$bn1_id?>" class="title"><?=$bn1_txt?></a>
                                <a href="/<?=HT?>_stock/research_view/<?=$bn1_id?>" class="more"><img src="/img/more_Black.png" alt="더보기"></a>
                            </li>
                            <li>
                                <a href="/<?=HT?>_stock/research_view/<?=$bn2_id?>" class="title"><?=$bn2_txt?></a>
                                <a href="/<?=HT?>_stock/research_view/<?=$bn2_id?>" class="more"><img src="/img/more_Black.png" alt="더보기"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php endif;?>