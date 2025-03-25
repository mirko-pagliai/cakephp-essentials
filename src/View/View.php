<?php
declare(strict_types=1);

namespace Cake\Essentials\View;

use Cake\View\View as CakeView;
use Override;

/**
 * {@inheritDoc}
 *
 * @property \Cake\Essentials\View\Helper\FormHelper $Form
 * @property \Cake\Essentials\View\Helper\HtmlHelper $Html
 * @property \BootstrapUI\View\Helper\PaginatorHelper $Paginator
 */
class View extends CakeView
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();

        $this->addHelper('Cake/Essentials.Html');
        $this->addHelper('Cake/Essentials.Form');
        $this->addHelper('BootstrapUI.Paginator', [
            'templates' => [
                'sort' => '<a href="{{url}}" class="text-decoration-none">{{text}}</a>',
                'sortAsc' => '<a class="asc text-decoration-none" href="{{url}}">{{text}} <i class="fas fa-arrow-up-short-wide"></i></a>',
                'sortDesc' => '<a class="desc text-decoration-none" href="{{url}}">{{text}} <i class="fas fa-arrow-down-wide-short"></i></a>',
                'sortAscLocked' => '<a class="asc locked text-decoration-none" href="{{url}}">{{text}} <i class="fas fa-arrow-up-short-wide"></i></a>',
                'sortDescLocked' => '<a class="desc locked text-decoration-none" href="{{url}}">{{text}} <i class="fas fa-arrow-down-wide-short"></i></a>',
            ],
        ]);
    }

    /**
     * Assigns the main title for the current view.
     *
     * You could assign the main title in your action/template and fetch it in layout.
     *
     * You can fetch it with:
     * ```
     * $this->fetch(name: 'main_title')
     * ```
     *
     * It is recommended not to use the `title` block, because this is always set automatically by `Cake\View\View::renderLayout()`.
     *
     * @param string $title
     * @return self
     * @see \Cake\View\View::renderLayout()
     */
    public function assignTitle(string $title): self
    {
        $this->assign(name: 'main_title', value: $title);

        return $this;
    }

    /**
     * Start capturing output for the `main_links` block, i.e. the links related to the current view.
     *
     * Remember to call the `end()` method when finished.
     *
     * Example:
     * ```
     * $this->startMainLinks();
     * echo $this->Html->link(
     *    title: __('Add'),
     *    url: ['action' => 'add'],
     *    options: ['class' => 'btn btn-sm btn-outline-primary', 'icon' => 'plus']
     * );
     * $this->end();
     * ```
     *
     *  You can fetch it with:
     *  ```
     *  $this->fetch(name: 'main_links')
     *  ```
     *
     * @return self
     */
    public function startMainLinks(): self
    {
        return $this->start('main_links');
    }
}
