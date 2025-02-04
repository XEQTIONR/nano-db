<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel (optional) -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="/images/icon-user.png" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{Auth::user()->name}}</p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- search form (Optional) -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
      </div>
    </form>
    <!-- /.search form -->

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <li><a href="/"><i style="position: relative; top: 2px;" class="icon-tachometer-s mr-2"></i> <span>Dashboard</span></a></li>
      <li class="header">AVAILABLE OPTIONS</li>
      <!-- Optionally, you can add icons to the links -->
      {{--<li class="active"><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>--}}



      <li class="treeview
                  @if(stristr(Route::currentRouteName(), 'lcs'))
        active
@endif
        ">
        <a href="#"><i class="icon-file-invoice-dollar-r mr-2"></i> <span>Letters of Credit</span>
          <span class="pull-right-container">
                <i class="icon-angle-left-s fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{route('lcs.create')}}"><i class="fa fa-angle-right"></i>Add a LC</a></li>
          <li><a href="{{route('lcs.index')}}"><i class="fa fa-angle-right"></i>View LCs</a></li>
          <li><a href="{{route('proforma_invoice.create')}}"><i class="fa fa-angle-right"></i>Add a Proforma invoice</a></li>
        </ul>
      </li>

      <li class="treeview
                  @if(stristr(Route::currentRouteName(), 'consignment'))
        active
@endif
        ">
        <a href="#"><i class="icon-anchor-r mr-2"></i> <span>Consignments</span>
          <span class="pull-right-container">
                <i class="icon-angle-left-s  fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{  route('consignments.index')  }}"><i class="fa fa-angle-right"></i>View consignments</a></li>
          <li><a href="{{  route('consignment_containers.index'  )}}"><i class="fa fa-angle-right"></i>View containers</a></li>
          <li><a href="{{ route('consignments.create')  }}"><i class="fa fa-angle-right"></i>Add a consignment</a></li>
          <li><a href="{{  route('consignment_containers.create')  }}"><i class="fa fa-angle-right"></i>Add a container</a></li>
          <li><a href="{{  route('consignment_expenses.create')  }}"><i class="fa fa-angle-right"></i>Add an expense</a></li>
          <li><a href="{{ route('waste.create') }}"><i class="fa fa-angle-right"></i>Add Waste</a></li>
        </ul>
      </li>

      <li class="treeview
                    @if(stristr(Route::currentRouteName(), 'orders') || stristr(Route::currentRouteName(), 'returns'))
        active
@endif
        ">
        <a href="#"><i class="icon-dolly-s mr-2"></i> <span>Orders</span>
          <span class="pull-right-container">
                <i class="icon-angle-left-s  fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{  route('orders.index')  }}"><i class="fa fa-angle-right"></i>View orders</a></li>
          <li><a href="{{  route('orders.create')  }}"><i class="fa fa-angle-right"></i>Create an order</a></li>
          <li><a href="{{ route('returns.create') }}"><i class="fa fa-angle-right"></i>Returns</a></li>
        </ul>
      </li>

      <li class="treeview
                    @if(stristr(Route::currentRouteName(), 'payments'))
        active
@endif
        ">
        <a href="#"><i class="icon-hand-holding-usd-s mr-2"></i> <span>Payments</span>
          <span class="pull-right-container">
                <i class="icon-angle-left-s  fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{  route('payments.index')  }}"><i class="fa fa-angle-right"></i>View payments made</a></li>
          <li><a href="{{ route('payments.create')  }}"><i class="fa fa-angle-right"></i>Record a payment</a></li>
        </ul>
      </li>

      <li class="treeview
                    @if(stristr(Route::currentRouteName(), 'tyres') || Route::currentRouteName() == 'stock')
        active
@endif
        ">
        <a href="#"><i class="icon-tyre-s mr-2"></i> <span>Products</span>
          <span class="pull-right-container">
                <i class="icon-angle-left-s  fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{  route('tyres.index')  }}"><i class="fa fa-angle-right"></i>View tyre catalog</a></li>
          <li><a href="{{  route('stock')  }}"><i class="fa fa-angle-right"></i>View current stock</a></li>
          <li><a href="{{ route('tyres.create') }}"><i class="fa fa-angle-right"></i>Add a tyre</a></li>

        </ul>
      </li>

      <li class="treeview
                    @if(stristr(Route::currentRouteName(), 'customers'))
        active
@endif
        ">
        <a href="{{ route('customers.index') }}"><i class="icon-users-s mr-2"></i> <span>Customers</span>
          <span class="pull-right-container">
                <i class="icon-angle-left-s  fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('customers.create')}} "><i class="fa fa-angle-right"></i>Add a customer</a></li>
          <li><a href="{{  route('customers.index')  }}"><i class="fa fa-angle-right"></i>View customers</a></li>
        </ul>
      </li>

      <li class="treeview
                    @if(stristr(Route::currentRouteName(), 'reports'))
        active
@endif
        ">
        <a href="#"><i class="icon-chart-bar-s mr-2"></i> <span>Reports</span>
          <span class="pull-right-container">
                <i class="icon-angle-left-s  fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="#"><i class="fa fa-angle-right"></i>Order reports</a></li>
          <li><a href="#"><i class="fa fa-angle-right"></i>Payment reports</a></li>
          <li><a href="#"><i class="fa fa-angle-right"></i>Expenditure reports</a></li>
          <li><a href="#"><i class="fa fa-angle-right"></i>Outstanding Balance reports</a></li>
          <li><a href="#"><i class="fa fa-angle-right"></i>Profit/loss report</a></li>
        </ul>
      </li>
      @if(Auth::user()->admin)
        <li class="header">ADMIN ACTIONS</li>
        <li class="treeview">
          <a href="#"><i class="icon-user-r mr-2"></i> <span>Users</span>
            <span class="pull-right-container">
                <i class="icon-angle-left-s  fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('users.index') }}"><i class="fa fa-angle-right"></i>View users</a></li>
            <li><a href="{{ route('register') }}"><i class="fa fa-angle-right"></i>Add an user</a></li>
          </ul>
        </li>
      @endif

    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>