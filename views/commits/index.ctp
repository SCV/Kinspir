<?php
$script = '
$(document).ready(function(){
	$(".message").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$html->scriptBlock($script, array('inline' => false));
?>

<div class="box">
<h4><?php  __('Commits') ?></h4>

<div class="commits timeline index">
	<ul>
		<?php $i = 0; $prevDate = null;
			foreach ((array)$commits as $commit):
				$zebra = ($i++ % 2) == 0 ? 'zebra' : null;
				$currentDate = date('l F d', strtotime($commit['Commit']['created']));
				if ($currentDate !== $prevDate)  {
					if ($i > 1 ) {
						echo "</ul></li>";
					}
					echo "<li><p class=\"the-date\">{$currentDate}</p>";
					echo "<ul>";
				}
				if (!empty($commit['Commit']['revision'])) {
					echo $this->element('timeline/commit', array('data' => $commit, 'zebra' => $zebra));
				}
				$prevDate = $currentDate;
			endforeach;
		?>
	</ul>
</div>
</div>
<?php echo $this->element('layout/pagination'); ?>
