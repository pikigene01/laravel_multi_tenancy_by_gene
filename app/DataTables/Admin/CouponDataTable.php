<?php

namespace App\DataTables\Admin;

use App\Facades\UtilityFacades;
use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

class CouponDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function (Coupon $request) {
                return UtilityFacades::date_time_format($request->created_at);
            })
            ->editColumn('is_active', function (Coupon $copouns) {
                if ($copouns->is_active == '1') {
                    return '<span class="badge rounded-pill bg-success p-2 px-3">' . __('Active') . '</span>';
                } else {
                    return '<span class="badge rounded-pill bg-danger p-2 px-3">' . __('Deactive') . '</span>';
                }
            })
            ->editColumn('discount_type', function (Coupon $request) {
                if ($request->discount_type) {
                    return ucfirst($request->discount_type);
                } else {
                    return;
                }
            })
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_time_format($request->created_at);
            })
            ->addColumn('Used', function (Coupon $coupon) {
                return UserCoupon::where('coupon', $coupon->id)->count();
            })
            ->addColumn('action', function (Coupon $coupon) {
                return view('superadmin.coupon.action', compact('coupon'));
            })
            ->rawColumns(['action', 'Used', 'is_active']);
    }

    public function query(Coupon $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('coupon-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->language([
                "paginate" => [
                    "next" => '<i class="fas fa-angle-right"></i>',
                    "previous" => '<i class="fas fa-angle-left"></i>'
                ]
            ])
            ->parameters([
                "dom" =>  "<'row'<'col-sm-12'><'col-sm-9 text-left'B><'col-sm-3'f>>
        <'row'<'col-sm-12'tr>>
        <'row align-items-center mt-3'<'col-sm-5'i><'col-sm-7'p>>",
                'buttons'   => [
                    ['extend' => 'create', 'className' => 'btn btn-primary btn-sm no-corner add_module', 'action' => " function ( e, dt, node, config ) {
                window.location = '" . route('coupon.create') . "';

           }"],
                    ['extend' => 'export', 'className' => 'btn btn-primary btn-sm ',],
                    ['extend' => 'print', 'className' => 'btn btn-primary btn-sm ',],
                    ['extend' => 'reset', 'className' => 'btn btn-primary btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-primary btn-sm ',],
                    ['extend' => 'pageLength', 'className' => 'btn btn-primary btn-sm dropdown',],
                ],

                "scrollX" => true,
                "drawCallback" => 'function( settings ) {
                    var tooltipTriggerList = [].slice.call(
                        document.querySelectorAll("[data-bs-toggle=tooltip]")
                      );
                      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                      });
                      var popoverTriggerList = [].slice.call(
                        document.querySelectorAll("[data-bs-toggle=popover]")
                      );
                      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                        return new bootstrap.Popover(popoverTriggerEl);
                      });
                      var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                      var toastList = toastElList.map(function (toastEl) {
                        return new bootstrap.Toast(toastEl);
                      });
                }'
            ])->language([
                'buttons' => [
                    'create' => __('Create'),
                    'export' => __('Export'),
                    'print' => __('Print'),
                    'reset' => __('Reset'),
                    'reload' => __('Reload'),
                    'excel' => __('Excel'),
                    'csv' => __('CSV'),
                    'pageLength' => __('Show %d rows '),
                ],
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('code')->title(__('Code')),
            Column::make('discount_type')->title(__('Discount Type')),
            Column::make('discount')->title(__('Discount')),
            Column::make('limit')->title(__('Limit')),
            Column::make('Used')->title(__('Used')),
            Column::make('is_active')->title(__('Status')),
            Column::make('created_at')->title(__('Created At')),
            Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width('20%')
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Coupon_' . date('YmdHis');
    }
}
