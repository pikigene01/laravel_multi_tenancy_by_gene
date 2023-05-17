<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        $allpermissions = [
            'manage-user', 'create-user', 'edit-user', 'delete-user', 'show-user', 'impersonate-user',
            'manage-role', 'create-role', 'edit-role', 'delete-role', 'show-role',
            'manage-setting',
            'manage-chat',
            'manage-transaction',
            'manage-plan', 'create-plan', 'delete-plan', 'show-plan', 'edit-plan',
            'manage-landingpage',
            'manage-blog', 'create-blog', 'delete-blog', 'show-blog', 'edit-blog',
            'manage-category', 'create-category', 'delete-category', 'show-category', 'edit-category',
            'manage-email-template',
            'manage-support-ticket', 'create-support-ticket', 'edit-support-ticket', 'delete-support-ticket',
            'manage-coupon', 'create-coupon', 'edit-coupon', 'delete-coupon', 'show-coupon', 'mass-create-coupon', 'upload-coupon',
            'manage-faqs', 'create-faqs', 'edit-faqs', 'delete-faqs',
            'manage-frontend',
        ];
        $adminpermissions = [
            'manage-user', 'create-user', 'edit-user', 'delete-user', 'show-user', 'impersonate-user',
            'manage-role', 'create-role', 'edit-role', 'delete-role', 'show-role',
            'manage-setting',
            'manage-chat',
            'manage-transaction',
            'manage-plan', 'create-plan', 'delete-plan', 'show-plan', 'edit-plan',
            'manage-landingpage',
            'manage-blog', 'create-blog', 'delete-blog', 'show-blog', 'edit-blog',
            'manage-category', 'create-category', 'delete-category', 'show-category', 'edit-category',
            'manage-email-template',
            'manage-coupon', 'create-coupon', 'edit-coupon', 'delete-coupon', 'show-coupon', 'mass-create-coupon', 'upload-coupon',
            'manage-support-ticket', 'create-support-ticket', 'edit-support-ticket', 'delete-support-ticket',
            'manage-faqs', 'create-faqs', 'edit-faqs', 'delete-faqs',
            'manage-frontend',
        ];

        $modules = [
            'user', 'role', 'setting', 'plan', 'chat', 'blog', 'category', 'landingpage', 'email-template', 'faqs', 'frontend', 'coupon'
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
            ['key' => 'landing_page_status', 'value' => '1'],

        ];
        tenancy()->central(function ($tenant) {
            Storage::copy('logo/app-logo.png', $tenant->id . '/logo/app-logo.png');
            Storage::copy('logo/app-small-logo.png', $tenant->id . '/logo/app-small-logo.png');
            Storage::copy('logo/app-favicon-logo.png', $tenant->id . '/logo/app-favicon-logo.png');
            Storage::copy('logo/app-dark-logo.png', $tenant->id . '/logo/app-dark-logo.png');
            Storage::copy('avatar/avatar.png', $tenant->id . '/avatar/avatar.png');
        });

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        foreach ($allpermissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }

        $adminRole = Role::create([
            'name' => 'Admin'

        ]);


        foreach ($adminpermissions as $permission) {
            $per = Permission::findByName($permission);
            $adminRole->givePermissionTo($per);
        }

        $centralUser = tenancy()->central(function ($tenant) {
            return User::find($tenant->id);
        });
        $Role = new Role();
        $Role->name = 'User';
        $Role->tenant_id = $centralUser->tenant_id;
        $Role->save();
        // $Role = Role::create([
        //     'name' => 'User',
        //     'tenant_id' => $centralUser->tenant_id,
        // ]);
        $user = User::create([
            'name' => $centralUser->name,
            'email' =>  $centralUser->email,
            'password' =>  $centralUser->password,
            'avatar' => 'avatar/avatar.png',
            'type' => 'Admin',
            'lang' => 'en',
            'plan_id' => 1,
            'plan_expired_date' => null,
            'email_verified_at' => $centralUser->email_verified_at,

        ]);

        $user->assignRole($adminRole->id);

        foreach ($modules as $module) {
            Module::create([
                'name' => $module
            ]);
        }

        $plan = Plan::create([
            'name' => 'Free',
            'price' => '0',
            'duration' => '1',
            'durationtype' => 'Year',
            'max_users' => '10'
        ]);


        MailTemplate::create([
            'mailable' => \App\Mail\Admin\TestMail::class,
            'subject' => 'Mail send for testing purpose',
            'html_template' => '<p><strong>This Mail For Testing</strong></p>

            <p><strong>Thanks</strong></p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Admin\Approve_OfflineMail::class,
            'subject' => 'Offline Payment Request Verified',
            'html_template' => '<p><strong>Hi&nbsp;&nbsp;{{ name }}</strong></p>

            <p><strong>Your Plan Update Request {{ email }}&nbsp;is Verified By Super Admin</strong></p>

            <p><strong>Please Check</strong></p>

            <p>&nbsp;</p>

            <p>&nbsp;</p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Admin\OfflineMail::class,
            'subject' => 'Offline Payment Request Unverified',
            'html_template' => '<p><strong>Hi&nbsp;{{ name }}</strong></p>

            <p><strong>Your Request Payment {{ email }}&nbsp;Is Disapprove By Super Admin</strong></p>

            <p><strong>Because&nbsp;{{ disapprove_reason }}</strong></p>

            <p><strong>Please Contact to Super Admin</strong></p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Admin\ConatctMail::class,
            'subject' => 'New Enquiry Details',
            'html_template' => '<p><strong>Name : {{name}}</strong></p>

            <p><strong>Email : </strong><strong>{{email}}</strong></p>

            <p><strong>Contact No : {{ contact_no }}&nbsp;</strong></p>

            <p><strong>Message :&nbsp;</strong><strong>{{ message }}</strong></p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Admin\PasswordResets::class,
            'subject' => 'Reset Password Notification',
            'html_template' => '<p><strong>Hello!</strong></p><p>You are receiving this email because we received a password reset request for your account. To proceed with the password reset please click on the link below:</p><p><a href="{{url}}">Reset Password</a></p><p>This password reset link will expire in 60 minutes.&nbsp;<br>If you did not request a password reset, no further action is required.</p>',
            'text_template' => null,
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\Admin\RegisterMail::class,
            'subject' => 'Register Mail',
            'html_template' => '<p><strong>Hi {{name}}</strong></p>

            <p><strong>Email {{email}}</strong></p>

            <p><strong>Register Successfully</strong></p>',
            'text_template' => null,
        ]);
    }
}
