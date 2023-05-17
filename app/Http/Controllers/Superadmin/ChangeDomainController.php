<?php

namespace App\Http\Controllers\Superadmin;

use App\DataTables\Superadmin\ChangeDomainRequestDataTable;
use App\Http\Controllers\Controller;
use App\Mail\Superadmin\DisapprovedMail;
use App\Models\ChangeDomainRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\MailTemplates\Models\MailTemplate;
use Stancl\Tenancy\Database\Models\Domain;

class ChangeDomainController extends Controller
{
    public function changeDomainIndex(ChangeDomainRequestDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-change-domain')) {
            return $dataTable->render('superadmin.changedomain.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function changeDomainApprove($id)
    {
        if (\Auth::user()->can('edit-change-domain')) {

            $change_domain = ChangeDomainRequest::find($id);
            $change_domain->status = 1;
            $change_domain->save();
            $domain = Domain::where('tenant_id', $change_domain->tenant_id)->first();
            $domain->domain = $change_domain->domain_name;
            $domain->save();
            return redirect()->back()->with('success', __('Domain change successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function domainDisapprove($id)
    {
        if (\Auth::user()->can('edit-change-domain')) {

            $requestdomain = ChangeDomainRequest::find($id);
            $view =   view('superadmin.changedomain.reason', compact('requestdomain'));
            return ['html' => $view->render()];
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function domainDisapproveUpdate(Request $request, $id)
    {
        if (\Auth::user()->can('edit-change-domain')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'reason' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('errors', $messages->first());
            }
            $requestdomain = ChangeDomainRequest::find($id);
            $requestdomain->reason = $request->reason;
            $requestdomain->status = 2;
            $requestdomain->update();
            if (MailTemplate::where('mailable', DisapprovedMail::class)->first()) {
                try {
                    Mail::to($requestdomain->email)->send(new DisapprovedMail($requestdomain));
                } catch (\Exception $e) {
                    return redirect()->back()->with('errors', $e->getMessage());
                }
                return redirect()->back()->with('success', __('Change domain request disapprove successfully.'));
            }
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }
}
