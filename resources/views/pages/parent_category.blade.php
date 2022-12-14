@extends('layout')

@section('content')
<section class="card card-fluid svg_patern">
<div class="container bg-white">
    <div class="pt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Finderiko</a></li>
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol>
        </nav>

        <h1 class="mt-5 mb-5">{{ $category->title }}</h1>

        <div class="row">
        @foreach ($category->subcategories()->where("id", ">", 18833)->orderBy("name")->get() as $subcategory)
        <div class="card col-md-4 flex-row align-items-center p-3">
            <a href="{{ route('category', $subcategory->slug) }}">
                <img src="{{ $subcategory->image }}" alt="{{ $subcategory->name }}" style="max-width:200px;max-height: 250px" loading="lazy" />
            </a>
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('category', $subcategory->slug) }}">{{ Illuminate\Support\Str::plural($subcategory->name) }}</a></h5>
                <a onclick="fetch('/delete/{{ $subcategory->id }}'); this.closest('.col-md-4').remove()">Delete</a>
            </div>
        </div>
        @endforeach
        </div>
    </div>
</div>
</section>
@endsection
