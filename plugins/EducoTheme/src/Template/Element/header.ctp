<?php
    $menu = [
        [
             'url' => ['controller' => 'Pages', 'action' => 'display', 'home', 'plugin' => false],
             'title' => __('Home')          
        ],
        [
            'url' => ['plugin' => false, 'controller' => 'AppUsers', 'action' => 'coaches'],
            'title' => __('Coaches')
        ],
        [
            'url' => ['plugin' => false, 'controller' => 'Topics', 'action' => 'index'],
            'title' => __('Topics')
        ]
    ]
?>

<header id="ed_header_wrapper">
    <?= $this->element('login')?>
    <div class="ed_header_bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="educo_logo"><a href="index.html">
                            <?= $this->Html->link($this->Html->image('header/Logo.png', ['alt' => 'Logo', 'pathPrefix' => 'images/']), '/', ['escape' => false])?>
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="edoco_menu_toggle navbar-toggle" data-toggle="collapse" data-target="#ed_menu">Menu <i
                            class="fa fa-bars"></i>
                    </div>
                    <div class="edoco_menu">
                        <ul class="collapse navbar-collapse pull-right" id="ed_menu">
                        <?php foreach($menu as $item):?>
                            <li><?= $this->Html->link($item['title'], $item['url'])?></li>
                        <?php endforeach;?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="educo_call"><i class="fa fa-phone"></i><a href="#">1-220-090</a></div>
                </div>
            </div>
        </div>
    </div>
</header>
