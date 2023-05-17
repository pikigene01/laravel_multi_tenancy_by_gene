<?php

namespace App\DataTables\Admin;

use App\Facades\UtilityFacades;
use App\Models\Order;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SalesDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('expire_at', function (Order $order) {
                return ($order->orderUser) ? UtilityFacades::date_format($order->orderUser->plan_expired_date) : '';
            })
            ->editColumn('user_id', function (Order $order) {
                return ($order->orderUser) ? $order->orderUser->name : '';
            })
            ->editColumn('plan_id', function (Order $order) {
                return $order->Plan->name;
            })
            ->editColumn('amount', function (Order $order) {
                return UtilityFacades::amount_format($order->amount);
            })->editColumn('discount_amount', function (Order $order) {
                if ($order->discount_amount) {
                    return UtilityFacades::amount_format($order->discount_amount);
                } else {
                    return;
                }
            })
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_time_format($request->created_at);
            })
            ->editColumn('payment_type', function (Order $order) {
                if ($order->payment_type) {
                    return ucfirst($order->payment_type);
                } else {
                    return;
                }
            })
            ->editColumn('status', function (Order $order) {
                if ($order->status == 0 && $order->plan_id  == 1) {
                    return '<span class="badge rounded-pill bg-primary p-2 px-3">' . __('Free') . '</span>';
                } elseif ($order->status == 2 &&  $order->plan_id  > 1) {
                    return '<span class="badge rounded-pill bg-danger p-2 px-3"">' . __('Cancel') . '</span>';
                } elseif ($order->status == 1 &&  $order->plan_id  > 1) {
                    return '<span class="badge rounded-pill bg-success p-2 px-3">' . __('Success') . '</span>';
                } elseif ($order->status == 3 &&  $order->plan_id  > 1) {
                    return '<span class="badge rounded-pill bg-info p-2 px-3">' . __('Offline') . '</span>';
                } else {
                    return '<span class="badge rounded-pill bg-warning p-2 px-3">' . __('Pending') . '</span>';
                }
            })

            ->rawColumns(['amount', 'status']);
    }


    public function query(Order $model)
    {
        $form_values = Order::select(['orders.*', 'users.type'])->join('users', 'users.id', '=', 'orders.user_id');
        $form_values->where('users.type', '!=', 'Admin');
        return $form_values;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->language([
                "paginate" => [
                    "next" => '<i class="fas fa-angle-right"></i>',
                    "previous" => '<i class="fas fa-angle-left"></i>'
                ]
            ])
            ->parameters([
                "dom" =>  "
                                <'row'<'col-sm-12 p-0'><'col-sm-9 text-left'B><'col-sm-3'f>>
                                <'row'<'col-sm-12'tr>>
                                <'row align-items-center mt-3'<'col-sm-5'i><'col-sm-7'p>>
                                ",

                'buttons'   => [
                    ['extend' => 'export', 'className' => 'btn btn-primary btn-sm no-corner',],
                    ['extend' => 'print', 'className' => 'btn btn-primary btn-sm no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-primary btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-primary btn-sm no-corner',],
                    ['extend' => 'pageLength', 'className' => 'btn btn-primary btn-sm no-corner',],
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
                    'pageLength' => __('Show %d rows'),
                ]
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('user_name')->title(__('User Name'))->data('user_id')->name('user_id'),
            Column::make('plan_name')->title(__('Plan Name'))->data('plan_id')->name('plan_id'),
            Column::make('amount')->title(__('Amount')),
            Column::make('discount_amount')->title(__('Discount Amount')),
            Column::make('coupon_code')->title(__('Coupon Code')),
            Column::make('status')->title(__('Status')),
            Column::make('payment_type')->title(__('Payment Type')),
            Column::make('created_at')->title(__('Created At')),
            Column::make('expire_at')->title(__('Expire At'))->searchable(false)->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Sale_' . date('YmdHis');
    }
}
