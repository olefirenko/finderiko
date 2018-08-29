@extends('layout')

@section('content')
<section class="card card-fluid svg_patern">
<div class="container bg-white">
    <div class="pt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Finderiko</a></li>
                <li class="breadcrumb-item active">Search Results</li>
            </ol>
        </nav>

        <h1 class="mt-5 mb-5">Search Results [{{ $categories->count() }}]</h1>

        <div class="row">
        @foreach ($categories as $category)
        <div class="card col-md-3">
            <a href="{{ route('category', $category->slug) }}">
                <img class="card-img-top" src="{{ $category->image }}" alt="{{ $category->name }}" style="max-height: 300px">
            </a>
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('category', $category->slug) }}">{{ str_plural($category->name) }}</a></h5>
            </div>
        </div>
        @endforeach
        </div>
    </div>
</div>
</section>
@endsection
