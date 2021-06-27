<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PaymentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use App\Imports\PaymentsImport;
use App\Models\Payment;

/**
 * Class PaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PaymentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Payment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/payment');
        CRUD::setEntityNameStrings('payment', 'payments');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::setFromDb(); // columns
        //$this->crud->enableExportButtons();

        $this->crud->addButtonFromModelFunction('top', 'insert', 'insertDataBtn', 'end');
        $this->crud->addButtonFromModelFunction('top', 'export', 'exportDataBtn', 'end');
        
        $this->crud->column('record_id');
        $this->crud->column('physician_first_name');
        $this->crud->column('physician_last_name');
        $this->crud->addColumn([
           'name'  => 'total_amount_of_payment_usdollars',
           'label' => 'Amount $']);
        $this->crud->column('nature_of_payment_or_transfer_of_value');
        $this->crud->column('date_of_payment');

        $this->crud->addFilter([
          'name'       => 'total_amount_of_payment_usdollars',
          'type'       => 'simple',
          'label'      => 'Show Amount Less than $10',
        ],
        false,
        function() { // if the filter is active
            //remove below Clause
        },
        function() { // if the filter is NOT active
            $this->crud->addClause('where', 'total_amount_of_payment_usdollars', '>=', 10);
        });

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PaymentRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    // public function search()
    // {

    //     return parent::search();
    // }

    function import(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        $path = $request->file('select_file')->getRealPath();
        $data = Excel::toArray(new PaymentsImport, $request->file('select_file'));

        if(count($data[0]) > 0)
        {
            $count = 0;
            $insert_data = [];
            foreach($data[0] as $value)
            {
                if($count == 0){
                    //setting head array from first row
                    $heading = $value;
                }else{
                    $row_data = [];
                    foreach($value as $key => $row)
                    {
                        //mapping long field name to real field name in table
                        if(strtolower($heading[$key]) == 'name_of_third_party_entity_receiving_payment_or_transfer_of_value'){
                            $field_name = 'name_of_third_party_entity_receiving_payment_or_transfer';
                        }else{
                            $field_name = strtolower($heading[$key]);
                        }

                        if($field_name == 'date_of_payment' || $field_name == 'payment_publication_date'){
                            //convert excel date number to right date format
                            $row = date('Y-m-d', ($row-25569)*86400);
                        }

                        $row_data[$field_name] = $row;
                    }
                    
                    array_push($insert_data, $row_data);
                }

                $count ++;
            }

            if(!empty($insert_data))
            {
                Payment::insert($insert_data);
            }
        }
        
        return back()->with('success', 'Excel Data Imported successfully.');
    }

    public function export(Request $request)
    {
        $query = Payment::query();
        if($request->is_filter_10_active == "false"){
            //Do not show amt less than $10
            $query = $query->where('total_amount_of_payment_usdollars', '>=', 10);
        }
        if($sv = $request->search_value){

            $query = $query->where(function($q) use($sv) {
                //$columns = Schema::getColumnListing('payments');
                $columns = ['record_id', 'physician_first_name', 'physician_last_name', 'total_amount_of_payment_usdollars', 'nature_of_payment_or_transfer_of_value', 'date_of_payment'];
                foreach($columns as $column){
                    $q->orWhere($column, 'LIKE', '%' . $sv . '%');
                }
            });
        }

        $array = $query->get()->toArray();

        $export = new PaymentsExport(
            $array
        );

        return Excel::download($export, 'payments.xlsx');
    }

    public function autocomplete()
    {
        $sv = trim(strip_tags($_GET['query'])); 

        $columns = ['record_id', 'physician_first_name', 'physician_last_name', 'total_amount_of_payment_usdollars', 'nature_of_payment_or_transfer_of_value', 'date_of_payment'];
        $get_columns = ['record_id', 'physician_first_name', 'physician_last_name', 'total_amount_of_payment_usdollars', 'nature_of_payment_or_transfer_of_value', 'date_of_payment'];
        $query = Payment::select($get_columns);
        $query = $query->where(function($q) use($sv, $columns) {
            
            foreach($columns as $column){
                $q->orWhere($column, 'LIKE', '%' . $sv . '%');
            }
        });
        $array = $query->limit(5)->get()->toArray();

        $reply = array();
        $reply['query'] = $sv;

        $reply['suggestions'] = array();
        foreach ($array as $ar) {
            $reply['suggestions'][] = ["value" => $ar['record_id'].' ('.implode (", ", $ar).')', "data" => $ar['record_id']];
        }

        echo json_encode($reply);
        
    }
}
