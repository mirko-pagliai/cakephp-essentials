<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Table\Traits;

use Cake\Essentials\ORM\Enum\UserStatus;
use Cake\ORM\Query\SelectQuery;

/**
 * @psalm-require-extends \Cake\ORM\Table
 */
trait UserStatusFindMethodsTrait
{
    public function findActive(SelectQuery $Query): SelectQuery
    {
        return $Query->where(['status' => UserStatus::Active]);
    }
}
