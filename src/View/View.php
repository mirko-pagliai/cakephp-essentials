<?php
declare(strict_types=1);

namespace Cake\Essentials\View;

use Cake\View\View as CakeView;

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
    public function initialize(): void
    {
        $this->addHelper('Cake/Essentials.Html', [
            'iconDefaults' => [
                'namespace' => 'fas',
                'prefix' => 'fa',
            ],
        ]);
        $this->addHelper('Cake/Essentials.Form');
    }
}
