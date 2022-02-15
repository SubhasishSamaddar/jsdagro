<!-- 
Clients 
-->
<!-- section class="our_client">  
	<hr class="soften"/>
	<h4 class="title cntr"><span class="text">Manufactures</span></h4>
	<hr class="soften"/>
	<div class="row">
		<div class="span2">
			<a href="#"><img alt="" src="{{ asset('front-assets/img/1.png') }}"></a>
		</div>
		<div class="span2">
			<a href="#"><img alt="" src="{{ asset('front-assets/img/2.png') }}"></a>
		</div>
		<div class="span2">
			<a href="#"><img alt="" src="{{ asset('front-assets/img/3.png') }}"></a>
		</div>
		<div class="span2">
			<a href="#"><img alt="" src="{{ asset('front-assets/img/4.png') }}"></a>
		</div>
		<div class="span2">
			<a href="#"><img alt="" src="{{ asset('front-assets/img/5.png') }}"></a>
		</div>
		<div class="span2">
			<a href="#"><img alt="" src="{{ asset('front-assets/img/6.png') }}"></a>
		</div>
	</div>
</section-->
</div><!-- .wrapper -->
<!--
Footer
-->
<footer class="siteFooter">
	<div class="footer-wrapper">
        <div class="footer-desc">
          <p><strong>JSD Agro</strong> has come into being with the vision to provide high-quality products at very reasonable prices and as a result, upgrade the lives of the people. We offer a large assortment of products including the items of your daily needs and grocery products delivered to your preferred location. Our online store allows you to walk away from the exhausting job of grocery shopping and enable you to browse and shop for groceries and other items in an easy relaxed way. </p>
          
        </div>     
      
		<div class="footer-top-row">
          <h5>All Categories</h5>
          <p>@php
			  	$parentCategory = DB::table('categories')->Where('parent_id','0')->Where('status','Active')->limit(12)->get();
				if( !$parentCategory->isEmpty() ):
					foreach( $parentCategory as $newData ):
			@endphp
						<a href="{{ route('category', $newData->name_url) }}">{{ $newData->name }}</a>,
			@php
					endforeach;
				endif;
			@endphp
          </p>
      </div>
      <div class="footer-mid-row">
          
            <div class="ftr-col one-fourth">
				<h5>Our Shops</h5>
				<p>Shop No: 5, Rishav Mansion, 4 Shantinagar Road, Bhadrakali, Uttarpara, Hooghly - 712232</p>
			</div>
			
			
			<div class="ftr-col one-fourth">
				<h5>Account</h5>
				<ul>
				    @if( isset(Auth::user()->id) )
	    			<li><a href="{{ route('my-profile') }}" >My Profile</a></li>
				    <li><a href="{{ route('my-order') }}" >My Orders</a></li>
				    <li><a href="{{ route('logout') }}" >Logout</a></li>
			        @else
		
	             	<li><a href="{{ route('login') }}" class="signin"> SignIn </a></li>
				    <li><a href="{{ route('register') }}" class="signup"> SignUp </a></li>
		        	@endif
	        
	    </ul>
				   
			</div>
			<div class="ftr-col one-fourth">
				<h5>Information</h5>
				<ul>
                  	<li><a href="{{ route('help') }}">Help</a></li>
					<!--<li><a href="{{ route('sitemap') }}">Sitemap</a></li>
					<li><a href="{{ route('legal-notice') }}">Legal Notice</a></li>-->
					<li><a href="{{ route('terms-and-conditions') }}">Terms &amp; Conditions</a></li>
					
				</ul>  
			</div>
            <div class="ftr-col one-fourth">
				<h5>Contacts</h5>
				<p>EN 62, Ice King Building<br/>EN Block, Near Webel More<br/>Sector 5, Saltlake, Kolkata<br/>
                  West Bengal, India 700091</p>
              <p><b>Phone:</b> +91 8617771271</p>
              <p><b>Email:</b> <a href="mailto:info@jsdagro.com">info@jsdagro.com</a></p>
			</div>
			
		</div>
		<div class="copyright">			
			<div class="copyright-txt">Copyright &copy; @php echo date('Y'); @endphp JSD Agro, All Rights Reserved.</div>
		</div>
	</div>
</footer>




<!--<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
           @php
			if(Cookie::has('swadesh_hut_name')):
				$swadesh_pin_code=Cookie::get('swadesh_pin_code');
			else:
				$swadesh_pin_code='';
			endif;
			@endphp
            <div class="modal-body">
            	<div class="modal-title">Enter Your Pincode Here</div>
				<div class="form-row">
					<input type="text" id="swadesh_hut_id" class="form-control" value="{{ $swadesh_pin_code}}" placeholder="700009">
					<input class="site-btn" type="submit" onclick="getSwadeshHut()" name="" value="Check Availability">
				</div>				
				<span id="msgstorename"></span>
            </div>  
        </div>
    </div>
</div>-->