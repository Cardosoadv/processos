<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav>
	<ul class="pagination justify-content-center"">
		<?php if ($pager->hasPrevious()) : ?>
			<li class="page-item">
				<a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" class="page-link">
					<?= lang('Pager.first') ?>
				</a>
			</li>
			<li class="page-link">
				<a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" class="page-link">
					<?= lang('Pager.previous') ?>
				</a>
			</li>
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<li <?= $link['active'] ? 'class="active"' : '' ?> class="page-item">
				<a href="<?= $link['uri'] ?>" class="page-link">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<li class="page-item">
				<a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" class="page-link">
					<?= lang('Pager.next') ?>
				</a>
			</li>
			<li class="page-item">
				<a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" class="page-link">
					<?= lang('Pager.last') ?>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>
