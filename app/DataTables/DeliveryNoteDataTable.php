<?php

namespace App\DataTables;

use App\Models\MakeDeliveryNote;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DeliveryNoteDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('order_no', function($row) {
            return 'Order-'.$row->order_no;
        })
        ->addColumn('customer_id', function($row) {
            return ucfirst($row->customer_name);
        })
        ->editColumn('delivery_date',function($row) {
            if($row->delivery_date) {
                $date = Carbon::createFromFormat('Y-m-d', $row->delivery_date)->format('d-m-Y');
                return $date;
            }
            return '-';
        })
        ->addColumn('action', function($row){
            return '<a href="'.route('make-delivery-note.edit',$row->uuid).'" class="btn btn-primary me-2" >Edit</a><button class="btn btn-danger delete-delivery-note" data-id="'.$row->uuid.'">Delete</button>';
        })
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(MakeDeliveryNote $model): QueryBuilder
    {
        $user = auth()->user();
        return $model->newQuery()
        ->where('created_by', $user->id)
        ->join('customer_models', 'make_delivery_notes.customer_id', '=', 'customer_models.id')
        ->select('make_delivery_notes.*', 'customer_models.name as customer_name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('deliverynote-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('order_no')->title('Order No'),
            Column::make('delivery_date')->title('Delivery Date'),
            Column::make('customer_name')->title('To User')->searchable('true')->name('customer_models.name'),
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
        return 'DeliveryNote_' . date('YmdHis');
    }
}
