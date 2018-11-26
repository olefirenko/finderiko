<footer class="section-spacer footer-section">
<div class="container">
    <div class="row flex-column-reverse flex-sm-row flex-lg-row">
    <div class="col-md-4 col-12">
        <div class="footer-widget first-of-footer-widget">
        <a href="/"><img src="/images/logo.jpg" class="logo-sm mb-10" alt="Finderiko"></a>
        <p>Copyright &copy; 2018 Finderiko. All Rights Reserved.</p>
        <p>
            <a href="//www.dmca.com/Protection/Status.aspx?ID=81ba0dcb-4a0f-4a26-9e36-bb83d8b35b50" title="DMCA.com Protection Status" class="dmca-badge"> <img src="//images.dmca.com/Badges/_dmca_premi_badge_2.png?ID=81ba0dcb-4a0f-4a26-9e36-bb83d8b35b50" alt="DMCA.com Protection Status"></a> <script src="//images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
        </p>
        
        <h6 class="text-small">AFFILIATE DISCLOSURE</h6>
        <small>Some posts may contain affiliate links. Finderiko.com is a participant in the Amazon Services LLC Associates Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees by advertising and linking to Amazon.com.</small>
        </div>
    </div>
    <div class="col-md-8 col-sm-10">
        <div class="row">
        <div class="col-md-4 col-4">
            <div class="footer-widget">
            <h4 class="footer-widget__title">Company</h4>
            <ul class="list-unstyled">
                <li>
                <a href="{{ route('article', 'about') }}">About</a>
                </li>
                <li>
                <a href="{{ route('article', 'contacts') }}">Contacts</a>
                </li>
                <li>
                <a href="{{ route('article', 'amazon-affiliate-disclosure') }}">Amazon Affiliate Disclosure</a>
                </li>
                <li>
                <a href="{{ route('article', 'terms-of-service') }}">Terms of Service</a>
                </li>
                <li>
                <a href="{{ route('article', 'privacy-policy') }}">Privacy Policy</a>
                </li>
            </ul>
            </div>
        </div>
        <div class="col-md-8 col-8">
            <div class="footer-widget">
            <h4 class="footer-widget__title">Departments</h4>
            <div class="row pl-3">
                <ul class="list-unstyled col-6">
                    <li>
                    <a href="/electronics">Electronics</a>
                    </li>
                    <li>
                    <a href="/sports-outdoors">Sports & Outdoors</a>
                    </li>
                    <li>
                    <a href="/automotive">Automotive</a>
                    </li>
                    <li>
                    <a href="/home-kitchen">Home & Kitchen</a>
                    </li>
                    <li>
                    <a href="/toys-games">Toys & Games</a>
                    </li>
                </ul>
                <ul class="list-unstyled col-6">
                    <li>
                    <a href="/pet-supplies">Pet Supplies</a>
                    </li>
                    <li>
                    <a href="/patio-lawn-garden">Patio, Lawn & Garden</a>
                    </li>
                    <li>
                    <a href="/musical-instruments">Musical Instruments</a>
                    </li>
                    <li>
                    <a href="/tools-home-improvement">Tools & Home Improvement</a>
                    </li>
                    <li>
                    <a href="/baby-products">Baby Products</a>
                    </li>
                </ul>
            </div>
            </div>
        </div>
    </div>
    </div>
</div>
</footer>
@if (Route::is('category'))
<div id="amzn-assoc-ad-2af12fcc-522d-49c5-8f26-de7d252f95e1"></div><script async src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=2af12fcc-522d-49c5-8f26-de7d252f95e1"></script>
@endif