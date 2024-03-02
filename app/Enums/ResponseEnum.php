<?php declare(strict_types=1);

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ResponseEnum extends Enum
{
    const GET = 'get';
    const ADD = 'add';
    const DELETE = 'delete';
    const UPDATE = 'update';

}
