<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\MailTemplates\Models\MailTemplate;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        $allpermissions = [
            'manage-permission', 'create-permission', 'edit-permission', 'delete-permission',
            'manage-role', 'create-role', 'edit-role', 'delete-role', 'show-role',
            'manage-user', 'create-user', 'edit-user', 'delete-user', 'show-user', 'impersonate-user',
            'manage-module', 'create-module', 'delete-module', 'show-module', 'edit-module',
            'manage-setting',
            'manage-transaction',
            'manage-landingpage',
            'manage-chat',
            'manage-langauge', 'create-langauge', 'delete-langauge', 'show-langauge', 'edit-langauge',
            'manage-plan', 'create-plan', 'delete-plan', 'show-plan', 'edit-plan',
            'manage-blog', 'create-blog', 'delete-blog', 'show-blog', 'edit-blog',
            'manage-category', 'create-category', 'delete-category', 'show-category', 'edit-category',
            'manage-email-template',
            'manage-support-ticket', 'create-support-ticket', 'edit-support-ticket', 'delete-support-ticket',
            'manage-domain-request', 'create-domain-request', 'edit-domain-request', 'delete-domain-request',
            'manage-change-domain', 'create-change-domain', 'edit-change-domain', 'delete-change-domain',
            'manage-faqs', 'create-faqs', 'edit-faqs', 'delete-faqs',
            'manage-coupon', 'create-coupon', 'edit-coupon', 'delete-coupon', 'show-coupon', 'mass-create-coupon', 'upload-coupon',
            'manage-frontend',
        ];
        $adminpermissions = [
            'manage-permission', 'create-permission', 'edit-permission', 'delete-permission',
            'manage-role', 'create-role', 'edit-role', 'delete-role', 'show-role',
            'manage-user', 'create-user', 'edit-user', 'delete-user', 'show-user', 'impersonate-user',
            'manage-setting',
            'manage-transaction',
            'manage-plan',
            'manage-chat',
            'manage-landingpage',
            'manage-blog', 'create-blog', 'delete-blog', 'show-blog', 'edit-blog',
            'manage-category', 'create-category', 'delete-category', 'show-category', 'edit-category',
            'manage-email-template',
            'manage-support-ticket', 'create-support-ticket', 'edit-support-ticket', 'delete-support-ticket',
            'manage-coupon', 'create-coupon', 'edit-coupon', 'delete-coupon',
            'manage-faqs', 'create-faqs', 'edit-faqs', 'delete-faqs',
            'manage-frontend',

        ];

        $modules = [
            'user', 'role', 'module', 'setting', 'langauge', 'permission', 'plan', 'chat', 'blog', 'category', 'coupon',
            'landingpage', 'email-template', 'support-ticket', 'domain-request', 'change-domain', 'faqs', 'frontend',
        ];

        $settings = [
            ['key' => 'app_name', 'value' => 'Full Multi Tenancy Laravel Admin Saas'],
            ['key' => 'app_logo', 'value' => 'logo/app-logo.png'],
            ['key' => 'favicon_logo', 'value' => 'logo/app-favicon-logo.png'],
            ['key' => 'default_language', 'value' => 'en'],
            ['key' => 'currency', 'value' => 'USD'],
            ['key' => 'currency_symbol', 'value' => '$'],
            ['key' => 'date_format', 'value' => 'M j, Y'],
            ['key' => 'time_format', 'value' => 'g:i A'],
            ['key' => 'color', 'value' => 'theme-1'],
            ['key' => 'settingtype', 'value' => 'local'],
            ['key' => 'roles', 'value' => 'User'],
            ['key' => 'landing_page_status', 'value' => '1'],
        ];
        foreach ($settings as $setting) {
            Setting::create($setting);
        }
        foreach ($allpermissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }
        $plan = Plan::create([
            'name' => 'Free',
            'price' => '0',
            'duration' => '1',
            'durationtype' => 'month',
        ]);

        $role = Role::create([
            'name' => 'Super Admin'
        ]);
        $adminRole = Role::create([
            'name' => 'Admin'
        ]);

        foreach ($allpermissions as $permission) {
            $per = Permission::findByName($permission);
            $role->givePermissionTo($per);
        }
        foreach ($adminpermissions as $permission) {
            $per = Permission::findByName($permission);
            $adminRole->givePermissionTo($per);
        }

        $user = User::create([
            'name' => 'Super Admin By Gene',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'avatar' => 'avatar/avatar.png',
            'type' => 'Super Admin',
            'lang' => 'en',
            'email_verified_at' => Carbon::now(),

        ]);

        $user->assignRole($role->id);

        foreach ($modules as $module) {
            Module::create([
                'name' => $module
            ]);
        }
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\TestMail::class,
            'subject' => 'Mail send for testing purpose',
            'html_template' => '<p><strong>This Mail For Testing</strong></p>

            <p><strong>Thanks</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\ApproveMail::class,
            'subject' => 'Domain Verified',
            'html_template' => '<p><strong>Hi {{name}}</strong></p>

            <p><strong>Yout Domain&nbsp;{{ domain_name }}&nbsp;&nbsp;is Verified By SuperAdmin</strong></p>

            <p><strong>Please Check with by click below link</strong></p>

            <p><a href="{{login_button_url}}">Login</a></p>

            <p>&nbsp;</p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\DisapprovedMail::class,
            'subject' => 'Domain Unverified',
            'html_template' => '<p><strong>Hi&nbsp;{{ name }}</strong></p>

            <p><strong>Your Domain&nbsp;{{ domain_name }}&nbsp;is not Verified By SuperAdmin </strong></p>

            <p><strong>Because&nbsp;{{ reason }}</strong></p>

            <p><strong>Please contact to SuperAdmin</strong></p>

            <p>&nbsp;</p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\Approve_OfflineMail::class,
            'subject' => 'Offline Payment Request Verified',
            'html_template' => '<p><strong>Hi&nbsp;&nbsp;{{ name }}</strong></p>

            <p><strong>Your Plan Update Request {{ email }}&nbsp;is Verified By Super Admin</strong></p>

            <p><strong>Please Check</strong></p>

            <p>&nbsp;</p>

            <p>&nbsp;</p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\OfflineMail::class,
            'subject' => 'Offline Payment Request Unverified',
            'html_template' => '<p><strong>Hi&nbsp;{{ name }}</strong></p>

            <p><strong>Your Request Payment {{ email }}&nbsp;Is Disapprove By Super Admin</strong></p>

            <p><strong>Because&nbsp;{{ disapprove_reason }}</strong></p>

            <p><strong>Please Contact to Super Admin</strong></p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\ConatctMail::class,
            'subject' => 'New Enquiry Details',
            'html_template' => '<p><strong>Name : {{name}}</strong></p>

            <p><strong>Email : </strong><strong>{{email}}</strong></p>

            <p><strong>Contact No : {{ contact_no }}&nbsp;</strong></p>

            <p><strong>Message :&nbsp;</strong><strong>{{ message }}</strong></p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\PasswordReset::class,
            'subject' => 'Reset Password Notification',
            'html_template' => '<p><strong>Hello!</strong></p><p>You are receiving this email because we received a password reset request for your account. To proceed with the password reset please click on the link below:</p><p><a href="{{url}}">Reset Password</a></p><p>This password reset link will expire in 60 minutes.&nbsp;<br>If you did not request a password reset, no further action is required.</p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\SupportTicketMail::class,
            'subject' => 'New Ticket Opened',
            'html_template' => '<p><strong>New Ticket Create {{ name }}</strong></p>

            <p><strong>A request for new Ticket&nbsp;&nbsp;{{ ticket_id }}</strong></p>

            <p><strong>Title : {{ title }}</strong></p>

            <p><strong>Email : {{ email }}</strong></p>

            <p><strong>Description :&nbsp;{{ description }}</strong></p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\ReceiveTicketReplyMail::class,
            'subject' => 'Received Ticket Reply',
            'html_template' => '<p><strong>Your Ticket id&nbsp; {{ ticket_id }} New&nbsp;Reply</strong></p>

            <p><strong>{{ reply }}</strong></p>

            <p><strong>Thank you</strong></p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\SendTicketReplyMail::class,
            'subject' => 'Send Ticket Reply',
            'html_template' => '<p><strong>Your Ticket id&nbsp; {{ ticket_id }} New&nbsp;Reply</strong></p>

            <p><strong>{{ reply }}</strong></p>

            <p><strong>Thank you</strong></p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Superadmin\RegisterMail::class,
            'subject' => 'Register Mail',
            'html_template' => '<p><strong>Hi {{name}}</strong></p>

            <p><strong>Email : {{email}}</strong></p>

            <p><strong>Domain : {{domain_name}}</strong></p>

            <p><strong>Note:Please link your domain with {{domain_ip}} ip address</strong></p>
            <p><strong>Thanks for registration, your account is in review and you get email when your account active.</strong></p>',
            'text_template' => null,
        ]);
    }
}
