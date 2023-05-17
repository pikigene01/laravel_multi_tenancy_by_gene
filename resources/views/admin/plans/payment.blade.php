@extends('layouts.main')
@push('css')
    <style>
        #card-element {
            border: 1px solid #a3afbb !important;
            border-radius: 10px !important;
            padding: 10px !important;
        }
    </style>
@endpush
@section('title', __('Order Summary'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">{{ __('Plan') }}</a></li>
    <li class="breadcrumb-item">{{ __('Order Summary') }}</li>
@endsection
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-lg-12">

            <div class="form-group">
                {{ Form::label('billing_address', __('Billing Address:'), ['class' => 'form-label']) }}
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-4">
                            <div class="flex-equal">
                                <table class="table fs-6 gs-0 gy-2 gx-2 m-0">
                                    <tr>
                                        <td class="text-muted">{{ __('Bill to') }}:</td>
                                        <td>
                                            <span class="text-gray-800 text-hover-primary">{{ Auth::user()->email }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">{{ __('Name') }}:</td>
                                        <td class="">{{ Auth::user()->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="flex-equal">
                                <table class="table fs-6 gs-0 gy-2 gx-2 m-0">
                                    <tr>
                                        <td class="text-muted">{{ __('Subscription plan') }}:</td>
                                        <td class="">
                                            <span class="text-gray-800 text-hover-primary">{{ $plan->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">{{ __('Subscription Description') }}:</td>
                                        <td class="">
                                            <span
                                                class="text-gray-800 text-hover-primary">{{ isset($plan->description) ? $plan->description : '--' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">{{ __('Subscription Duration') }}:</td>
                                        <td class="">
                                            {{ $plan->duration . ' ' . $plan->durationtype }}
                                        </td>
                                    </tr>
                                    <tr class="total_payable">
                                        <td class="text-muted">{{ __('Subscription Fees') }}:</td>
                                        <td class="">
                                            {{ $admin_payment_setting['currency_symbol'] }}{{ number_format($plan->price, 2) }}/{{ $plan->durationtype }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">{{ __('Disscount Price') }}:</td>
                                        <td class="discount_price">
                                            {{ $admin_payment_setting['currency_symbol'] }}0.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">{{ __('Total Price') }}:</td>
                                        <td class="final-price">
                                            {{ $admin_payment_setting['currency_symbol'] }}{{ number_format($plan->price, 2) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::label('payment_methods', __('Payment Methods'), ['class' => 'form-label']) }}
                    </div>
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        @if ($paymenttypes)
                            @foreach ($paymenttypes as $key => $paymenttype)
                                @php
                                    if (array_key_first($paymenttypes) == $key) {
                                        $active = 'active show';
                                    } else {
                                        $active = '';
                                    }
                                @endphp

                                <li class="nav-item">
                                    <a class="nav-link text-uppercase {{ $active }} "
                                        id="{{ str_replace(' ', '_', $key) }}-tab" data-bs-toggle="tab"
                                        href="#payment{{ $key }}" role="tab" aria-controls="payment"
                                        aria-selected="true">{{ $paymenttype }}</a>
                                </li>
                            @endforeach
                        @else
                            <h2>{{ 'Please contact to super admin for enable payments.' }}</h2>
                        @endif
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        @foreach ($paymenttypes as $key => $paymenttype)
                            @if ($key == 'stripe' &&
                                $admin_payment_setting['stripesetting'] == 'on' &&
                                !empty($admin_payment_setting['stripe_key']) &&
                                !empty($admin_payment_setting['stripe_secret']))
                                @php
                                    $route = 'stripe.pending';
                                    $id = 'stripe-payment-form';
                                    $button_type = 'submit';
                                @endphp
                            @elseif ($key == 'paypal' &&
                                $admin_payment_setting['paypalsetting'] == 'on' &&
                                !empty($admin_payment_setting['paypal_client_id']) &&
                                !empty($admin_payment_setting['paypal_client_secret']))
                                @php
                                    $route = 'payprocessTransactionadmin';
                                    $id = 'payment-form';
                                    $button_type = 'submit';
                                @endphp
                            @elseif ($key == 'razorpay' &&
                                isset($admin_payment_setting['razorpaysetting']) &&
                                $admin_payment_setting['razorpaysetting'] == 'on')
                                @php
                                    $route = 'payrazorpay.payment';
                                    $id = 'razorpay-payment-form';
                                    $button_type = 'button';
                                @endphp
                            @elseif ($key == 'paytm' &&
                                isset($admin_payment_setting['paytmsetting']) &&
                                $admin_payment_setting['paytmsetting'] == 'on')
                                @php
                                    $route = 'paypaytm.payment';
                                    $id = 'paytm-payment-form';
                                    $button_type = 'submit';
                                @endphp
                            @elseif ($key == 'paystack' &&
                                isset($admin_payment_setting['paystacksetting']) &&
                                $admin_payment_setting['paystacksetting'] == 'on')
                                @php
                                    $route = 'paypaystack.payment';
                                    $id = 'paystack-payment-form';
                                    $button_type = 'button';
                                @endphp
                            @elseif ($key == 'flutterwave' &&
                                isset($admin_payment_setting['flutterwavesetting']) &&
                                $admin_payment_setting['flutterwavesetting'] == 'on')
                                @php
                                    $route = 'payflutterwave.payment';
                                    $id = 'flutterwave-payment-form';
                                    $button_type = 'button';
                                @endphp
                            @elseif ($key == 'coingate' &&
                                isset($admin_payment_setting['coingatesetting']) &&
                                $admin_payment_setting['coingatesetting'] == 'on')
                                @php
                                    $route = 'coingate.payment.prepare';
                                    $id = 'coingate-payment-form';
                                    $button_type = 'submit';
                                @endphp
                            @elseif ($key == 'mercado' &&
                                isset($admin_payment_setting['mercadosetting']) &&
                                $admin_payment_setting['mercadosetting'] == 'on')
                                @php
                                    $route = 'mercado.payment.prepare';
                                    $id = 'payment-form';
                                    $button_type = 'submit';
                                @endphp
                            @elseif ($key == 'offline' &&
                                isset($admin_payment_setting['offlinesetting']) &&
                                $admin_payment_setting['offlinesetting'] == 'on')
                                @php
                                    $route = 'offline.payment.request';
                                    $id = 'offline-payment-form';
                                    $button_type = 'submit';
                                @endphp
                            @endif

                            @php
                                if (array_key_first($paymenttypes) == $key) {
                                    $active = 'active show';
                                } else {
                                    $active = '';
                                }
                            @endphp

                            <div class="tab-pane fade {{ $active }}" id="payment{{ $key }}" role="tabpanel"
                                aria-labelledby="{{ str_replace(' ', '_', $key) }}-tab">
                                <div id="stripe_payment" class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5>{{ __('Payment Methods') }}</h5>
                                        <h5 class="text-muted">{{ __($paymenttype) }}</h5>
                                    </div>
                                    <div class="tab-pane {{ ($admin_payment_setting['stripesetting'] == 'on' && !empty($admin_payment_setting['stripe_key']) && !empty($admin_payment_setting['stripe_secret'])) == 'on' ? 'active' : '' }}"
                                        id="stripe_payment">
                                        <form role="form" action="{{ route($route) }}" method="post"
                                            class="require-validation" id="{{ $id }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @if ($key == 'paytm')
                                                        <div class="form-group">
                                                            <label for="mobile_number"
                                                                class="form-label">{{ __('Mobile Number') }}</label>
                                                            <input type="number" id="mobile_number" required
                                                                name="mobile_number" class="form-control"
                                                                placeholder="{{ __('Enter mobile number') }}">
                                                        </div>
                                                    @endif
                                                    <div class="d-flex align-items-center">
                                                        <div class="form-group w-100">
                                                            <label for="paypal_coupon"
                                                                class="form-label">{{ __('Coupon') }}</label>
                                                            <input type="text" id="stripe_coupon" name="coupon"
                                                                class="form-control coupon"
                                                                placeholder="{{ __('Enter coupon code') }}">
                                                        </div>
                                                        <div class="form-group ms-3 mt-4">
                                                            <a href="#" class="text-muted " data-bs-toggle="tooltip"
                                                                title="{{ __('Apply') }}"><i
                                                                    class="ti ti-square-check btn-apply"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="{{ $button_type }}" value="{{ __('Pay Now') }}"
                                                        id="pay_with_{{ $key }}" class="btn btn-primary">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <script src="{{ asset('vendor/jquery-form/jquery.form.js') }}"></script>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    @if (env('PAYTM_ENVIRONMENT') == 'production')
        <script type="application/javascript" crossorigin="anonymous" src="https:\\securegw.paytm.in\merchantpgpui\checkoutjs\merchants\{{ Auth::user()->type == 'Admin' ? env('PAYTM_MERCHANT_ID') : Utility::getsettings('paytm_merchant_id') }}.js" ></script>
    @else
        <script type="application/javascript" crossorigin="anonymous" src="https:\\securegw-stage.paytm.in\merchantpgpui\checkoutjs\merchants\{{ Auth::user()->type == 'Admin' ? env('PAYTM_MERCHANT_ID') : Utility::getsettings('paytm_merchant_id') }}.js" ></script>
    @endif
    <script
        src="https://www.paypal.com/sdk/js?client-id={{ Auth::user()->type == 'Admin' ? env('PAYPAL_SANDBOX_CLIENT_ID') : Utility::getsettings('paypal_sandbox_client_id') }}">
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        });
        $(document).ready(function() {
            $(document).on('click', '.btn-apply', function() {

                var ele = $(this);
                var coupon = ele.closest('.row').find('.coupon').val();
                $.ajax({
                    url: '{{ route('apply.coupon') }}',
                    datType: 'json',
                    data: {
                        plan_id: '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}',
                        coupon: coupon
                    },
                    success: function(data) {
                        if (data.final_price) {
                            $('.discount_price').text(data.discount_price);
                            $('.final-price').text(data.final_price);
                        }
                        $('#stripe_coupon, #paypal_coupon').val(coupon);
                        if (data != '') {
                            if (data.is_success == true) {
                                notifier.show('Successfully!', data.message, 'success',
                                    '{{ asset('assets/images/notification/ok-48.png') }}',
                                    4000);
                            } else {
                                notifier.show('Error!', data.message, 'danger',
                                    '{{ asset('assets/images/notification/high_priority-48.png') }}',
                                    4000);
                            }
                        } else {
                            notifier.show('Error!', "{{ __('Coupon code required.') }}",
                                'danger',
                                '{{ asset('assets/images/notification/high_priority-48.png') }}',
                                4000);
                        }
                    }
                })
            });
        });

        @if (isset($admin_payment_setting['paystacksetting']) && $admin_payment_setting['paystacksetting'] == 'on')
            $(document).on("click", "#pay_with_paystack", function() {
                $('#paystack-payment-form').ajaxForm(function(res) {
                    var paystack_callback = "{{ url('/paystack/transaction/callback') }}";
                    var order_id = '{{ time() }}';
                    var coupon_id = res.coupon;
                    var handler = PaystackPop.setup({
                        key: '{{ $admin_payment_setting['paystack_key'] }}',
                        email: res.email,
                        amount: res.total_price * 100,
                        currency: res.currency,
                        ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                            1
                        ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                        metadata: {
                            custom_fields: [{
                                display_name: "Email",
                                variable_name: "email",
                                value: res.email,
                            }]
                        },

                        callback: function(response) {

                            window.location.href = paystack_callback + '/' + response
                                .transaction +
                                '/' + coupon_id +
                                '/' + res.plan_id
                        },
                        onClose: function() {
                            alert('window closed');
                        }
                    });
                    handler.openIframe();
                }).submit();
            });
        @endif
        @if (isset($admin_payment_setting['flutterwavesetting']) && $admin_payment_setting['flutterwavesetting'] == 'on')
            $(document).on("click", "#pay_with_flutterwave", function() {

                $('#flutterwave-payment-form').ajaxForm(function(res) {

                    var coupon_id = res.coupon;
                    var API_publicKey = '{{ $admin_payment_setting['flutterwave_key'] }}';
                    var flutter_callback = "{{ url('/flutterwave/transaction/callback') }}";
                    const modal = FlutterwaveCheckout({
                        public_key: API_publicKey,
                        tx_ref: "titanic-48981487343MDI0NzMx",
                        amount: res.total_price,
                        currency: res.currency,
                        payment_options: "card, banktransfer, ussd",
                        callback: function(response) {
                            window.location.href = flutter_callback + '/' + response
                                .transaction_id +
                                '/' + coupon_id +
                                '/' + res.plan_id
                            modal.close();
                        },
                        onclose: function(incomplete) {
                            window.location.href = '/plans';
                            modal.close();
                        },
                        meta: {
                            consumer_id: res.plan_id,
                            consumer_mac: "92a3-912ba-1192a",
                        },
                        customer: {
                            email: res.email,
                            phone_number: '7421052101',
                            name: 'dqw',
                        },
                        customizations: {
                            title: res.plan_name,
                            description: "Payment for an awesome cruise",
                            logo: "https://www.logolynx.com/images/logolynx/22/2239ca38f5505fbfce7e55bbc0604386.jpeg",
                        },
                    });
                }).submit();
            });
        @endif
        @if (isset($admin_payment_setting['razorpaysetting']) && $admin_payment_setting['razorpaysetting'] == 'on')
            $(document).on("click", "#pay_with_razorpay", function() {
                $('#razorpay-payment-form').ajaxForm(function(res) {

                    var razorPay_callback = '{{ url('/razorpay/transaction/callback') }}';
                    var totalAmount = res.total_price * 100;
                    var coupon_id = res.coupon;
                    var options = {
                        "key": "{{ $admin_payment_setting['razorpay_key'] }}", // your Razorpay Key Id
                        "amount": totalAmount,
                        "name": res.plan_name,
                        "currency": res.currency,
                        "description": "",
                        "handler": function(response) {
                            window.location.href = razorPay_callback + '/' + response
                                .razorpay_payment_id +
                                '/' + coupon_id +
                                '/' + res.plan_id
                        },
                        "theme": {
                            "color": "#528FF0"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                }).submit();
            });
        @endif
        @if (isset($admin_payment_setting['paytmsetting']) && $admin_payment_setting['paytmsetting'] == 'on')
            $(document).on("click", "#pay_with_paytm", function() {
                $('#paytm-payment-form').ajaxForm(function(res) {
                    if (res.errors) {
                        notifier.show('Error!', res.errors, 'danger',
                            '{{ asset('assets/images/notification/high_priority-48.png') }}',
                            4000);
                    }
                    window.Paytm.CheckoutJS.init({
                        "root": "",
                        "flow": "DEFAULT",
                        "data": {
                            "orderId": res.orderId,
                            "token": res.txnToken,
                            "tokenType": "TXN_TOKEN",
                            "amount": res.amount,
                        },
                        handler: {
                            transactionStatus: function(data) {},
                            notifyMerchant: function notifyMerchant(eventName, data) {
                                if (eventName == "APP_CLOSED") {
                                    $('.paytm-pg-loader').hide();
                                    $('.paytm-overlay').hide();
                                    //location.reload();
                                }
                            }
                        }
                    }).then(function() {
                        window.Paytm.CheckoutJS.invoke();
                    });
                });
            }).submit();
        @endif
        @if (isset($admin_payment_setting['stripesetting']) && $admin_payment_setting['stripesetting'] == 'on')
            $(document).on("click", "#pay_with_stripe", function() {
                $('#stripe-payment-form').ajaxForm(function(res) {
                    if (res.error) {
                        notifier.show('Error!', res.error, 'danger',
                            '{{ asset('assets/images/notification/high_priority-48.png') }}', 4000);
                    }
                    const stripe = Stripe("{{ $admin_payment_setting['stripe_key'] }}");
                    createCheckoutSession(res.plan_id, res.order_id, res.coupon, res.total_price).then(
                        function(
                            data) {

                            if (data.sessionId) {
                                stripe.redirectToCheckout({
                                    sessionId: data.sessionId,
                                }).then(handleResult);
                            } else {
                                handleResult(data);
                            }
                        });
                });
            }).submit();
            const createCheckoutSession = function(plan_id, order_id, coupon, amount) {
                return fetch("{{ route('stripe.session') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        createCheckoutSession: 1,
                        plan_id: plan_id,
                        order_id: order_id,
                        coupon: coupon,
                        amount: amount,
                    }),
                }).then(function(result) {
                    return result.json();
                });
            };
            const handleResult = function(result) {
                if (result.error) {
                    showMessage(result.error.message);
                }
                setLoading(false);
            };
        @endif
    </script>
@endpush
