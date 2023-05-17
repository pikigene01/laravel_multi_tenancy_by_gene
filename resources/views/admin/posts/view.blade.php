@section('title', 'Blog')
<!DOCTYPE html>
<html lang="en">

@include('layouts.front_header')
<header id="home" class="bg-primary blog_detail">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-sm-5">
                <h1 class="text-white mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.2s">
                    {{ __('Blog') }}
                </h1>
            </div>

        </div>
    </div>
</header>
<div class="main_content_wrapper">
    <div class="container">
        <div class="col-11 mt-2">
            <div class="form-group">
                {{ Form::label('category_id', __('Category'), ['class' => 'p-2']) }}
                {!! Form::select('category_id', $category, null, ['class' => 'form-select', 'data-trigger']) !!}
            </div>
        </div>
        <div class="row all_posts">
            @foreach ($posts as $post)
                <div class="col-lg-3 col-md-6">
                    <div class="card mb-3">
                        @if ($post->photo)
                            <img src="{{ Storage::exists($post->photo) ? Storage::url(tenant('id') . '/' . $post->photo) : Storage::url('test_image/350x250.png') }}"
                                class="img-fluid card-img-top card-img-custom">
                        @else
                            <img src="{{ Storage::url('test_image/350x250.png') }}"
                                class="img-fluid card-img-top card-img-custom">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">
                                {{ substr($post->short_description, 0, 75) . (strlen($post->short_description) > 75 ? '...' : '') }}
                            </p>
                            <a href="{{ route('post.details', $post->slug) }}">{{ __('Read More') }}<i
                                    class="fas fa-chevron-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@include('layouts.front_footer')
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var genericExamples = document.querySelectorAll('[data-trigger]');
        for (i = 0; i < genericExamples.length; ++i) {
            var element = genericExamples[i];
            new Choices(element, {
                placeholderValue: 'This is a placeholder set in the config',
                searchPlaceholderValue: 'This is a search placeholder',
            });
        }
    });
</script>
<script>
    $(document).on("change", "#category_id", function() {
        var cate_id = $(this).val();
        $.ajax({
            url: '{{ route('get.category.post') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                category: cate_id,
            },
            success: function(data) {
                $(".all_posts").html('');
                $.each(data, function(index, val) {
                    var url = '{{ url('blog-detail') }}/' + val.slug;
                    // alert(123);
                    $(".all_posts").append(
                        '<div class="col-lg-3 col-md-6"><div class="card mb-3"><img class="img-fluid card-img-top card-img-custom" src="' +
                        val.photo +
                        '" alt="Card image cap"><div class="card-body">    <h5 class="card-title">' +
                        val.title + '</h5>    <p class="card-text">' + val
                        .short_description + '</p><a href="' + url +
                        '">Read More <i class="fas fa-chevron-right"></i></a></div></div></div>'
                        // '<div class="col-12 col-sm-6 col-md-6 col-lg-3"><article class="article article-style-b"><div class="article-header"><div class="article-image" style="background-image:url(' +
                        // img_path +
                        // ')" ></div></div><div class="article-details"><div class="article-title"><h2>' +
                        // val.title +
                        // '</h2></div><p>' + val
                        // .short_description + '</p><div class="article-cta"><a href="' +
                        // url +
                        // '">Read More <i class="fas fa-chevron-right"></i></a></div></div></article></div>'
                    );
                });
            }
        })
    });
</script>

</body>

</html>
