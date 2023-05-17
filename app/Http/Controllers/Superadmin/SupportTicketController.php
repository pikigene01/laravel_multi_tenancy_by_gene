<?php

namespace App\Http\Controllers\Superadmin;

use App\DataTables\Superadmin\SupportTicketDataTable;
use App\Http\Controllers\Controller;
use App\Models\Conversions;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index(SupportTicketDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-support-ticket')) {
            return $dataTable->render('superadmin.support_ticket.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit-support-ticket')) {
            $supportticket = tenancy()->central(function ($tenant) use ($id) {
                return SupportTicket::find($id);
            });
            return view('superadmin.support_ticket.edit', compact('supportticket'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit-support-ticket')) {
            $order = tenancy()->central(function ($tenant) use ($request, $id) {
                $supportticket = SupportTicket::find($id);
                $validation = [
                    'subject' => 'required|string|max:255',
                    'status' => 'required|string|max:100',
                    'description' => 'required',
                ];
                if ($request->hasfile('attachments')) {
                    $validation['attachments.*'] = 'mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800';
                }
                $this->validate($request, $validation);
                $post = $request->all();
                if ($request->hasfile('attachments')) {
                    $data = json_decode($supportticket->attachments, true);
                    foreach ($request->file('attachments') as $file) {
                        $name = $file->getClientOriginalName();
                        $file->storeAs('/tickets/' . $supportticket->ticket_id, $name);
                        $data[] = $name;
                    }
                    $post['attachments'] = json_encode($data);
                }
                $supportticket->update($post);
            });
            return redirect()->route('support-ticket.index')->with('success', __('Support ticket updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-support-ticket')) {
            $order = tenancy()->central(function ($tenant) use ($id) {
                $supportticket = SupportTicket::find($id);
                $conversions = Conversions::where('ticket_id', $id);
                $supportticket->delete();
                $conversions->delete();
            });
            return redirect()->route('support-ticket.index')->with('success', __('Support ticket deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }
}
