<?php

namespace App\DataTables\Superadmin;

use App\Facades\UtilityFacades;
use App\Models\ChangeDomainRequest;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ChangeDomainRequestDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('domainlink', function (ChangeDomainRequest $requestdomain) {
                $central_domainip = gethostbyname(config('tenancy.central_domains')[0]);
                $requestdomainip = gethostbyname($requestdomain->domain_name);
                if ($central_domainip == $requestdomainip) {
                    return '<span class="badge rounded-pill bg-success p-2 px-3">' . __('Linked') . '</span>';
                } else {
                    return '<span class="badge rounded-pill bg-danger p-2 px-3">' . __('Not Link At') . '</span>';
                }
            })
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_time_format($request->created_at);
            })
            ->addColumn('action', function (ChangeDomainRequest $requestdomain) {
                return view('superadmin.changedomain.action', compact('requestdomain'));
            })
            ->editColumn('status', function (ChangeDomainRequest $requestdomain) {
                if ($requestdomain->status == 1) {
                    return '<span class="badge rounded-pill bg-success p-2 px-3">' . __('Success') . '</span>';
                } elseif ($requestdomain->status == 2) {
                    return '<span class="badge rounded-pill bg-danger p-2 px-3">' . __('Disapproved') . '</span>';
                } else {
                    return '<span class="badge rounded-pill bg-warning p-2 px-3">' . __('Pending') . '</span>';
                }
            })
            ->rawColumns(['action', 'status', 'domainlink']);
    }

    public function query(ChangeDomainRequest $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('changedomainrequest-table')
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
                                       <'row'<'col-sm-12 p-0'><'col-sm-9 'B><'col-sm-3'f>>
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
            Column::make('name')->title(__('Name')),
            Column::make('email')->title(__('Email')),
            Column::make('domain_name')->title(__('Domain Name')),
            Column::make('actual_domain_name')->title(__('Actual Domain Name')),
            Column::make('status')->title(__('Status'))->orderable(false)->searchable(false),
            Column::computed('domainlink')->title(__('Domain link')),
            Column::make('created_at')->title(__('Created At')),
            Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->width('20%'),
        ];
    }

    protected function filename(): string
    {
        return 'ChangeDomainRequest_' . date('YmdHis');
    }
}
