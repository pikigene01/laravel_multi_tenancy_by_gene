<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\EmailTemplateDataTable;
use Illuminate\Http\Request;
use Spatie\MailTemplates\Models\MailTemplate;

class EmailTemplateController extends Controller
{
    public function index(EmailTemplateDataTable $dataTable)
    {
        return $dataTable->render('admin.email_template.index');
    }

    public function edit($id)
    {
        $mail_template = MailTemplate::find($id);
        return view('admin.email_template.edit', compact('mail_template'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject' => 'required',
            'html_template' => 'required',
        ]);
        $input = $request->all();
        $mail_template = MailTemplate::find($id);
        $mail_template->update($input);
        return redirect()->route('email-template.index')->with('success', __('Email template updated successfully.'));
    }
}
