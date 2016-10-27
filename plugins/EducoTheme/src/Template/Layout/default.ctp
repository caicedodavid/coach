<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta name="MobileOptimized" content="320" />
        <title>
            <?= $cakeDescription ?>:
            <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('icon') ?>
        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css')?>
        <?= $this->fetch('script')?>
        <?php echo $this->AssetCompress->css('EducoTheme.head');?>
        <?php echo $this->AssetCompress->script('EducoTheme.head');?>
    </head>
    <body>
        <div id="educo_wrapper">
            <?= $this->element('header') ?>
            <div class="content main">
                <?= $this->Flash->render() ?>
                <?= $this->Flash->render('Auth') ?>
                <?= $this->fetch('banner') ?>
                <div class="container clearfix">
                    <?= $this->fetch('content') ?>
                </div>
            </div>
            <footer>
                <?= $this->element('footer')?>
            </footer>
            <?= $this->Html->script('EducoTheme.jquery-1.12.2'); ?>
            <?= $this->AssetCompress->script('EducoTheme.bottom');?>
        </div>
    </body>
</html>
