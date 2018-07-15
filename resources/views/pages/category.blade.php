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

        <h1 class="mt-5 mb-5">{{ $category->title }} 2018</h1>

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
                    <td><a href="{{ $product->amazon_link }}" target="_blank" rel="nofollow"><img src="{{ $product->image }}" width="200"/></a></td>
                    <td>{{ $product->name }}</td>
                    <td class="text-info font-weight-bold">{{ $product->brand }}</td>
                    <td class="font-weight-bold fs-20">{{ $product->getPriceRange($step) }}</td>
                    <td><a href="{{ $product->amazon_link }}" class="btn btn-primary" target="_blank" rel="nofollow">Check price</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</section>
@endsection
