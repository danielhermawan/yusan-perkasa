@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="http://placehold.it/160x160/00a65a/ffffff/&text={{ Auth::user()->name[0] }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">{{ trans('backpack::base.administration') }}</li>

          <li class="treeview">
              <a href="#">
                  <i class="fa fa-users"></i> <span>People</span>
                  <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                  <li><a href="{{ url('/employee') }}"><i class="fa fa-circle-o"></i> <span>Employee</span></a></li>
                  <li><a href="{{ url('/role') }}"><i class="fa fa-circle-o"></i> <span>Role</span></a></li>
              </ul>
          </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i> <span>Product</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/product-type') }}"><i class="fa fa-circle-o"></i> <span>Product Type</span></a></li>
                    <li><a href="{{ url('/unit') }}"><i class="fa fa-circle-o"></i> <span>Unit</span></a></li>
                    <li><a href="{{ url('/product') }}"><i class="fa fa-circle-o"></i> <span>Product</span></a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-address-card"></i> <span>Vendor</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/supplier') }}"><i class="fa fa-circle-o"></i> <span>Supplier</span></a></li>
                    <li><a href="{{ url('/customer') }}"><i class="fa fa-circle-o"></i> <span>Customer</span></a></li>
                </ul>
            </li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif