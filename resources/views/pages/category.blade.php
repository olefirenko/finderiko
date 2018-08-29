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

        <h1 class="mt-5 mb-5">10 {{ str_plural($category->title) }} <time datetime='{{ date("d-m-Y") }}'>{{ date('Y') }}</time></h1>
        {{--  <a href="/delete/{{ $category->id }}">Delete</a>  --}}
        <div class="alert alert-dismissible alert-warning">
            <p>After analyzing {{ $category->total_results or '' }} products, scanning {{ ($category->total_results * 5) or '' }} reviews, spending more than 36 hours of research and speaking with our test users, we think the <a href="{{ $products->first()->amazon_link }}" target="_blank" rel="nofollow">{{ $products->first()->short_name }}</a> is the one of the <strong>Best {{ $category->name }} on the market</strong>.</p>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Price</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>
                            <a href="{{ $product->amazon_link }}" target="_blank" rel="nofollow"><img src="{{ $product->image }}" width="200" style="max-height: 250px"/></a>
                        </td>
                        <td>
                            {{ $product->name }}
                            @if ($key == 0)
                            <br><b>(Editor’s Choice)</b>
                            @endif

                            @if ($best_for_money->id == $product->id)
                            <br><b>(Best value for the money)</b>
                            @endif
                        </td>
                        <td class="text-info font-weight-bold">{{ $product->brand }}</td>
                        <td class="font-weight-bold fs-20">{!! $product->getPriceRange($step) !!}</td>
                        <td><a href="{{ $product->amazon_link }}" class="btn btn-primary" target="_blank" rel="nofollow">Check price</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($category->buyers_quide)
        <hr>
        <div class="mt-5 mb-5">
            {!! $category->buyers_quide !!}
        </div>
        @endif

        {{--  <div class="media">
            <img class="mr-2" src="https://geeklah.com/images/gareth.jpg" alt="Gareth Otwell" style="border-radius: 50%;border: 2px solid #aaa;max-width: 150px;">

            <div class="media-body">
              <h5 class="mt-0">About Gareth Otwell</h5>
              Research analysis by <span itemprop="author"><a href="/user/kamalpatel/">Kamal Patel</a></span> and verified by the <a href="/about/">Examine.com Research Team</a>. Last updated on <span itemprop="dateModified">{{ $category->updated_at }}.</span>
            </div>
        </div>  --}}
        <hr>
        <h2 class="mt-5 mb-3">Similiar Categories</h2>
        <div class="card-group">
            @foreach ($related_categories as $subcategory)
            <div class="card">
                <a href="{{ route('category', $subcategory->slug) }}" class="card-img-top">
                    <img class="card-img-top" src="{{ $subcategory->image }}" alt="{{ $subcategory->name }}" style="max-height: 250px">
                </a>
                <div class="card-body">
                    <h5 class="card-title"><a href="{{ route('category', $subcategory->slug) }}">{{ str_plural($subcategory->name) }}</a></h5>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
</section>
@endsection
