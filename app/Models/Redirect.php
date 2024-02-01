<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Vinkla\Hashids\Facades\Hashids;

class Redirect extends Eloquent{
    // ...

    public function getCodeAttribute()
    {
        return Hashids::encode($this->id);
    }

    // ...
}