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
            ->editColumn('chart_type', function(Product $model) {

                $chart_type = "";
                if($model->fanchart == true){
                    $chart_type .= 'Fanchart,';
                }

                if($model->pedigree == true){
                    $chart_type .= ' Pedigree';
                }

                return $chart_type;
            })
            ->editColumn('fanchart_features', function(Product $model) {

                if($model->fanchart != true){
                    return '';
                }


                $max_output_png = [
                    '1' => '1344 x 839 px',
                    '2' => '2688 x 1678 px',
                    '3' => '4032 x 2517 px',
                    '4' => '5376 x 3356 px',
                    '5' => '6720 x 4195 px',
                ];

                $max_output_pdf = [
                    'a0' => 'A0',
                    'a1' => 'A1',
                    'a2' => 'A2',
                    'a3' => 'A3',
                    'a4' => 'A4',
                ];


                $print_type = "";
                if($model->fanchart_output_png == true){
                    $print_type .= 'PNG,';
                }

                if($model->fanchart_output_pdf == true){
                    $print_type .= ' PDF';
                }

                $features = "<ul>";
                $features .= "<li>Max generations : ".$model->fanchart_max_generation.'</li>';
                $features .= "<li>Print type : ".$print_type.'</li>';
                $features .= "<li>Max png size : ".$max_output_png[$model->fanchart_max_output_png].'</li>';
                $features .= "<li>Max pdf size : ".$max_output_pdf[$model->fanchart_max_output_pdf].'</li>';
                $features .= "</ul>";
                return $features;

            })
            ->editColumn('pedigree_features', function(Product $model) {

                if($model->pedigree != true){
                    return '';
                }

                $max_output_png = [
                    '1' => '1344 x 839 px',
                    '2' => '2688 x 1678 px',
                    '3' => '4032 x 2517 px',
                    '4' => '5376 x 3356 px',
                    '5' => '6720 x 4195 px',
                ];

                $max_output_pdf = [
                    'a0' => 'A0',
                    'a1' => 'A1',
                    'a2' => 'A2',
                    'a3' => 'A3',
                    'a4' => 'A4',
                ];

                $print_type = "";
                if($model->pedigree_output_png == true){
                    $print_type .= 'PNG,';
                }

                if($model->pedigree_output_pdf == true){
                    $print_type .= ' PDF';
                }

                $features = "<ul>";
                $features = "<li>Max generations : ".$model->pedigree_max_generation.'</li>';
                $features .= "<li>Max nodes : ".$model->max_nodes.'</li>';
                $features .= "<li>Print type : ".$print_type.'</li>';
                $features .= "<li>Max png size : ".$max_output_png[$model->pedigree_max_output_png].'</li>';
                $features .= "<li>Max pdf size : ".$max_output_pdf[$model->pedigree_max_output_pdf].'</li>';
                $features .= "</ul>";

                return $features;

            })
            /*
            ->editColumn('print_type', function(Product $model) {

                $print_type = "";
                if($model->fanchart_output_png == true){
                    $print_type .= 'PNG,';
                }

                if($model->output_pdf == true){
                    $print_type .= ' PDF';
                }

                return $print_type;

            })
            ->editColumn('max_output_png', function(Product $model) {

                $max_output_png = [
                    '1' => '1344 x 839 px',
                    '2' => '2688 x 1678 px',
                    '3' => '4032 x 2517 px',
                    '4' => '5376 x 3356 px',
                    '5' => '6720 x 4195 px',
                ];

                return $max_output_png[$model->max_output_png];

            })
            ->editColumn('max_output_pdf', function(Product $model) {

                $max_output_pdf = [
                    'a0' => 'A0',
                    'a1' => 'A1',
                    'a2' => 'A2',
                    'a3' => 'A3',
                    'a4' => 'A4',
                ];

                return $max_output_pdf[$model->max_output_pdf];

            })
            */
            ->rawColumns(['actions','chart_type','fanchart_features','pedigree_features'])
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
            Column::make('price'),
            Column::make('description'),
            Column::computed('chart_type')->title('Chart types'),
            Column::make('duration'),
            Column::make('print_number')->title('Max print'),

            Column::make('fanchart_features')->title('Fanchart features'),
            Column::make('pedigree_features')->title('pedigree features'),

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
