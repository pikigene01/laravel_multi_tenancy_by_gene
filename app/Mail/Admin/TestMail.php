<?php

namespace App\Mail\Admin;

use Spatie\MailTemplates\TemplateMailable;

class TestMail extends TemplateMailable
{


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function getHtmlLayout(): string
    {
        return view('mails.layout')->render();
    }

}
