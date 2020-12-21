<?php

namespace Crater;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = "language";
    protected $fillable = [
        'parent',
        'key_value',
        'value_en',
        'value_da'
    ];
}
