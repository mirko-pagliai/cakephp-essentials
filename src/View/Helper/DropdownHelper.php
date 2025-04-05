<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use Cake\View\Helper;

/**
 * DropdownHelper.
 *
 * @property \Cake\Essentials\View\Helper\FormHelper $Form
 * @property \Cake\Essentials\View\Helper\HtmlHelper $Html
 */
class DropdownHelper extends Helper
{
    protected array $_links = [];

    protected string $_opening = '';

    /**
     * @var array
     */
    protected array $helpers = ['Html', 'Form'];

    /**
     * Creates the main link that allows you to open the dropdown.
     *
     * @param string $title
     * @param array $options
     * @return self
     */
    public function create(string $title = '', array $options = []): self
    {
        $options += [
            'aria-expanded' => 'false',
            'data-bs-toggle' => 'dropdown',
            'role' => 'button',
        ];

        $options = $this->addClass(options: $options, class: 'dropdown-toggle');

        $this->_opening = $this->Html->link(title: $title, url: '#', options: $options);

        return $this;
    }

    /**
     * Creates an HTML link, compatible with the dropdown submenu.
     *
     * @param string $title
     * @param array|string $url
     * @param array<string, mixed> $options
     * @return self
     *
     * @see \App\View\Helper\HtmlHelper::link() for more information about the method arguments
     */
    public function link(string $title, array|string $url, array $options = []): self
    {
        $options = $this->addClass(options: $options, class: 'dropdown-item');

        $this->_links[] = $this->Html->link(title: $title, url: $url, options: $options);

        return $this;
    }

    /**
     * Creates an HTML link from route path string, compatible with the dropdown submenu.
     *
     * @param string $title
     * @param string $path
     * @param array $params
     * @param array<string, mixed> $options
     * @return self
     *
     * @see \App\View\Helper\HtmlHelper::linkPath() for more information about the method arguments
     */
    public function linkFromPath(string $title, string $path, array $params = [], array $options = []): self
    {
        return $this->link(title: $title, url: ['_path' => $path] + $params, options: $options);
    }

    /**
     * Creates an HTML link, but access the URL using the method you specify (defaults to POST), compatible with
     *  the dropdown submenu.
     *
     * @param string $title
     * @param array|string $url
     * @param array<string, mixed> $options
     * @return self
     *
     * @see \Cake\View\Helper\FormHelper::postLink() for more information about the method arguments
     */
    public function postLink(string $title, array|string $url, array $options = []): self
    {
        $options = $this->addClass(options: $options, class: 'dropdown-item');

        $this->_links[] = $this->Form->postLink(title: $title, url: $url, options: $options);

        return $this;
    }
}
