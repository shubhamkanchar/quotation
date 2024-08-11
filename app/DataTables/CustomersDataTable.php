<?php

namespace App\DataTables;

use App\Models\Customer;
use App\Models\CustomerModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CustomersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('action', function($row){
            return '<a href="'.route('customer.edit',$row->id).'" class="btn btn-primary me-2 mb-1" >Edit</a><button class="btn btn-danger delete-customer" data-id="'.$row->id.'">Delete</button>';
        })
        ->editColumn('updated_at',function($row){
            return date('d-m-Y', strtotime($row->updated_at));
        })
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(CustomerModel $model): QueryBuilder
    {
        $user = auth()->user();
        return $model->where('user_id', $user->id)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('customers-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    // ->selectStyleSingle()
                    ->buttons([
                        // Button::make('excel'),
                        // Button::make('csv'),
                        // Button::make('pdf'),
                        // Button::make('print'),
                        // Button::make('reset'),
                        // Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            
            // Column::make('id'),
            Column::make('name'),
            Column::make('company_name'),
            Column::make('email'),
            Column::make('number'),
            Column::make('address_1'),
            Column::make('address_2'),
            Column::make('other_info'),
            Column::make('gstin'),
            Column::make('state'),
            Column::make('shipping_address'),
            Column::make('updated_at'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(160)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Customers_' . date('YmdHis');
    }
}
