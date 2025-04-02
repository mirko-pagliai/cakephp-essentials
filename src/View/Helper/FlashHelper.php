<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use Cake\View\Helper;

/**
 * FlashHelper.
 *
 * @property \Cake\Essentials\View\Helper\AlertHelper $Alert
 */
class FlashHelper extends Helper
{
    /**
     * @var array
     */
    protected array $helpers = ['Alert'];
}
