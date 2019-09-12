@extends('layout')

@section('content')
<section class="card card-fluid svg_patern">
<div class="container bg-white">
    <div class="pt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Finderiko</a></li>
                <li class="breadcrumb-item active" aria-current="page">Brands</li>
            </ol>
        </nav>

        <h1 class="mt-5 mb-5">💯 Top 100 Best Brands <time datetime='{{ date("d-m-Y") }}'>{{ date('Y') }}</time></h1>
        {{--  <a href="/delete/{{ $category->id }}">Delete</a>  --}}
        <div class="alert alert-dismissible alert-warning">
            <p>After analyzing </p>
        </div>
        <div class="table-responsive">
            <a name="tentable"></a>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Total Products</th>
                        <th scope="col">Department</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $key => $brand)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>
                            <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" width="300"/>
                        </td>
                        <td>
                            {{ $brand->name }}
                        </td>
                        <td class="text-info font-weight-bold">
                            {{ $brand->count_products }}                            
                        </td>
                        <td class="font-weight-bold fs-20">
                            @if ($brand->parent_categories->first())
                            {{ $brand->parent_categories->first()->name }}          
                            @endif                  
                        </td>
                        <td><a href="{{ route('brand', $brand->slug) }}" class="btn btn-primary"">Details</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>
@endsection
