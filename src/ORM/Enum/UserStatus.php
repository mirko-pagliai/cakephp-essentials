<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Enum;

use Cake\Database\Type\EnumLabelInterface;
use Override;
use function Cake\I18n\__d as __d;

/**
 * UserStatus.
 *
 * This enum can adequately describe the `status` field of your `UsersTable`.
 *
 * It provides 4 distinct cases:
 * - `Active`: the user is active, and can log in and everything that comes with it;
 * - `Disabled`: the user has been disabled, and cannot log in, and therefore no other operations;
 * - `RequiresUserActivation`: indicates that the user must activate his account personally, for example by clicking on
 *  the link in an email sent to him after registration (requires implementation of this code). Until then, he will not
 *  be able to log in, and therefore no other operations;
 * - `RequiresAdminActivation`: similar to the previous, but requires an administrator to activate the account (always
 *  requires code implementation). Again, until that time he will not be able to log in, and therefore no other operations.
 *
 * The `status` field of the `users` table must be a 25-character varchar.
 *
 * In your `UsersTable::initialize()` method:
 * ```
 * $this->getSchema()->setColumnType('status', \Cake\Database\Type\EnumType::from(\Cake\Essentials\ORM\Enum\UserStatus::class));
 * ```
 *
 * in your `validationDefault()` method:
 * ```
 * $validator->enum('status', UserStatus::class);
 * ```
 *
 * @see \App\Model\Table\UsersTable::initialize()
 */
enum UserStatus: string implements EnumLabelInterface
{
    case Active = 'active';

    case Disabled = 'disabled';

    case RequiresUserActivation = 'requires_user_activation';

    case RequiresAdminActivation = 'requires_admin_activation';

    /**
     * @inheritDoc
     */
    #[Override]
    public function label(): string
    {
        return match ($this) {
            self::Active => __d('cake/essentials', 'Active'),
            self::Disabled => __d('cake/essentials', 'Disabled'),
            self::RequiresUserActivation => __d('cake/essentials', 'It requires user activation'),
            self::RequiresAdminActivation =>__d('cake/essentials', 'It requires administrator activation'),
        };
    }
}
