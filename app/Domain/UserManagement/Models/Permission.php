<?php declare(strict_types=1);

namespace App\Domain\UserManagement\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasUlids,
        SoftDeletes;
}
