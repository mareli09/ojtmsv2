<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CMS extends Model
{
    protected $table = 'cms_settings';
    
    protected $fillable = [
        'key',
        'value',
        'section',
    ];
    
    public static function getValue($key)
    {
        $cms = self::where('key', $key)->first();
        return $cms ? $cms->value : null;
    }
}
