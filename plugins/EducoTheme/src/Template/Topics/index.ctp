<?php $this->start('banner') ?>
<?php echo $this->element('banner', ['title' => __('Topics') . ($selectedCategory ? ' > ' . $selectedCategory : null)]); ?>
<?php $this->end() ?>

<div class="page index">
    <?php echo $this->element('Topics/list'); ?>
</div>
<?php $this->start('bottomScript'); ?>
    <script type="text/javascript">
    	$(function() {
    		$(".select-category[category-id='" + <?="'" . $categoryId . "'"?> + "']").css({
				'color':'#167ac6',
				'padding-right':'0px',
				'padding-left':'10px'
			})
		});
    </script>
<?php $this->end('bottomScript'); ?>