@props([
    'title' => '',
    'url' => ''
])

<section class="py-5 pages-banner" style="background-image: url({{ $url }});">
    <div class="container">
        <div class="row">
            <div class="py-5 col-12">
                <h1>{{ $title }}</h1>
            </div>
        </div>
    </div>
</section>

