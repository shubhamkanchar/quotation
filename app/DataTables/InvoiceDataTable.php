<?php

namespace App\DataTables;

use App\Models\MakeInvoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('invoice_no', function($row) {
            return 'Inv-'.$row->invoice_no;
        })
        ->addColumn('customer_id', function($row) {
            return ucfirst($row->customer_name);
        })
        ->editColumn('invoice_date',function($row) {
            if($row->invoice_date) {
                $date = Carbon::createFromFormat('Y-m-d', $row->invoice_date)->format('d-m-Y');
                return $date;
            }
            return '-';
        })
        ->addColumn('action', function($row){
            return '<a href="'.route('make-invoice.edit',$row->uuid).'" class="btn btn-primary me-2" >Edit</a><button class="btn btn-danger delete-invoice" data-id="'.$row->uuid.'">Delete</button>';
        })
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(MakeInvoice $model): QueryBuilder
    {
        $user = auth()->user();
        return $model->newQuery()
        ->where('created_by', $user->id)
        ->join('customer_models', 'make_invoices.customer_id', '=', 'customer_models.id')
        ->select('make_invoices.*', 'customer_models.name as customer_name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('invoice-table')
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
            Column::make('invoice_no')->title('Invoice'),
            Column::make('invoice_date')->title('Invoice Date'),
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
        return 'Invoice_' . date('YmdHis');
    }
}
