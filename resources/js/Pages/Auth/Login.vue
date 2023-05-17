
<template>
  <FrontHeaderVue :app_values="this.app_values"></FrontHeaderVue>
  <li class="nav-item" style="height:100px;background:#333;width:100%;">
        <!-- <select class="btn btn-primary my-1 me-2 "
            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"
            id="language">
             <div v-for="(language, index) in this.languages" :key="index">

                <option v-if="(this.lang == language)" class="" selected
                    value="{{ '/login/' + language }}">{{ language.upperCase() }}
                </option>
                <option v-if="(this.lang !== language)" class=""
                    value="{{ '/login/' + language }}">{{ language.upperCase() }}
                </option>
                </div>
        </select> -->
    </li>

    <div class="card-body">
        <div class="">
            <h2 class="mb-3 f-w-600">{{ 'Sign in' }}</h2>
        </div>
        <div class="">
            <!-- <form method="POST" data-validate action="/login"> -->
                {{ this.app_values.csrf_token }}
                <div class="form-group mb-3">
                    <label class="form-label" for="email">{{ 'Email Address' }}</label>
                    <input type="email" v-model="this.loginForm.email" class="form-control" :placeholder="'Enter email address'" name="email"
                        tabindex="1" required autocomplete="email" autofocus>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="password">{{ 'Enter Password' }}</label>
                    <a :href="'/password/request'" class="text-small float-end">
                        {{ 'Forgot Password ?' }}
                    </a>
                    <input id="password" type="password" v-model="this.loginForm.password" class="form-control" :placeholder=" 'Enter password' "
                        name="password" tabindex="2" required autocomplete="current-password">
                </div>
                <div class="form-group mb-4">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="customswitch1" />
                        <label class="form-check-label" for="customswitch1">{{ 'Remember me' }}</label>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" @click="submitSend()" class="btn btn-primary btn-block mt-2">
                        {{ 'Sign In' }}
                    </button>
                </div>
            <!-- </form> -->
            <!-- @if (tenant())
                <div class="my-4 text-center">
                    {{ 'Do not have an account ?') }} <a href="{{ route('register') }}">{{ __('Create One') }}</a>
                </div>
            @endif
            @if (Utility::getsettings('googlesetting') == 'on' ||
                Utility::getsettings('facebooksetting') == 'on' ||
                Utility::getsettings('githubsetting') == 'on')
                <p class="my-4 text-center">{{ __('or register with') }}</p>
            @endif
            <div class="row mb-4">
                @if (Utility::getsettings('googlesetting') == 'on')
                    <div class="col-4">
                        <a href="{{ url('/redirect/google') }}">
                            <div class="d-grid">
                                <button class="btn btn-light">
                                    <img src="{{ asset('assets/images/auth/img-google.svg') }}" alt=""
                                        class="img-fluid wid-25" />
                                </button>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Utility::getsettings('facebooksetting') == 'on')
                    <div class="col-4">
                        <div class="d-grid">
                            <a href="{{ url('/redirect/facebook') }}">
                                <button class="btn btn-light">
                                    <img src="{{ asset('assets/images/auth/img-facebook.svg') }}" alt=""
                                        class="img-fluid wid-25" />
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
                @if (Utility::getsettings('githubsetting') == 'on')
                    <div class="col-4">
                        <div class="d-grid">
                            <a href="{{ url('/redirect/github') }}">
                                <button class="btn btn-light">
                                    <img src="{{ asset('assets/images/auth/github.svg') }}" alt=""
                                        class="img-fluid wid-25" />
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
            </div>-->
        </div>
    </div>

</template>
 <script>
import { useForm,Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import FrontHeaderVue from '../Layouts/FrontHeader.vue';
export default {
  components: { FrontHeaderVue,Link },
  props: ["languages", "app_values"],

  data() {
    return {
      loginForm: useForm({
       name: null,
        id: null,
        email: null,
        password: null,
      }),
    };
  },
 mounted(){
  },
  methods: {
    submitSend() {
      this.loginForm.post('/login', {
        preserveScroll: true,
        onSuccess: () => {
          this.loginForm.reset();
          window.location = "/home";
          // document.getElementById("closeEventModel").click();
        },
      });
    },

  },
};
</script>
