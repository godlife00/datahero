
<?php foreach($morning as $exp) : ?>
                    <li>
                        <dl class="lst_type2 none_img<?=($this->session->userdata('is_paid')===FALSE) ? ' lst_lock':''?>">
                            <dt class="tit">
							<?php if($this->session->userdata('is_paid')===FALSE) :?>
							<a href="#" data-modal="<?=($is_event === true) ? 'modal-4':'modal-3'?>" class="btn_free md-trigger">
							<?php else :?>
							<a href="/stock/morning_view/<?=$exp['mo_id']?>">
							<?php endif;?>
							<strong><?=$exp['mo_title']?></strong></a><p class="day"><?=date('y.m/d',strtotime($exp['mo_display_date']))?></p></dt>
                        </dl>
                    </li>
<?php endforeach; ?>


