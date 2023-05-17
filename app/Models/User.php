<?php

namespace App\Models;

use App\Mail\Admin\PasswordResets;
use App\Mail\Superadmin\PasswordReset;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\MailTemplates\Models\MailTemplate;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use BelongsToTenant, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'tenant_id',
        'plan_id',
        'created_by',
        'address',
        'country',
        'phone',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function loginSecurity()
    {
        return $this->hasOne('App\Models\LoginSecurity');
    }
    public function currentLanguage()
    {
        return $this->lang;
    }

    public function getAvatarImageAttribute()
    {
        if (tenant('id') == null) {
            $avatar = Storage::exists($this->avatar) ? Storage::url($this->avatar) : Storage::url('avatar/avatar.png');
        } else {
            if (config('filesystems.default') == 'local') {
                $avatar = Storage::exists($this->avatar) ? Storage::url(tenant('id') . '/' . $this->avatar) : Storage::url('avatar/avatar.png');
            } else {
                $avatar = Storage::exists($this->avatar) ? Storage::url($this->avatar) : Storage::url('avatar/avatar.png');
            }
        }
        return $avatar;
    }

    public function assignPlan($planID)
    {
        $usr  = $this;
        $plan = Plan::find($planID);
        if ($plan) {
            $users     = User::where('tenant_id', tenant('id'))->where('type', '!=', 'Admin')->get();
            $userCount = 0;

            foreach ($users as $user) {
                $userCount++;
                $user->active_status = ($plan->max_users == -1 || $userCount <= $plan->max_users) ? 1 : 0;
                $user->save();
            }

            $this->plan_id = $plan->id;
            if ($plan->durationtype == 'Month' && $planID != '1') {
                $this->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
            } elseif ($plan->durationtype == 'Year' && $planID != '1') {
                $this->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
            } else {
                $this->plan_expired_date = null;
            }
            $this->save();

            return ['is_success' => true];
        } else {
            return [
                'is_success' => false,
                'errors' => __('Plan is deleted.'),
            ];
        }
    }

    public function sendPasswordResetNotification($token)
    {
        if (tenant()) {
            if (MailTemplate::where('mailable', PasswordResets::class)->first()) {
                $url = URL::temporarySignedRoute(
                    'password.reset',
                    \Illuminate\Support\Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                    [
                        'token' => $token,
                    ]
                );
                Mail::to($this->email)->send(new PasswordResets($this, $url));
            }
        } else {
            if (MailTemplate::where('mailable', PasswordReset::class)->first()) {
                $url = URL::temporarySignedRoute(
                    'password.reset',
                    \Illuminate\Support\Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                    [
                        'token' => $token,
                    ]
                );
                Mail::to($this->email)->send(new PasswordReset($this, $url));
            }
        }
    }
}
