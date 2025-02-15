<?php

namespace App\DataTables;

use App\Models\Fantree;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class FantreesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('actions', 'superuser.fantree.actions')
            ->editColumn('user_name', function(Fantree $model) {
                return $model->user->firstname.' '.$model->user->lastname;

            })
            ->editColumn('user_email', function(Fantree $model) {

                return $model->user->email;

            })
            ->editColumn('created_at', function(Fantree $model) {

                return  $model->created_at;

            })

            ->rawColumns(['actions','user_name','user_email','created_at'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Fantree $model): QueryBuilder
    {
        $query = $model->where('user_id','<>',Auth::user()->id)->with('user')->newQuery();

        
        
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {

        return $this->builder()
                    ->setTableId('fantress-table')
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
                    ->buttons([]);


    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('user_name')->title('User Name'),
            Column::computed('user_email')->title('User Email'),
            Column::computed('created_at')->title('Created At'),
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
        return 'Fantrees_' . date('YmdHis');
    }
}
