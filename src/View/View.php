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
 */
class View extends CakeView
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function initialize(): void
    {
        $this->addHelper('Cake/Essentials.Html');
        $this->addHelper('Cake/Essentials.Form');
    }

    /**
     * Assigns the title for the current view.
     *
     * You could assign title for single action/template and fetch it in layout.
     *
     * You can fetch it with:
     * ```
     * $this->fetch(name: 'title')
     * ```
     *
     * @param string $title
     * @return self
     */
    public function assignTitle(string $title): self
    {
        $this->assign(name: 'title', value: $title);

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
