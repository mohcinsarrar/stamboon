<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('actions', 'admin.users.actions')
            ->editColumn('name', 'admin.users.name')
            ->editColumn('status', 'admin.users.status')
            ->editColumn('plan', function(User $model) {

                $payment = $model->has_payment();

                if($payment != false){
                    return $payment->product->name;
                }
                else{
                    $payment = $model->last_payment();
                    if($payment != false){
                        return $payment->product->name;
                    }
                    else{
                        return '';
                    }
                    
                }

            })
            ->editColumn('billing_date', function(User $model) {

                $payment = $model->has_payment();
                
                if($payment != false){
                    return Carbon::parse($payment->created_at)->format('Y-m-d');
                }
                else{
                    $payment = $model->last_payment();

                    if($payment != false){
                        return Carbon::parse($payment->created_at)->format('Y-m-d');
                    }
                    else{
                        return '';
                    }
                    
                }

            })
            ->editColumn('expired_date', function(User $model) {

                $payment = $model->has_payment();
                if($payment != false){
                    return $payment->active_until();
                }
                else{
                    $payment = $model->last_payment();
                    if($payment != false){
                        return $payment->active_until();
                    }
                    else{
                        return '';
                    }
                    
                }

            })
            ->rawColumns(['actions','name','status','plan', 'billing_date', 'expired_date'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->with('payments.product')->newQuery();
        $query = $query->role('user');

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {

        return $this->builder()
                    ->setTableId('users-table')
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
            Column::make('name'),
            Column::make('email'),
            Column::make('status'),
            Column::computed('plan')->title('Plan'),
            Column::computed('billing_date')->title('Billing at'),
            Column::computed('expired_date')->title('Active until'),
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
        return 'Users_' . date('YmdHis');
    }
}
