<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUser extends Pivot
{
    /**
     * {@inheritdoc}
     */
    public $incrementing = true;
}
