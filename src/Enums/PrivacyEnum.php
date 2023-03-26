<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Enums;

enum PrivacyEnum: string
{
    case PUBLIC = 'PUBLIC';
    case PROTECTED = 'PROTECTED';
    case SECRET = 'SECRET';
}
