@extends('layout')

@section('content')
<section class="card card-fluid svg_patern">
<div class="container bg-white">
    <div class="pt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Finderiko</a></li>
                <li class="breadcrumb-item"><a href="{{ route('category', $category->parent->slug) }}">{{ $category->parent->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <h1 class="mt-5 mb-3">10 {{ Illuminate\Support\Str::plural($category->title) }} {{ date('Y') }}</h1>
        <p class="small author">
            <img loading="lazy" src="/images/gareth.jpg" alt="Gareth Otwell" width="35" class="rounded-circle mr-2">
            Reviewed by Gareth Otwell
            | Last Updated: <time class="entry-modified-time" itemprop="dateModified" datetime="{{ $category->updated_at->toIso8601String() }}">{{ $category->updated_at->toFormattedDateString() }}</time>
        </p>
        {{--  <a href="/delete/{{ $category->id }}">Delete</a>  --}}
        <!-- <div class="alert alert-warning">
            <p>After analyzing {{ $category->total_results or '' }} products, scanning @if ($category->total_results){{ $category->total_results * 5 }}@endif reviews, spending more than 36 hours of research and speaking with our test users, we think the <a href="{{ $products->first()->link }}" class="toplink" target="_blank" rel="nofollow">{{ $products->first()->short_name }}</a> is the one of the <strong>Best {{ $category->name }} on the market</strong>.</p>
        </div>
        <div class="table-responsive">
            <a name="tentable"></a>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="hid">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col" class="mh">Brand</th>
                        <th scope="col" class="mh">Price</th>
                        <th scope="col" class="mh"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                    <tr>
                        <td scope="row" class="hid">{{ $key + 1 }}</td>
                        <td class="text-center">
                            @if ($key == 0)
                            <div class="t-plash">⭐Best Choice</div>
                            @endif
                            
                            @if ($best_for_money && $best_for_money->id == $product->id)
                            <div class="t-plash">💲Best value for the money</div>
                            @endif

                            @if ($premium && $premium->id == $product->id)
                            <div class="t-plash">👑Premium Pick</div>
                            @endif

                            @if (in_array($product->id, array_keys($under_products)))
                            <div class="t-plash">Best under ${{ $under_products[$product->id] }}</div>
                            @endif
                            <a href="{{ $product->link }}" class="image{{ $key + 1 }}" target="_blank" rel="nofollow"><img src="{{ $product->image }}" style="max-height: 250px;max-width: 200px" alt="{{ $product->short_name }}" loading="lazy" /></a>
                        </td>
                        <td>
                            {!! str_replace($product->short_name, '<a href="#product'.$key.'">'.$product->short_name.'</a><span class="mh">', $product->name) !!}
                            </span>
                            <a href="{{ $product->link }}" class="btn btn-primary button{{ $key + 1 }} d-block d-sm-none mt-3" target="_blank" rel="nofollow">Check price</a>
                        </td>
                        <td class="text-info font-weight-bold mh">
                            @if ($product->brand)
                                @if ($product->brand->count_products >= 10)
                                    <a href="{{ route('brand', $product->brand->slug) }}">{{ $product->brand->name }}</a>
                                @else
                                    {{ $product->brand->name }}
                                @endif
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="font-weight-bold fs-20 mh">
                            {!! $product->getPriceRange($step) !!}
                        </td>
                        <td class="mh"><a href="{{ $product->link }}" class="btn btn-primary button{{ $key + 1 }}" target="_blank" rel="nofollow">Check price</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div> -->

        <div class="row">
            <div class="col-md-9">
                @foreach ($products as $key => $product)
                <div class="m-1 clearfix">
                <h2>
                    <a name="product{{ $key }}" href="{{ $product->link }}" class="h2{{ $key + 1 }}" target="_blank" rel="nofollow">{{ $product->short_name }}</a>
                    @if ($key == 0)
                    &ndash; ⭐ Best Overall {{ Illuminate\Support\Str::title(Illuminate\Support\Str::singular($category->name)) }}
                    @endif
                    
                    @if ($best_for_money && $best_for_money->id == $product->id)
                    &ndash; 💲 Best Budget {{ Illuminate\Support\Str::title(Illuminate\Support\Str::singular($category->name)) }}
                    @endif

                    @if ($premium && $premium->id == $product->id)
                    &ndash; 👑 Premium Pick
                    @endif

                    @if (in_array($product->id, array_keys($under_products)))
                    &ndash; Best under ${{ $under_products[$product->id] }}
                    @endif
                </h2>
                @if ($product->brand->count_products >= 10)
                <p>By <strong><a href="{{ route('brand', $product->brand->slug) }}">{{ $product->brand->name }}</a></strong></p>
                @endif
                <!-- <p>Price range 💵 : <strong>{!! $product->getPriceRange($step) !!}</strong></p> -->
                <a href="{{ $product->link }}" class="pr_link m-5 text_image{{ $key + 1 }}" target="_blank" rel="nofollow"><img src="{{ $product->image }}" class="rounded" style="max-width: 250px" alt="{{ $product->short_name }}" loading="lazy"/></a>
               {!! str_replace("<ul>", "<ul class='list-circle'>", $product->description) !!}
                </div>
                @endforeach

                @if ($category->buyers_quide)
                <hr>
                <div class="mt-5 mb-5">
                    {!! $category->buyers_quide !!}
                </div>
                @endif
            </div>
            <div class="col-md-3 position-relative" id="sidebar">
                <div class="products_navigation">
                    <h4>Navigate out top 10 {{ Illuminate\Support\Str::plural($category->title) }}</h4>
                    <ul>
                        <li><a href="#product0">⭐Best Overall {{ Illuminate\Support\Str::title(Illuminate\Support\Str::singular($category->name)) }}</a></li>
                        @foreach ($under_products as $key => $item)
                        <li><a href="#product{{ $products->firstWhere('id', $key)->position - 1 }}">Best Under ${{ $item }}</a></li>                        
                        @endforeach
                        <li><a href="#product{{ $best_for_money->position - 1  }}">💲Best Budget {{ Illuminate\Support\Str::title(Illuminate\Support\Str::singular($category->name)) }}</a></li>
                        <li><a href="#product{{ $premium->position - 1 }}">👑Premium Pick</a></li>
                        <li><a href="#similiar">Similiar Products</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{--  <div class="media">
            <img class="mr-2" src="https://geeklah.com/images/gareth.jpg" alt="Gareth Otwell" style="border-radius: 50%;border: 2px solid #aaa;max-width: 150px;">

            <div class="media-body">
              <h5 class="mt-0">About Gareth Otwell</h5>
              Research analysis by <span itemprop="author"><a href="/user/kamalpatel/">Kamal Patel</a></span> and verified by the <a href="/about/">Examine.com Research Team</a>. Last updated on <span itemprop="dateModified">{{ $category->updated_at }}.</span>
            </div>
        </div>  --}}
        <hr id="similiarHR">
        <h2 id="similiar" class="mt-5 mb-3">Similiar Categories</h2>
        <div class="card-group">
            @foreach ($related_categories as $subcategory)
            <div class="card text-center">
                <a href="{{ route('category', $subcategory->slug) }}" class="card-img-top">
                    <img src="{{ $subcategory->image }}" alt="{{ $subcategory->name }}" style="max-height: 250px;max-width:200px" loading="lazy" />
                </a>
                <div class="card-body">
                    <h5 class="card-title"><a href="{{ route('category', $subcategory->slug) }}">{{ Illuminate\Support\Str::plural($subcategory->name) }}</a></h5>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
</section>
<script>
    if (window.screen.width > 1000) {
        var addsWrapper = document.getElementById('sidebar');
        var adsElement = document.querySelector('.products_navigation');
        window.onscroll = function() {
            if(addsWrapper.offsetTop < window.scrollY && window.scrollY < addsWrapper.offsetHeight + addsWrapper.offsetTop - 100) {
               adsElement.classList.add('fixed');
            } else {
               adsElement.classList.remove('fixed');
            }
        }
    }
</script>
@endsection
