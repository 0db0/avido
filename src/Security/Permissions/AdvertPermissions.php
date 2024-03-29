<?php

namespace App\Security\Permissions;

final class AdvertPermissions
{
    public const CREATE             = 'create_advert';
    public const LIST               = 'view_any_advert';
    public const SHOW               = 'view_advert';
    public const EDIT               = 'edit_advert';
    public const DELETE             = 'delete_advert';
    public const PUSH_TO_MODERATION = 'push_to_moderation_advert';

    public const MODERATE = 'moderate';

    public const ADVERT_PERMISSIONS = [
        self::CREATE,
        self::SHOW,
        self::EDIT,
        self::DELETE,
        self::PUSH_TO_MODERATION,
    ];
}
