<?php

namespace App\Models;

use App\Models\Traits\AutoCreateTableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory, AutoCreateTableTrait;

    protected $table = 'error_log';

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->init();
    }
}
