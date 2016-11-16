<?php $this->start('background')?>
    <div class="ed_graysection ed_purchase_course ed_toppadder80 ed_bottompadder80 course_purchase_wrapper">
<?php $this->end()?>
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="ed_course_single_item">
            <div class="row">
                <?= $this->fetch('image_title')?>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="ed_course_single_tab ed_toppadder40">
                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <?= $this->fetch('content')?>
                        </div>
                    </div><!--tab End-->
                </div>
            </div>
        </div>  
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="sidebar_wrapper_upper">
            <div class="sidebar_wrapper">
                <aside class="widget widget_sharing">
                    <h4 class="widget-title">share this course</h4>
                    <ul>
                        <li><a href="course_single.html"><i class="fa fa-facebook"></i> facebook</a></li>
                        <li><a href="course_single.html"><i class="fa fa-linkedin"></i> linkedin</a></li>
                        <li><a href="course_single.html"><i class="fa fa-twitter"></i> twitter</a></li>
                        <li><a href="course_single.html"><i class="fa fa-google-plus"></i> google+</a></li>
                    </ul>
                </aside>
            </div>
        </div>
    </div>
<!--Sidebar End-->
</div>