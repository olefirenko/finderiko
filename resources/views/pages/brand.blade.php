@extends('layout')

@section('content')
<section class="card card-fluid svg_patern">
    <div class="container bg-white">
        <div class="pt-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Finderiko</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('brands') }}">Brands</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $brand->name }}</li>
                </ol>
            </nav>

            <section>
                <div class="container">
                    <div class="row flex-column-reverse flex-sm-row align-items-center">
                        <div class="col-sm-6 mr-auto">
                            <div class="feature-list-wrapper">
                                <div class="content-header">
                                    <h1 class="content-title mt-5 mb-5">Best {{ $brand->name }} Products <time
                                        datetime='{{ date("d-m-Y") }}'>{{ date('Y') }}</time></h1>
                                    <p>Top categories:</p>
                                </div>
                                <ul class="list list-circle">
                                    @foreach ($categories as $category)
                                        <li>
                                            <span>{{ $category->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="feature-list-image">
                                <img src="https://aooljjncam.cloudimg.io/width/300/x/{{ $brand->logo }}" class="img-fluid"
                                    alt="{{ $brand->name }}" title="{{ $brand->name }}" loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            

            <div class="row">
                @foreach ($products as $key => $product)
                <div class="card col-md-3 text-center">
                    <a href="{{ route('category', $product->category->slug) }}#product{{ $product->position - 1 }}">
                        <img src="{{ $product->image }}" style="max-height: 250px;max-width: 250px;"
                            alt="{{ $product->short_name }}" loading="lazy" />
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><a href="{{ route('category', $product->category->slug) }}#product{{ $product->position - 1 }}">{{ trim(str_replace($brand->name, '', $product->short_name)) }}</a></h5>
                        <span class="badge badge-primary">#{{ $product->position }} in {{ $product->category->short_title }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- <div class="table-responsive">
            <a name="tentable"></a>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
            <td>
                <img src="{{ $product->image }}" width="200" style="max-height: 250px" alt="{{ $product->short_name }}"
                    loading="lazy" />
            </td>
            <td>
                {!! $product->name !!}
            </td>
            <td class="text-info font-weight-bold"><a
                    href="{{ route('category', $product->category->slug) }}">{{ $product->category->short_title }}</a>
            </td>
            <td><a href="{{ $product->amazon_link }}" class="btn btn-primary button{{ $key + 1 }}" target="_blank"
                    rel="nofollow">Check price</a></td>
            </tr>
            @endforeach
            </tbody>
            </table>
        </div> --}}
    </div>
    </div>
</section>
@endsection