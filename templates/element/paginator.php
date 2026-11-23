<?php
declare(strict_types=1);

/**
 * Pagination element.
 *
 * It renders the pagination links, in the full version on large screens and in a compact version (previous page,
 * current page of total pages, next page) on small screens, followed by a counter with the current page and the number
 * of items.
 *
 * Nothing is rendered if the view has no paginated result set. The links are not rendered if there is only one page,
 * while the counter is rendered anyway.
 *
 * @var \App\View\AppView $this
 *
 * @see https://github.com/FriendsOfCake/bootstrap-ui/blob/master/README.md#pagination
 * @see https://getbootstrap.com/docs/5.3/components/pagination/
 */

use Cake\Core\Exception\CakeException;
use function Cake\I18n\__d as __d;

try {
    $hasPages = $this->Paginator->hasPage();
    // @phpstan-ignore catch.neverThrown
} catch (CakeException) {
    $hasPages = false;
}

if (!$hasPages) {
    return;
}
?>

<?php if ($this->Paginator->total() > 1) : ?>
    <nav class="d-print-none mt-4">
        <div class="d-none d-lg-flex justify-content-center">
            <?= $this->Paginator->links(options: [
                // `pagination` is required
                'class' => 'm-0 pagination',
                'prev' => true,
                'next' => true,
            ]) ?>
        </div>
        <ul class="pagination d-lg-none justify-content-center m-0">
            <?php
            if ($this->Paginator->hasPrev()) {
                echo $this->Paginator->prev();
            }
            echo $this->Html->tag(
                name: 'li',
                text: $this->Html->span(
                    text: $this->Paginator->counter(__d('cake/essentials', 'Page {{page}} of {{pages}}')),
                    options: ['class' => 'bg-transparent page-link'],
                ),
                options: ['class' => 'page-item disabled'],
            );
            if ($this->Paginator->hasNext()) {
                echo $this->Paginator->next();
            }
            ?>
        </ul>
    </nav>
<?php endif; ?>

<div class="mt-2 small text-center text-secondary">
    <?= $this->Paginator->counter(
        __d('cake/essentials', 'Page {{page}} of {{pages}}, showing {{current}} items out of {{count}} total'),
    ) ?>
</div>
