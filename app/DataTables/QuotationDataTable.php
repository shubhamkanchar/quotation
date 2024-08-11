<?php

namespace App\DataTables;

use App\Models\MakeQuotation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class QuotationDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('quotation_no', function($row) {
                return 'Quote-'.$row->quotation_no;
            })
            ->addColumn('customer_id', function($row) {
                return ucfirst($row->customer_name);
            })
            ->editColumn('quotation_date',function($row) {
                if($row->quotation_date) {
                    $date = Carbon::createFromFormat('Y-m-d', $row->quotation_date)->format('d-m-Y');
                    return $date;
                }
                return '-';
            })
            ->addColumn('action', function($row){
                return '<a href="'.route('make-quotation.edit',$row->uuid).'" class="btn btn-primary me-2" >Edit</a><button class="btn btn-danger delete-quotation" data-id="'.$row->uuid.'">Delete</button>';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(MakeQuotation $model): QueryBuilder
    {
        $user = auth()->user();
        return $model->newQuery()
        ->where('created_by', $user->id)
        ->join('customer_models', 'make_quotations.customer_id', '=', 'customer_models.id')
        ->select('make_quotations.*', 'customer_models.name as customer_name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('quotation-table')
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
            Column::make('quotation_no')->title('Quotation'),
            Column::make('quotation_date')->title('Quotation Date'),
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
        return 'Quotation_' . date('YmdHis');
    }
}
