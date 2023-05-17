<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\SupportTicketDataTable;
use App\Http\Controllers\Controller;
use App\Mail\Superadmin\SupportTicketMail;
use App\Models\Conversions;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MailTemplates\Models\MailTemplate;

class SupportTicketController extends Controller
{
    public function index(SupportTicketDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-support-ticket')) {
            return $dataTable->render('admin.support_ticket.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create-support-ticket')) {
            return  view('admin.support_ticket.create');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create-support-ticket')) {
            $order = tenancy()->central(function ($tenant) use ($request) {
                $validation = [
                    'subject' => 'required|string|max:255',
                    'status' => 'required|string|max:100',
                    'description' => 'required',
                ];
                if ($request->hasfile('attachments')) {
                    $validation['attachments.*'] = 'mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800';
                }
                $this->validate($request, $validation);
                $post              = $request->all();
                $post['ticket_id'] = time();
                $data              = [];
                if ($request->hasfile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $name = $file->getClientOriginalName();
                        $file->storeAs('/tickets/' . $post['ticket_id'], $name);
                        $data[] = $name;
                    }
                }
                $post['attachments'] = json_encode($data);
                $post['tenant_id'] = Auth::user()->tenant_id;
                $post['name'] = Auth::user()->name;
                $post['email'] = Auth::user()->email;
                $ticket   = SupportTicket::create($post);
                $super_admin_mail = tenancy()->central(function ($tenant) {
                    return User::where('type', 'Super Admin')->first()->email;
                });
                if (MailTemplate::where('mailable', SupportTicketMail::class)->first()) {
                    try {
                        Mail::to($super_admin_mail)->send(new SupportTicketMail($ticket));
                    } catch (\Exception $e) {
                        return redirect()->back()->with('errors', $e->getMessage());
                    }
                }
            });
            return redirect()->route('support-ticket.index')->with('success', __('Support ticket created successfully.'));
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
            $conversion = tenancy()->central(function ($tenant) use ($id) {
                return Conversions::all();
            });
            return view('admin.support_ticket.edit', compact('supportticket', 'conversion'));
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
