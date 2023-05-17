 @extends('layouts.app')
 @section('title', __('Two Factor Authentication'))
 @section('content')
     <div class="card-body">
         <div class="">
             <h2 class="mb-3 f-w-600">{{ __('Two Factor Authentication') }}</h2>
         </div>
         <div class="">
             @if ($errors->any())
                 <div class="alert alert-danger">
                     <ul>
                         @foreach ($errors->all() as $error)
                             <li>{{ $error }}</li>
                         @endforeach
                     </ul>
                 </div>
             @endif
             <small class="text-muted">{{ __('Enter the pin from Google Authenticator app:') }}</small><br /><br />
             <form class="form-horizontal" data-validate action="{{ route('2faVerify') }}" method="POST">
                 @csrf
                 <div class="form-group {{ $errors->has('one_time_password-code') ? 'is-invalid' : '' }} mb-4">
                     <label for="one_time_password" class="form-label">{{ __('One Time Password') }}</label>
                     <input id="one_time_password" placeholder="{{ __('Enter one time password') }}"
                         name="one_time_password" class="form-control col-md-4" type="text" required />
                 </div>
                 <div class="d-grid">
                 <button class="btn btn-primary btn-block mt-2" type="submit">{{ __('Authenticate') }}</button>
                 </div>
             </form>
         </div>
     </div>
 @endsection
