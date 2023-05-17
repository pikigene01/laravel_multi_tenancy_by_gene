@section('title', __('Privacy Policy'))
<!DOCTYPE html>
<html lang="en">

    @include('layouts.front_header')
    <header id="home" class="bg-primary blog_detail">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-sm-5">
                    <h1 class="text-white mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.2s">
                        {{ __('Privacy Policy') }}
                    </h1>
                </div>
    
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <article class="article article-detail article-noshadow">
                        <div class="p-0">
                            <div>
                                {!! Utility::getsettings('privacy') !!}
                            </div>

                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.front_footer')



</body>

</html>
