<?php
if (!isset($separator)) {
	if (defined('PAGINATOR_SEPARATOR')) {
		$separator = PAGINATOR_SEPARATOR;
	} else {
		$separator = '';
	}
}
if (empty($prev)) {
	$prev = __d('tools', 'previous');
}
if (empty($next)) {
	$next = __d('tools', 'next');
}
if (!isset($format)) {
	$format = __d('tools', 'Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total');
}
if (!empty($reverse)) {
	$tmp = $prev;
	$prev = $next;
	$next = $tmp;
}
if (!empty($addArrows)) {
	$prev = '« ' . $prev;
	$next .= ' »';
}
$escape = isset($escape) ? $escape : true;
?>

<div class="paging paginator ed_blog_bottom_pagination">
	<ul class="pagination">
	<?php echo $this->Paginator->prev($prev, ['escape' => $escape], null, ['class' => 'prev disabled']);?>
 	<?php echo $separator; ?>
	<?php echo $this->Paginator->numbers(['escape' => $escape, 'separator' => $separator]);?>
 	<?php echo $separator; ?>
	<?php echo $this->Paginator->next($next, ['escape' => $escape], null, ['class' => 'next disabled']);?>
	</ul>

</div>