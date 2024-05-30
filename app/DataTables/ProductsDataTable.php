<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('actions', 'admin.products.actions')
            ->editColumn('features', function(Product $model) {

                $raw_features = '<ul class="list-group">';

                foreach($model->features as $feature){
                    $raw_features .= '<li class="list-group-item d-flex align-items-center border-0">';
                    $raw_features .= '<i class="ti ti-circle-check ti-sm me-2"></i>';
                    $raw_features .= $feature;
                    $raw_features .= '</li>';
                }

                $raw_features .= "</ul>";
                return $raw_features;

            })
            ->rawColumns(['actions','features'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {

        return $this->builder()
                    ->setTableId('products-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters([
                        'dom'        => '<"row me-2 mb-4"' .
                                        '<"col-md-2"<"me-3"l>>' .
                                        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"B>>' .
                                        '>t' .
                                        '<"row mx-2 my-4 justify-content-between"' .
                                        '<"col-sm-12 col-md-6"i>' .
                                        '<"col-auto"p>' .
                                        '>',
                        'lengthMenu' => [10, 25, 50, [ 'label'=> 'All', 'value'=> -1 ]]
                    ])
                    ->orderBy(1)
                    ->buttons([
                        Button::raw('add_product')
                        ->className('mx-2 btn btn-primary rounded')
                        ->attr([
                            'data-bs-toggle'=>'offcanvas',
                            'data-bs-target'=> '#offcanvasAddProduct'
                            ])
                        ->text('<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Product</span>')
                    ]);


    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name'),
            Column::make('amount'),
            Column::make('description'),
            Column::make('features'),
            Column::make('created_at'),
            Column::computed('actions')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Products_' . date('YmdHis');
    }
}
