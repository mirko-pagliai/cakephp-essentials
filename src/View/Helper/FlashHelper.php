<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use Cake\View\Helper;
use UnexpectedValueException;

/**
 * FlashHelper.
 *
 * @property \Cake\Essentials\View\Helper\AlertHelper $Alert
 */
class FlashHelper extends Helper
{
    /**
     * @var array<string>
     */
    protected array $helpers = ['Alert'];

    /**
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        /**
         * Default alert type used when `FlashComponent::set()` is called
         */
        'default' => 'primary',
    ];

    /**
     * Renders the message set in FlashComponent::set().
     *
     * @param string $key The [Flash.]key you are rendering in the view
     * @param array<string, mixed> $options Additional options to use for the creation of this flash message. Supports
     *  the `params` and `element` keys that are used in the helper
     * @return string|null Rendered flash message or null if flash key does not exist in session
     * @throws \UnexpectedValueException If the value for flash settings key is not an array
     */
    public function render(string $key = 'flash', array $options = []): ?string
    {
        $stack = $this->getView()->getRequest()->getSession()->consume("Flash.$key");
        if ($stack === null) {
            return null;
        }

        if (!is_array($stack)) {
            throw new UnexpectedValueException(sprintf('Value for flash setting key "%s" must be an array', $key));
        }

        if (isset($stack['element'])) {
            $stack = [$stack];
        }

        $out = '';
        /** @var array<array<string, mixed>> $stack */
        foreach ($stack as $message) {
            /** @var array{message: string, key: string, element: string, params: array<string, mixed>} $message */
            $message = $options + $message;

            $type = explode('/', $message['element'])[1];
            if ($type == 'default') {
                /** @var string $type */
                $type = $this->getConfigOrFail('default');
            } elseif ($type == 'error') {
                $type = 'danger';
            }

            //Adds class to exclude flash messages when printing
            $message['params'] = $this->addClass(options: $message['params'], class: 'd-print-none');

            $out .= $this->Alert->alert(type: $type, text: $message['message'], options: $message['params']);
        }

        return $out;
    }
}
