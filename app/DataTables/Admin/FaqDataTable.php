<?php

namespace App\DataTables\Admin;

use App\Facades\UtilityFacades;
use App\Models\Faq;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FaqDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_time_format($request->created_at);
            })
            ->addColumn('action', function (Faq $faqs) {
                return view('admin.faqs.action', compact('faqs'));
            })
            ->rawColumns(['action']);
    }

    public function query(Faq $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
        ->setTableId('faq-table')
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
                ['extend' => 'create', 'className' => 'btn btn-primary btn-sm no-corner add_module', 'action' => " function ( e, dt, node, config ) {
                    window.location = '" . route('faqs.create') . "';

               }"],
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
            Column::make('No')->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('quetion')->title(__('Quetion')),
            Column::make('order')->title(__('Order')),
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
        return 'Faq_' . date('YmdHis');
    }
}
