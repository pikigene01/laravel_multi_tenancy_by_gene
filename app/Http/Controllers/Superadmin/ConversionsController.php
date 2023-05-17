<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Mail\Superadmin\SendTicketReplyMail;
use App\Models\Conversions;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MailTemplates\Models\MailTemplate;

class ConversionsController extends Controller
{
    public function store(Request $request, $ticket_id)
    {
            $user = \Auth::user();
            $ticket = SupportTicket::find($ticket_id);
            $validation = ['reply_description' => ['required']];
            if ($request->hasfile('reply_attachments')) {
                $validation['reply_attachments.*'] = 'mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800';
            }
            $this->validate($request, $validation);

            $post = [];
            $post['sender'] = Auth::user()->id;
            $post['ticket_id'] = $ticket->id;
            $post['description'] = $request->reply_description;
            $data = [];
            if ($request->hasfile('reply_attachments')) {
                foreach ($request->file('reply_attachments') as $file) {
                    $name = $file->getClientOriginalName();
                    $file->storeAs('/tickets/' . $ticket->ticket_id, $name);
                    $data[] = $name;
                }
            }
            $post['attachments'] = json_encode($data);
            $conversion = Conversions::create($post);

             $get_mail_id = SupportTicket::where('id',$conversion->ticket_id)->first();

             if (MailTemplate::where('mailable', SendTicketReplyMail::class)->first()) {
                try {
                    Mail::to($get_mail_id->email)->send(new SendTicketReplyMail($conversion,$ticket));
                } catch (\Exception $e) {
                    return redirect()->back()->with('errors', $e->getMessage());
                }
            }
        return redirect()->back()->with('success', __('Reply created successfully.'));
    }
}
