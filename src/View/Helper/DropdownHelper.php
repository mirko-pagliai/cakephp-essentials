<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BadMethodCallException;
use Cake\View\Helper;
use Stringable;

/**
 * DropdownHelper.
 *
 * This helper allows you to quickly and easily create dropdowns.
 *
 * It first provides the `create()` method to set the opening link of the dropdown.
 *
 * After calling this method, you should consecutively call one or more of the "links methods" (`link()`, `linkPath()`,
 *  `deleteLink()` and `postLink()`) to build the dropdown submenu links.
 *
 * Finally, you will have to call the `render()` method, which builds and returns the entire dropdown. Along with this
 *  method, it also provides the magic `__toString()` method, which makes the instance stringable (but it can't take
 *  arguments, in that case you have to use the `render()` method).
 *
 * The `create()` method and all link methods return the instance itself and are therefore chainable.
 *
 * Example:
 *
 * ```
 * echo $this->Dropdown->create(title: 'My opening link')
 *    ->link(title: 'First sub-link', url: '/example-1')
 *    ->link(title: 'Second sub-link', url: '/example-2')
 *    ->linkFromPath(title: 'Third sub-link', path: 'Users::index')
 *    ->render(options: ['class' => 'my-custom-wrapper-class']);
 * ```
 *
 * @property \Cake\Essentials\View\Helper\FormHelper $Form
 * @property \Cake\Essentials\View\Helper\HtmlHelper $Html
 *
 * @see https://getbootstrap.com/docs/5.3/components/dropdowns/
 */
class DropdownHelper extends Helper implements Stringable
{
    /**
     * @var array<string>
     */
    protected array $_links = [];

    protected ?string $_opening = null;

    /**
     * @var array<string>
     */
    protected array $helpers = ['Html', 'Form'];

    /**
     * Creates the main link that allows you to open the dropdown.
     *
     * @param string $title
     * @param array<string, mixed> $options Array of HTML attributes
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
     * Creates an HTML link, that submits a form to the given URL using the DELETE method, compatible with the dropdown
     *  submenu.
     *
     * @param string $title
     * @param array|string|null $url
     * @param array<string, mixed> $options Array of HTML attributes
     * @return self
     *
     * @see \Cake\View\Helper\FormHelper::deleteLink() for more information about the method arguments
     */
    public function deleteLink(string $title, array|string|null $url = null, array $options = []): self
    {
        $options = $this->addClass(options: $options, class: 'dropdown-item');

        $this->_links[] = $this->Form->deleteLink(title: $title, url: $url, options: $options);

        return $this;
    }

    /**
     * Creates an HTML link, compatible with the dropdown submenu.
     *
     * @param string $title
     * @param array|string $url
     * @param array<string, mixed> $options Array of HTML attributes
     * @return self
     *
     * @link \Cake\View\Helper\HtmlHelper::link() for more information about the method arguments
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
     * @param array<array-key, mixed> $params An array specifying any additional parameters. Can be also any special
     *  parameters supported by `Router::url()`
     * @param array<string, mixed> $options Array of HTML attributes
     * @return self
     *
     * @link \Cake\View\Helper\HtmlHelper::linkFromPath() for more information about the method arguments
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
     * @param array<string, mixed> $options Array of HTML attributes
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

    /**
     * Returns `true` if links have already been added to the dropdown submenu.
     *
     * Useful for conditional controls.
     *
     * @return bool
     */
    public function hasLinks(): bool
    {
        return !empty($this->_links);
    }

    /**
     * Builds and returns the entire dropdown, encapsulating the opening link and the links submenu in a wrapper.
     *
     * @param array<string, mixed> $options Additional HTML attributes for the wrapper
     * @return string
     * @throws \BadMethodCallException if the `create()` method or if no link methods have been called
     */
    public function render(array $options = []): string
    {
        if (empty($this->_opening)) {
            throw new BadMethodCallException(
                'The opening link has not been set, probably the `create()` method was not called previously.',
            );
        }
        if (empty($this->_links)) {
            throw new BadMethodCallException('Dropdown links have not been set');
        }

        // Builds the submenu
        $submenu = $this->Html->nestedList(list: $this->_links, options: ['class' => 'dropdown-menu']);

        // Builds the result
        $options = $this->addClass(options: $options, class: 'dropdown');
        $result = $this->Html->div(text: $this->_opening . $submenu, options: $options);

        // Resets properties
        $this->_opening = null;
        $this->_links = [];

        return $result;
    }

    /**
     * Magic method, returns the instance as a string and makes the dropdown "stringable".
     *
     * If you need to set some options for the wrapper, you need to call the `render()` method.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
