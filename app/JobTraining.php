<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTraining extends Model
{
    protected $guarded = [
        'id'
    ];

    public function surat()
    {
        return $this->hasMany('App\Surat', 'jenis_surat', 'kode_surat');
    }
}
