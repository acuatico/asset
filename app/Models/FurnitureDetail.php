<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FurnitureDetail extends Model
{
    use PerformanceCondition;

    protected $guarded = ['id'];
    protected $table = 'asset_furniture_details';

    public $timestamps = false;

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function setInstallDateAttribute($value)
    {
        if ($value != '') {
            $this->attributes['install_date'] = Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d');
        }
    }
}
