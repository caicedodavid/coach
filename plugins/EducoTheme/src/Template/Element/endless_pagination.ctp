<?php
if (!isset($separator)) {
	if (defined('PAGINATOR_SEPARATOR')) {
		$separator = PAGINATOR_SEPARATOR;
	} else {
		$separator = '';
	}
}
if (empty($next)) {
	$next = __d('tools', 'Load More');
}

$escape = isset($escape) ? $escape : true;
?>

<div class="paging paginator ed_blog_bottom_pagination", id="pagination-button">
	<ul class="pagination">
	<?php echo $this->Paginator->next($next, ['escape' => $escape], null, ['class' => 'next disabled']);?>
	</ul>
</div>