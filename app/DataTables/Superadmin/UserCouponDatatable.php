<?php

namespace App\DataTables\Superadmin;

use App\Facades\UtilityFacades;
use App\Models\Coupon;
use App\Models\EmailTracker;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use jdavidbakr\MailTracker\Model\SentEmail;

class UserCouponDatatable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('User', function ($request) {
                return !empty($request->userDetail) ? $request->userDetail->name : '';
            })
            ->addColumn('Request_Domain', function ($request) {
                return !empty($request->requestdomain) ? $request->requestdomain->name : '';
            })
            ->addColumn('Date', function ($request) {
                return UtilityFacades::date_time_format($request->created_at);
            });
    }

    public function query(UserCoupon $model, Request $request)
    {
        return $model->newQuery()->where('coupon', $request->id);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('usercoupon-table')
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

    protected function getColumns()
    {
        return [
            Column::make('id')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('User')->title(__('User')),
            Column::make('Request_Domain')->title('Request Domain Name'),
            Column::make('Date')->title(__('Date')),
        ];
    }

    protected function filename(): string
    {
        return 'UserCoupon_' . date('YmdHis');
    }
}
