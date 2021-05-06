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
            {{-- <a href="/delete/{{ $category->id }}">Delete</a>
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
        </div> -->--}}

            <div class="row">
                <div class="col-md-9">
                    @foreach ($products as $key => $product)
                    <div class="m-1 clearfix">
                        <h2 id="product{{ $key }}">
                            {{ $product->short_name }}
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
                        @if ($product->brand_id && $product->brand->shouldBeShown() )
                        <p>
                            @if ($product->product_infos->contains("label", "Model"))
                            <strong>{{ data_get($product->product_infos->firstWhere("label", "Model"), 'value') }}</strong>
                            @elseif ($product->product_infos->contains("label", "PartNumber"))
                            <strong>{{ data_get($product->product_infos->firstWhere("label", "PartNumber"), 'value') }}</strong>
                            @endif
                            by <strong><a href="{{ route('brand', $product->brand->slug) }}">{{ $product->brand->name }}</a></strong>
                        </p>
                        @endif
                        <ul class="list-infos">
                            @if ($product->product_infos->contains("label", "ReleaseDate"))
                            <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                </svg>
                                <span class="feature">Release date:</span> {{ Carbon\Carbon::parse($product->product_infos->firstWhere("label", "ReleaseDate")->value)->format('m/d/Y') }}
                            </li>
                            @endif
                            @if ($product->product_infos->contains("label", "Color") && $product->product_infos->firstWhere("label", "Color")->value != "Null")
                            <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-palette" viewBox="0 0 16 16">
                                    <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                    <path d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8zm-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7z" />
                                </svg>
                                <span class="feature">Color:</span> <span style='color: {{ explode(' ', $product->product_infos->firstWhere("label", "Color")->value)[0] }}'>{{ $product->product_infos->firstWhere("label", "Color")->value }}</span>
                            </li>
                            @endif
                            @if ($product->product_infos->contains("label", "Weight"))
                            <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1h-2z" />
                                    <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                </svg>
                                <span class="feature">Weight:</span> {{ round($product->product_infos->firstWhere("label", "Weight")->value, 2) }} pounds
                            </li>
                            @endif
                            @if ($product->product_infos->contains("label", "Height") && $product->product_infos->contains("label", "Length") && $product->product_infos->contains("label", "Width"))
                            <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box" viewBox="0 0 16 16">
                                    <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z" />
                                </svg>
                                <span class="feature">Dimensions:</span> 
                                {{ round($product->product_infos->firstWhere("label", "Length")->value, 1) }} x
                                {{ round($product->product_infos->firstWhere("label", "Width")->value, 1) }} x 
                                {{ round($product->product_infos->firstWhere("label", "Height")->value, 1) }}
                                 inches
                            </li>
                            @endif
                            @if ($product->product_infos->contains("label", "Warranty") && $product->product_infos->firstWhere("label", "Warranty")->value != "default_no_selection_value" && strlen($product->product_infos->firstWhere("label", "Warranty")->value) > 5)
                            <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-award" viewBox="0 0 16 16">
                                    <path d="M9.669.864L8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193l.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z" />
                                    <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z" />
                                </svg>
                                <span class="feature">Warranty:</span> {{ $product->product_infos->firstWhere("label", "Warranty")->value }}
                            </li>
                            @endif
                        </ul>
                        {{--
                        <!-- <p>Price range 💵 : <strong>{!! $product->getPriceRange($step) !!}</strong></p> -->--}}
                        <a href="{{ $product->link }}" class="pr_link m-5 text_image{{ $key + 1 }}" target="_blank" rel="nofollow"><img src="{{ $product->image }}" class="rounded" alt="{{ $product->short_name }}" loading="lazy" /></a>
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

            {{-- <div class="media">
                <img class="mr-2" src="https://geeklah.com/images/gareth.jpg" alt="Gareth Otwell" style="border-radius: 50%;border: 2px solid #aaa;max-width: 150px;">

                <div class="media-body">
                    <h5 class="mt-0">About Gareth Otwell</h5>
                    Research analysis by <span itemprop="author"><a href="/user/kamalpatel/">Kamal Patel</a></span> and verified by the <a href="/about/">Examine.com Research Team</a>. Last updated on <span itemprop="dateModified">{{ $category->updated_at }}.</span>
                </div>
            </div> --}}
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
        window.onscroll = function () {
            if (addsWrapper.offsetTop < window.scrollY && window.scrollY < addsWrapper.offsetHeight + addsWrapper.offsetTop - 100) {
                adsElement.classList.add('fixed');
            } else {
                adsElement.classList.remove('fixed');
            }
        }
    }
</script>
@endsection