<?php $this->start('banner');
echo $this->element('banner', ['title' => __('Topics {0}', $selectedCategory ? ' > ' . $selectedCategory : null)]);
$this->end();?>
<div class="page index">
    <?php echo $this->element('Topics/list'); ?>
</div>
<?php $this->start('bottomScript'); ?>
    <script type="text/javascript">
    	$(function() {
    		$(".select-category[category-id='" + <?="'" . $categoryId . "'"?> + "']").addClass( "selected-category" );
    		var $nameInput = $('#search-form').find("input[name='name']");
			$('.input-group').find("#widget-search").val($nameInput.val());
			$nameInput.val(null);
		})
    </script>
<?php $this->end('bottomScript'); ?>