<?php

namespace App\DataTables\users;

use App\Models\Pedigree;
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


class PedigreesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('actions', 'users.pedigree.actions')
            ->editColumn('name', function(Pedigree $model) {

                return $model->name;

            })
            ->editColumn('user_name', function(Pedigree $model) {
                return $model->user->firstname.' '.$model->user->lastname;

            })
            ->editColumn('user_email', function(Pedigree $model) {

                return $model->user->email;

            })
            ->editColumn('generation', function(Pedigree $model) {
                $generation = 0;
                if($model->stats != null){
                    return $model->stats['generation'] ? $model->stats['generation'] . ' generations' : '0 generations';
                }
                else{
                    return '0 generations';
                }
                

            })
            ->editColumn('indis', function(Pedigree $model) {
                if($model->stats != null){
                    return $model->stats['indis'] ? $model->stats['indis'] . ' individual' : '0 individual';
                }
                else{
                    return '0 individual';
                }
                

            })
            ->editColumn('print_number', function(Pedigree $model) {
                return $model->print_number != null ? $model->print_number . ' exports' : '0 exports';

            })
            ->editColumn('created_at', function(Pedigree $model) {

                return  $model->created_at;

            })

            ->rawColumns(['actions','name','user_name','user_email','generation','indis','print_number','created_at'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Pedigree $model): QueryBuilder
    {

        $user = Auth::user();
        if($user->hasRole('superadmin')){
            $query = $model->with('user')->newQuery();
            return $query;
        }
        if($user->hasRole('superuser')){
            $query = $model->where('user_id',Auth::user()->id)->with('user')->newQuery();
            return $query;
        }


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
                    ->buttons([
                        Button::raw('add_pedigree')
                        ->className('mx-2 btn btn-primary rounded')
                        ->attr([
                            'data-bs-toggle'=>'offcanvas',
                            'data-bs-target'=> '#offcanvasAddPedigree'
                            ])
                        ->text('<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Pedigree</span>')
                    ]);


    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('name')->title('Name'),
            Column::computed('user_name')->title('User Name'),
            Column::computed('user_email')->title('User Email'),
            Column::computed('generation')->title('Generations'),
            Column::computed('indis')->title('Individuals'),
            Column::computed('print_number')->title('Number of exports'),
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
        return 'Pedigrees_' . date('YmdHis');
    }
}
