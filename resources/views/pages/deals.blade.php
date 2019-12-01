@extends('layout')

@section('content')
<section class="card card-fluid svg_patern">
    <div class="container bg-white">
        <div class="pt-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Finderiko</a></li>
                    <li class="breadcrumb-item active" aria-current="page">💲Today's Deals <time
                            datetime='{{ date("d-m-Y") }}'>{{ date('Y') }}</time></li>
                </ol>
            </nav>


            @foreach ($categories as $category)
            @if ($category->deals->count())
            <h2 class="mt-5">{{ $category->name }}</h2>
            <hr>
            @foreach ($category->deals->chunk(5) as $deals)
            <div class="card-group">
                @foreach ($deals as $deal)
                <div class="card p-3 border-0 text-center position-relative discount-block">
                    @if ($deal->percentage_saved)
                    <div class="discount position-absolute u-icon u-icon__circle u-icon__lg bg-dimped__primary-orange">
                        -{{ $deal->percentage_saved }}%</div>
                    @endif
                    <a target="_blank" rel="nofollow" href="{{ $deal->amazon_link }}" class="card-img-top">
                        <img src="{{ $deal->image }}" alt="{{ $deal->name }}" style="max-height: 250px;max-width:200px"
                            loading="lazy" />
                    </a>
                    <div class="card-body">
                        <h6 class="card-title"><a target="_blank" rel="nofollow"
                                href="{{ $deal->amazon_link }}">{{ $deal->short_name }}</a></h6>
                        <div style="min-height: 40px">
                            <a href="{{ $deal->amazon_link }}" class="btn btn-primary d-none" target="_blank"
                                rel="nofollow">Check price</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
            @endif
            @endforeach
        </div>
    </div>
</section>
@endsection