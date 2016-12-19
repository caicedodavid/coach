<?php $this->start('banner') ?>
	<?= $this->Html->css('style2');?>
	<?= $this->element('slider-banner')?>
<?php $this->end() ?>
<!-- Most recomended Courses Section four start -->
<div class="ed_graysection ed_toppadder70 ed_bottompadder70">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="ed_heading_top ed_bottompadder50">
					<h3>Find the right course</h3>
					<p class="ed_toppadder70">Sometimes students need help with things to make sure you are happy .</p>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="ed_mostrecomeded_course_slider ed_mostrecomededcourseslider">
					<div id="owl-demo3" class="owl-carousel owl-theme">
						<?php foreach ($topics as $topic): ?>
							<div class="item">           			   	
            			   		<div class="ed_item_img">
            			   		    <?php echo $this->Img->displayImage($topic['topic_image'], 'medium-small', ['url' => ['action' => 'view', $topic->id]]);?>
            			   		</div>
            			   		<div class="ed_item_description ed_most_recomended_data">
            			   		    <h4>
            			   		        <?= $this->Html->link(__($topic->name), ['controller' => 'Topics', 'plugin' => false, 'action' => 'view', $topic->id])?>
            			   		        <span><?= $this->Number->currency($topic->price, 'USD');?></span>
            			   		    </h4>
            			   		    <div class="row">
										<div class="ed_rating">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
														<div class="ed_stardiv">
															<div class="star-rating"><?php echo $this->Html->tag('span',null, ['style' => 'width:' . ($topic->rating)/5 * 100 . '%;'])?></span></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div> 
            			   		    <div class="course_detail">
            			   		    	<div class="course_faculty">
										</div>
                                		<p><?= substr($topic->description,0,100) . ((strlen($topic->description) > 100) ? '...  ':'  ')?><?= $this->Html->link(__('See More'), ['controller' => 'Topics', 'plugin' => false, 'action' => 'view', $topic->id])?></p>
            			            </div>
            			        </div>

            			    </div>
            			<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
    </div><!-- /.container -->
</div>

