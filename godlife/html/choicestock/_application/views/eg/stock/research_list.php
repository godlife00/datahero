
<?php foreach($explore as $exp) : ?>
                    <li>
                        <dl class="lst_type2<?=($exp['e_pay']=='Y' && $this->session->userdata('is_paid')===FALSE) ? ' lst_lock':''?>">
                            <dt class="tit">                            
                            <?php if($exp['e_pay']=='Y' && $this->session->userdata('is_paid')===FALSE) :?>
                            <a href="javascript:fnSinChung();">
                            <?php else :?>
                            <a href="/<?=EG?>_stock/research_view/<?=$exp['e_id']?>">
                            <?php endif;?>                                                
                            <strong><?=nl2br($exp['e_title'])?></strong></a></dt>
                            <?php if(strlen($exp['e_thumbnail']) > 0) : ?>
                            <dd class="photo">
                            <?php if($exp['e_pay']=='Y' && $this->session->userdata('is_paid')===FALSE) :?>
                            <a href="javascript:fnSinChung();">
                            <?php else :?>
                            <a href="/<?=EG?>_stock/research_view/<?=$exp['e_id']?>">
                            <?php endif;?>                                                
                            <img src="<?=(strstr($exp['e_thumbnail'], 'http')) ? '':'https://hero.datahero.co.kr'?><?=$exp['e_thumbnail']?>" alt=""></a></dd>
                            <?php endif; ?>
                        </dl>
                    </li>
<?php endforeach; ?>
