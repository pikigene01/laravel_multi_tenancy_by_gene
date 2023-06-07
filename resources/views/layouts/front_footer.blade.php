<section class="footer">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="footer-logo">
                    @if (Utility::getsettings('dark_mode') == 'on')
                        <img src="{{ Storage::exists('logo/app-logo.png') ? Utility::getpath('logo/app-logo.png') : Storage::url('logo/app-logo.png') }}"
                            class="app-logo img_setting">
                    @else
                        <img src="{{ Storage::exists('logo/app-dark-logo.png') ? Utility::getpath('logo/app-dark-logo.png') : Storage::url('logo/app-dark-logo.png') }}"
                            class="app-logo img_setting">
                    @endif
                </div>
            </div>
            <div class="col-6 text-end">
                <ul class="list-inline mb-1">
                    <li class="list-inline-item">
                        <a href="{{ route('privacypolicy') }}" class="link-primary">{{ __('Privacy Policy') }}</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ route('contactus') }}" class="link-primary">{{ __('Contact Us') }}</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ route('termsandconditions') }}"
                            class="link-primary">{{ __('Terms And Conditions') }}</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ route('faq') }}" class="link-primary">{{ __('FAQs') }}</a>
                    </li>
                </ul>
                <p class="text-body">© {{ date('Y') }},
                    Risk App
                    {{-- {{ config('app.name') }} --}}
                </p>
            </div>
        </div>
    </div>


</section>
<!-- [ dashboard ] End -->
<!-- Required Js -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/wow.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/notifier.js') }}"></script>
<script src="{{ asset('vendor/modules/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bouncer.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-validation.js') }}"></script>
@include('layouts.includes.alerts')
<script>
    // Start [ Menu hide/show on scroll ]
    let ost = 0;
    document.addEventListener("scroll", function() {
        let cOst = document.documentElement.scrollTop;
        if (cOst == 0) {
            document.querySelector(".navbar").classList.add("top-nav-collapse");
        } else if (cOst > ost) {
            document.querySelector(".navbar").classList.add("top-nav-collapse");
            document.querySelector(".navbar").classList.remove("default");
        } else {
            document.querySelector(".navbar").classList.add("default");
            document
                .querySelector(".navbar")
                .classList.remove("top-nav-collapse");
        }
        ost = cOst;
    });
    // End [ Menu hide/show on scroll ]
    var wow = new WOW({
        animateClass: "animate__animated", // animation css class (default is animated)
    });
    wow.init();
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: "#navbar-example",
    });
</script>
