@extends('layout')

@section('content')
<div class="position-relative overflow-hidden p-3 p-md-5 mt-2 text-center" style="background-color: tan;background-blend-mode: multiply;background-image: url('images/office-1209640_1920.jpg');background-size: cover;background-position-y: -150px;">
  <div class="col-md-5 p-lg-5 mx-auto my-5">
    <h1 class="display-4 font-weight-normal text-white">Find The Best Products</h1>
    <p class="lead font-weight-normal text-white">Thousands reviews have been analyzed and compared to help you choose the best products to buy</p>
    <a class="btn btn-primary" href="#departments">Find the best</a>
  </div>
  <div class="product-device box-shadow d-none d-md-block"></div>
  <div class="product-device product-device-2 box-shadow d-none d-md-block"></div>
</div>
<div class="svg_patern">
<section class="section-spacer pb-0 section--clients pt-0">
  <div class="container">
    <header class="section-header text-center w-100">
      <h2 class="section-title">Popular Categories</h2>
    </header>
    <div class="row">
      <div class="col-12 mx-auto">
        <div class="card-columns">
          @foreach ($popular_categories as $category)
          <div class="card">
            <a href="{{ route('category', $category->slug) }}">
              <img class="card-img-top" src="{{ $category->image }}" alt="{{ $category->name }}" style="max-height: 300px">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="{{ route('category', $category->slug) }}">{{ $category->name }}</a></h5>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-spacer pb-0 section--clients" id="departments">
  <div class="container">
    <header class="section-header text-center w-100">
      <h2 class="section-title">Shop By Department</h2>
    </header>
    <div class="row">
      <div class="col-12 mx-auto">
        <div class="card-columns">
          @foreach ($categories as $category)
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">
                <a href="{{ route('category', $category->slug) }}">{{ $category->name }}</a>
              </h5>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
</div>
{{-- <section id="overview" class="section-spacer">
  <div class="container">
    <div class="section-screens__inner">
      <div class="row">
        <div class="col-md-8 mx-auto">
          <header class="section-header pt-100 text-center">
            <h2 class="section-title">Single platform, Deep insights</h2>
            <p class="lead">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus aut reiciendis
              praesentium.
            </p>
          </header>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-4 col-12">
        <div class="feature-card">
          <div class="u-icon u-icon__circle u-icon__lg bg-dimped__primary">
            <i class="icon ion-ios-keypad"></i>
          </div>
          <div class="feature-card__body">
            <h4 class="feature-title">Relentless Features</h4>
            <p>Aesthetic jean shorts glossier lo-fi DIY thundercats fashion axe echo park copper mug
            </p>
            <a href="#" class="btn btn-link btn-link--secondary">Learn more
              <i class="icon ion-ios-arrow-forward"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="feature-card">
          <div class="u-icon u-icon__circle u-icon__lg bg-dimped__purple">
            <i class="icon ion-ios-apps"></i>
          </div>
          <div class="feature-card__body">
            <h4 class="feature-title">Easy Integration</h4>
            <p>Aesthetic jean shorts glossier lo-fi DIY thundercats fashion axe echo park copper mug</p>
            
            <a href="#" class="btn btn-link btn-link--secondary">Learn more
              <i class="icon ion-ios-arrow-forward"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="feature-card m-0">
          <div class="u-icon u-icon__circle u-icon__lg bg-dimped__cyan">
            <i class="icon ion-ios-bug"></i>
          </div>
          <div class="feature-card__body">
            <h4 class="feature-title">AI bugs report</h4>
            <p>Aesthetic jean shorts glossier lo-fi DIY thundercats fashion axe echo park copper mug</p>
            
            <a href="#" class="btn btn-link btn-link--secondary">Learn more
              <i class="icon ion-ios-arrow-forward"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    
    
  </div>
</section>

<section id="hiw" class="section-spacer bg-very__gray">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <div class="feature-list-image">
          <img src="images/place-holder-1.png" class="img-fluid" alt="Image">
        </div>
      </div>
      <div class="col-sm-5 ml-auto">
        <div class="feature-list-wrapper">
          <div class="content-header">
            <h2 class="content-title">Intuitive Interface</h2>
            <p>Dignissimos maiores, laudantium consequatur nam, officiis repellendus voluptate laboriosam. Efficiis
              repellendus
              voluptate
            </p>
          </div>
          <ul class="list list-unstyled list-circle">
            <li>
              <span>Fully responsive</span>
            </li>
            <li>
              <span>24/7 Supports</span>
            </li>
            <li>
              <span>Single API</span>
            </li>
            <li>
              <span>Weekly Reports</span>
            </li>
          </ul>
          <a href="#" class="btn btn-link btn-link--secondary">
            Explore our products
            <i class="icon ion-ios-arrow-round-forward"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="faqs" class="section-spacer section-faq">
  <div class="container">
    <header class="section-header text-center">
      <h2 class="section-title">Frequently Asked Questions</h2>
    </header>
    <div class="row">
      <div class="col-sm-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Life time support?</h5>
            <p>Hiss and stare at nothing then run suddenly away. Lick human with sandpaper tongue paw at your fat belly
              be
              a nyan cat, feel great about it, be annoying 24/7 poop rainbows in litter box all day.</p>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Is there limit users for each plans?</h5>
              <p>Hiss and stare at nothing then run suddenly away. Lick human with sandpaper tongue paw at your fat belly
                be
                a nyan cat, feel great about it, be annoying 24/7 poop rainbows in litter box all day.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Can I cancel my monthly subscriptions?</h5>
                <p>Hiss and stare at nothing then run suddenly away. Lick human with sandpaper tongue paw at your fat belly
                  be
                  a nyan cat, feel great about it, be annoying 24/7 poop rainbows in litter box all day.</p>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Do I have to pay for all users?</h5>
                  <p>Hiss and stare at nothing then run suddenly away. Lick human with sandpaper tongue paw at your fat belly
                    be
                    a nyan cat, feel great about it, be annoying 24/7 poop rainbows in litter box all day.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-40 text-center">
              <a href="#" class="btn btn-primary btn-lg">Talk to our team</a>
            </div>
          </div>
        </section>
        
        <section id="try" class="section-spacer text-left pb-0 pt-50">
          <div class="container">
            <div class="callout">
              <div class="callout-content">
                <div class="Callout--Header">
                  <h2>Start your free trial</h2>
                  <p class="lead">Lick human with sandpaper tongue paw at your fat belly be a nyan cat, feel great about it, be
                    annoying 24/7 poop</p>
                  </div>
                </div>
                <div class="callout-footer ml-lg-auto">
                  <form action="">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Enter your email">
                      <button type="submit" class="btn btn-primary">Start your free trial</button>
                    </div>
                  </form>
                  <p class="d-block text-sm">14 days free - no credit card required</p>
                </div>
              </div>
            </div>
          </section> --}}
          @endsection
          