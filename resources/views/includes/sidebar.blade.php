
<aside class="main-sidebar sidebar-dark-primary elevation-4   text-sm">
    <!-- Brand Logo -->

    {{-- <a href="{{ route('home') }}" class="brand-link text-sm">
      <img src="{{ asset('/images/logo.png')}}"
           alt="{{ config('app.name') }}"   class="brand-image elevation-5"
           style="opacity: 1">

    </a><br/>
    <span class="brand-text font-weight-light"></span> --}}
    <!-- Sidebar -->
    <div class="sidebar  text-sm">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('/storage/'.auth()->user()->profile_image)}}" class="img-circle elevation-5" alt="User Image">
        </div>

        <div class="info">
          <a class="d-block" href="#">{{ Auth::user()->name }}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->

      <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-child-indent nav-compact nav-flat nav-legacy" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          @if(Auth::user()->user_type=='Admin')
          <li class="nav-item has-treeview {{ in_array(Request::segment(2),array('users','roles','subscriptions','payments')) ? 'menu-open' : null }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                User & Role
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: ;">
              <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ Request::segment(2) === 'users' ? 'active' : null }}">
                  <i class="fas fa-user-circle nav-icon"></i>
                  <p>Users Management</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('roles.index') }}" class="nav-link {{ Request::segment(2) === 'roles' ? 'active' : null }}">
                  <i class="fas fa-users-cog nav-icon"></i>
                  <p>Roles Management</p>
                </a>
              </li>
            </ul>
          </li>
          @endif


          @if(Auth::user()->user_type=='Admin' || Auth::user()->user_type=='Swadesh_Hut')
          <li class="nav-item has-treeview {{ in_array(Request::segment(2),array('categories','products','generatecriticalproductreport')) ? 'menu-open' : null }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fab fa-product-hunt"></i>
              <p>Products
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: ;">
				      @if(Auth::user()->user_type=='Admin')
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ Request::segment(2) === 'categories' ? 'active' : null }}">
                    <i class="fas fa-folder-open nav-icon"></i>
                    <p>Category Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('brands.index') }}" class="nav-link {{ Request::segment(2) === 'brands' ? 'active' : null }}">
                    <i class="fab fa-product-hunt nav-icon"></i>
                    <p>Brand Management</p>
                    </a>
                </li>
				      @endif
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ Request::segment(2) === 'products' ? 'active' : null }}">
                    <i class="fab fa-product-hunt nav-icon"></i>
                    <p>Product Management</p>
                    </a>
                </li>
            </ul>
          </li>
          @endif







          @if(Auth::user()->user_type=='Admin')
          <li class="nav-item has-treeview {{ in_array(Request::segment(2),array('countries','states','audits','banner','user-contacts','contact-us-info','online-payments','company-payouts')) ? 'menu-open' : null }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: ;">
                <!--li class="nav-item">
                    <a href="{{ route('countries.index') }}" class="nav-link {{ Request::segment(2) === 'countries' ? 'active' : null }}">
                    <i class="fas fa-flag nav-icon"></i>
                    <p>Country Management</p>
                    </a>
                </li-->
                <li class="nav-item">
                    <a href="{{ route('user-contacts') }}" class="nav-link {{ Request::segment(2) === 'user-contacts' ? 'active' : null }}">
                    <i class="fas fa-clock nav-icon"></i>
                    <p>Help Contacts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('contact-us-info') }}" class="nav-link {{ Request::segment(2) === 'contact-us-info' ? 'active' : null }}">
                    <i class="fas fa-clock nav-icon"></i>
                    <p>User Contacts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('banner.index') }}" class="nav-link {{ Request::segment(2) === 'banner' ? 'active' : null }}">
                    <i class="fas fa-city nav-icon"></i>
                    <p>Banner Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('states.index') }}" class="nav-link {{ Request::segment(2) === 'states' ? 'active' : null }}">
                    <i class="fas fa-city nav-icon"></i>
                    <p>State Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('audits') }}" class="nav-link {{ Request::segment(2) === 'audits' ? 'active' : null }}">
                    <i class="fas fa-clock nav-icon"></i>
                    <p>Audit Log</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('online-payments') }}" class="nav-link {{ Request::segment(2) === 'online-payments' ? 'active' : null }}">
                    <i class="fas fa-clock nav-icon"></i>
                    <p>Online Payments</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo url('/') ?>/cpanel/company-payouts" class="nav-link {{ Request::segment(2) === 'company-payouts' ? 'active' : null }}">
                    <i class="fas fa-clock nav-icon"></i>
                    <p>Company Payout</p>
                    </a>
                </li>
            </ul>
          </li>
          @endif



          @if(Auth::user()->user_type=='Package' || Auth::user()->user_type=='Admin')
          <li class="nav-item has-treeview {{ in_array(Request::segment(2),array('package_location','package_inventory','swadesh_hut','voucher','inventory-in-log')) ? 'menu-open' : null }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Location Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: ;">
                @if(Auth::user()->user_type=='Admin')
                <li class="nav-item">
                  <a href="{{ route('package_location.index') }}" class="nav-link {{ Request::segment(2) === 'package_location' ? 'active' : null }}">
                    <i class="fas fa-map nav-icon"></i>
                    <p>Package Location</p>
                  </a>
                </li>
                @endif

                @if(Auth::user()->user_type=='Package')
                <li class="nav-item">
                  <a href="{{ route('package_inventory.index') }}" class="nav-link {{ Request::segment(2) === 'package_inventory' ? 'active' : null }}">
                    <i class="fas fa-map nav-icon"></i>
                    <p>Package Inventory</p>
                  </a>
                </li>
                
                <li class="nav-item">
                  <a href="{{ url('cpanel/package_inventory/inventory-in-log') }}" class="nav-link {{ Request::segment(2) === 'inventory-in-log' ? 'active' : null }}">
                    <i class="fas fa-map nav-icon"></i>
                    <p>Inventory In Log</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="{{ url('cpanel/package_inventory/inventory-out-log') }}" class="nav-link {{ Request::segment(2) === 'inventory-out-log' ? 'active' : null }}">
                    <i class="fas fa-map nav-icon"></i>
                    <p>Inventory Out Log</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="<?php echo url('/'); ?>/cpanel/package_inventory/voucher" class="nav-link {{ Request::segment(2) === 'voucher' ? 'active' : null }}">
                    <i class="fas fa-map nav-icon"></i>
                    <p>Voucher Management</p>
                  </a>
                </li>


                <li class="nav-item">
                  <a href="<?php echo url('/'); ?>/cpanel/image-crop" class="nav-link {{ Request::segment(2) === 'image-crop' ? 'active' : null }}">
                    <i class="fas fa-map nav-icon"></i>
                    <p>Image Management</p>
                  </a>
                </li>


                @endif
                <li class="nav-item">
                    <a href="{{ url('/').'/cpanel/package_inventory/datewisereport' }}" class="nav-link {{ Request::segment(2) === 'package_inventory' ? 'active' : null }}">
                      <i class="fas fa-file-excel-o"></i>
                      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Generate Report</p>
                    </a>
                </li>

                @if(Auth::user()->user_type=='Admin')
                <li class="nav-item">
                  <a href="{{ route('stores.index') }}" class="nav-link {{ Request::segment(2) === 'stores' ? 'active' : null }}">
                    <i class="fas fa-home nav-icon"></i>
                    <p>Stores</p>
                  </a>
                </li>

                <li class="nav-item">
                    <a target="blank" href="{{ url('/').'/cpanel/products/generatestockreport' }}" class="nav-link {{ Request::segment(2) === 'generatestockreport' ? 'active' : null }}">
                      <i class="fas fa-file-excel-o"></i>
                      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Generate Stock Report</p>
                    </a>
                </li>
                @endif
            </ul>
          </li>
          @endif
          @if(Auth::user()->user_type=='Admin' || Auth::user()->user_type=='Swadesh_Hut')
          <li class="nav-item" class="nav-link" >
            <a href="{{ route('order.index') }}" class="nav-link {{ Request::segment(2) === 'order' ? 'active' : null }}" >
              <i class="nav-icon fab fa-buffer"></i>
              <p>Order Management</p>
            </a>
          </li>

          <li class="nav-item" class="nav-link" >
            <a href="{{ route('swadesh_hut_pos.index') }}" class="nav-link {{ Request::segment(2) === 'swadesh_hut_pos' ? 'active' : null }}" >
              <i class="nav-icon fab fa-buffer"></i>
              <p>POS Management</p>
            </a>
          </li>
          @endif

          @if(Auth::user()->user_type=='Admin' || Auth::user()->user_type=='Swadesh_Hut')
          <li class="nav-item">
            <a href="<?php echo url('/'); ?>/cpanel/package_inventory/voucher?usertype=swadeshhutuser" class="nav-link {{ Request::segment(2) === 'voucher' ? 'active' : null }}">
              <i class="fas fa-map nav-icon"></i>
              <p>Voucher Management</p>
            </a>
          </li>
          
          @endif
          <li class="nav-item">
            <a href="<?php echo url('/'); ?>/cpanel/returned-product" class="nav-link {{ Request::segment(2) === 'returned-product' ? 'active' : null }}">
              <i class="fas fa-map nav-icon"></i>
              <p>Returned Product</p>
            </a>
          </li>



          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-circle"></i>
              <p>
                My Account
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">


                <li class="nav-item">
                  <a href="{{ route('change.profile') }}" class="nav-link {{ Request::segment(2) === 'profile_update' ? 'active' : null }}">
                    <i class="far fa-user nav-icon"></i>
                    <p>Profile</p>
                  </a>
                </li>



                <li class="nav-item">
                  <a href="{{ route('change.password') }}" class="nav-link {{ Request::segment(2) === 'change-password' ? 'active' : null }}">
                    <i class="fas fa-lock-open nav-icon"></i>
                    <p>Change Password</p>
                  </a>
                </li>



                <li class="nav-item">
                  <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();    document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <p>Logout</p>
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                </li>

            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
