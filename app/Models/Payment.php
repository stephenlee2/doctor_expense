<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'payments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    //button HTML - creating without view
    public function insertDataBtn($crud)
    {
        return '
            <form method="post" enctype="multipart/form-data" action="'. url('admin/import') .'">
            '. csrf_field() .'
                <div class="form-group">
                    <input type="file" name="select_file" />
                    <input type="submit" name="upload" class="btn btn-primary" value="Upload">
                </div>
            </form>
        ';
    }

    public function exportDataBtn($crud)
    {
        return '
            <form method="post" enctype="multipart/form-data" action="'. url('admin/export') .'">
            '. csrf_field() .'
                <div class="form-group">
                    <input type="hidden" name="is_filter_10_active" id="is_filter_10_active" value="false" />
                    <input type="hidden" name="search_value" id="search_value" value="" />
                    <input type="submit" name="export" class="btn btn-primary" value="Export (Excel)">
                </div>
            </form>
        ';
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
