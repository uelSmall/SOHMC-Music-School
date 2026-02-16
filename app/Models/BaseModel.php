<?php

namespace App\Models;

use App\Models\Traits\HasHashedMediaTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BaseModel extends Model implements HasMedia
{
    use HasHashedMediaTrait, SoftDeletes;

    protected $guarded = [
        'id',
        'updated_at',
        '_token',
        '_method',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at'   => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function fill(array $attributes)
    {
        unset($attributes['_token'], $attributes['_method']);
        return parent::fill($attributes);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(250)
            ->height(250)
            ->quality(70);

        $this->addMediaConversion('thumb300')
            ->width(300)
            ->height(300)
            ->quality(70);
    }

    /**
     * Get the list of all the Columns of the table.
     *
     * @return array Column names array
     */
    public function getTableColumns()
    {
        $table_name = DB::getTablePrefix() . $this->getTable();

        switch (config('database.default')) {
            case 'sqlite':
                $columns = DB::select("PRAGMA table_info({$table_name});");
                break;

            case 'mysql':
            case 'mariadb':
                $columns = DB::select('SHOW COLUMNS FROM ' . $table_name);
                $columns = array_map(function ($column) {
                    return [
                        'name'    => $column->Field,
                        'type'    => $column->Type,
                        'notnull' => $column->Null,
                        'key'     => $column->Key,
                        'default' => $column->Default,
                        'extra'   => $column->Extra,
                    ];
                }, $columns);
                break;

            case 'pgsql':
                $columns = DB::select("
                    SELECT 
                        column_name,
                        data_type,
                        is_nullable,
                        column_default
                    FROM information_schema.columns 
                    WHERE table_name = '{$table_name}'
                      AND table_schema = 'public';
                ");

                $columns = array_map(function ($column) {
                    return [
                        'name'    => $column->column_name,
                        'type'    => $column->data_type,
                        'notnull' => $column->is_nullable === 'NO' ? 'NO' : 'YES',
                        'key'     => '', // can be extended with pg_constraint if needed
                        'default' => $column->column_default,
                        'extra'   => '',
                    ];
                }, $columns);
                break;

            default:
                $columns = [];
                break;
        }

        return json_decode(json_encode($columns));
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->attributes['status']) {
            case '0':
                return '<span class="badge bg-danger">Inactive</span>';
            case '1':
                return '<span class="badge bg-success">Active</span>';
            case '2':
                return '<span class="badge bg-warning text-dark">Pending</span>';
            default:
                return '<span class="badge bg-primary">Status:' . $this->status . '</span>';
        }
    }

    public function getStatusLabelTextAttribute()
    {
        switch ($this->attributes['status']) {
            case '0':
                return 'Inactive';
            case '1':
                return 'Active';
            case '2':
                return 'Pending';
            default:
                return $this->status;
        }
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = slug_format(trim($value));
        if (empty($value)) {
            $this->attributes['slug'] = slug_format(trim($this->attributes['name']));
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($table) {
            $table->created_by = Auth::id();
            $table->created_at = Carbon::now();
        });

        static::updating(function ($table) {
            $table->updated_by = Auth::id();
        });

        static::saving(function ($table) {
            $table->updated_by = Auth::id();
        });

        static::deleting(function ($table) {
            $table->deleted_by = Auth::id();
            $table->save();
        });
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', '=', 1);
    }
}
