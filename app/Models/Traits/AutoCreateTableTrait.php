<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait AutoCreateTableTrait
{
    // decide split table
    protected bool $isSplitTable = true;

    // origin table name
    public string $originTable;

    // final table name
    public string $finalTable;

    // table suffix
    protected string|null $suffix = null;

    /**
     * example: 20220401
     * @var string
     */
    public string $ymd;

    public function init($suffix = null, array $attributes = []): void
    {
        $this->originTable = $this->table;
        $this->finalTable = $this->table;

        $this->ymd = Carbon::now()->format('Ymd');

        if ($this->isSplitTable) {
            $this->suffix = $suffix ?: $this->ymd;
        }

        $this->setSuffix();
    }

    /**
     * @param string $suffix
     * @return void
     */
    public function setSuffix(string $suffix = ''): void
    {
        if ($this->isSplitTable) {
            $this->suffix = $suffix ?: $this->ymd;
        }
        if ($this->suffix !== null) {
            $this->finalTable = $this->originTable . '_' . $this->suffix;
            $this->table = $this->finalTable;
        }

        // create table
        $this->createTable();
    }

    /**
     * provider a static function to set suffix
     * @param string|null $suffix
     * @return Builder
     */
    public static function suffix(string $suffix = null): Builder
    {
        $instance = new static;
        $instance->setSuffix($suffix);
        return $instance->newQuery();
    }

    /**
     * get new create table instance and return
     * @param $attributes
     * @param $exists
     * @return AutoCreateTableTrait
     */
    public function newInstance($attributes = [],  $exists = false)
    {
        $model = parent::newInstance($attributes, $exists);
        $model->setSuffix($this->suffix);
        return $model;
    }

    /**
     * create action
     * @return void
     */
    protected function createTable(): void
    {
        if (!Schema::hasTable($this->finalTable)) {
            DB::update("create table {$this->finalTable} like {$this->originTable}");
        }
    }
}
