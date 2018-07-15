@extends('layout')

@section('content')
<section class="card card-fluid svg_patern">
    <div class="container bg-white">
        <div class="pt-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Finderiko</a></li>
                    <li class="breadcrumb-item active">{{ str_limit($article->name, 50) }}</li>
                </ol>
            </nav>
    
            <h1 class="mt-5 mb-5">{{ $article->name }}</h1>
    
            {!! $article->content !!}
        </div>
    </div>
</section>
@endsection
