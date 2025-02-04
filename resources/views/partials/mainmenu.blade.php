{{--- mainmenu.blade.php
@author Ishtehar Hussain
@desc The main menu of the app in a partial file.
--}}

{{--<div class="container-fluid">--}}

  <!-- REPORTS -->

  <div class="row subMenu" id="theMenu">

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/reports/order">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/order.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Order Reports
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
              All reports regarding orders made (monthly, yearly, etc).
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/reports/payment">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1" >
            <img src="/images/cash.png" width="48 " height="48">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Payment Reports
              </div>
            </div> <!--row-->

            <div class="row">
              <div class="col-md-12">
                Reports regarding payments made against orders.
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/reports/expense">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/expense.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Expenditure Reports
              </div>
            </div>
              <div class="row">
                <div class="col-md-12">
                  Reports about all different expenses.
                </div>
              </div>
            </div>
          </div> <!--row-->
        </a>
      </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/reports/outstanding_balance">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/outstanding-balance.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Outstanding Balance Report
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                Reports about money owed to us from customers.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="#">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/profit.png" width="40" height="40">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Profit/Loss Reports
              </div>
            </div> <!--row-->

            <div class="row">
              <div class="col-md-12">
                Report evaluating how our business ventures are doing financially.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>

  </div>

  <!-- VIEW INFO -->

  <div class="row subMenu" id="theMenu2">

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/customers">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/user.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Customers
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
              View all customer information.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/tyres">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1" >
            <img src="/images/tyre.png" width="35 " height="35">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Tyres
              </div>
            </div> <!--row-->

            <div class="row">
              <div class="col-md-12">
                View the tyre Catalog.
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/consignments">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/consignment.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Consignments
              </div>
            </div>
              <div class="row">
                <div class="col-md-12">
                View all consignments arrived.
                </div>
              </div>
            </div>
          </div> <!--row-->
        </a>
      </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/lcs">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/lc.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                LCs
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                View all information about LCs applied for.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/consignment_expenses">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/expense.png" width="40" height="40">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Expenses
              </div>
            </div> <!--row-->

            <div class="row">
              <div class="col-md-12">
                View all consignment expenses.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/orders">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/order.png" width="40" height="40">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Orders
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
              View all orders placed.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/payments">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/cash.png" width="48" height="48">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Payments
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                View all payments recieved from customers.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>


    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/stock">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/stock.png" width="40" height="40">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Stock
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                View current stock.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div><!--col-->


    {{--<div class="col-md-4">
      <a href="/consignment_containers">
        consignment_containers
      </a>
    </div> --}}

  </div>

  <!-- ACTIONS -->

  <div class="row subMenu" id="theMenu3">

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/tyres/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1" >
            <img src="/images/tyre.png" width="35 " height="35">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Add a new Tyre
              </div>
            </div> <!--row-->

            <div class="row">
              <div class="col-md-12">
                Add a new kind of tyre to tyre catalog.
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/lcs/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/lc.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Add a new LC
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                Add a new approved Letter of Credit to the database.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>
    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/performa_invoices/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/performa-invoice.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Add a new proforma invoice
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                The draft invoice provided to the bank when creating a LC.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>
    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/consignments/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/consignment.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Add a new Consignment
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                Add a new consignment to the database against a LC.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>
    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/consignment_expenses/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/expense.png" width="40" height="40">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Add a new Expense
              </div>
            </div> <!--row-->

            <div class="row">
              <div class="col-md-12">
                Add a new expense for a consignment.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/customers/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/user.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Add a customer
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
              Add a new customer who can order from us.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>
    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/orders/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/order.png" width="40" height="40">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Create a new order
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                Record a new order placed by a customer.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>
    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/payments/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/cash.png" width="48" height="48">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Record a payment made
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                Create a payment invoice for a new payment.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>

    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/consignment_containers/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/container.png" width="45" height="45">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Add a new container
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                Add a new container arrived to an existing consignment recorded.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>
    <div class="col-md-4 col-lg-3 vertical-spaced">
      <a href="/container_contents/create">
        <div class="row">
          <div class="col-md-2 col-sm-1 col-xs-1">
            <img src="/images/commercial-invoice.png" width="35" height="35">
          </div>

          <div class="col-md-10 col-sm-11 col-xs-11">
            <div class="row">
              <div class="col-md-12 menu-option-title">
                Add a new commercial invoice
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                Record information about goods from new containers.
              </div>
            </div>
          </div>
        </div> <!--row-->
      </a>
    </div>
    <!--<div class="col-md-4"><a href="/hscodes/create">Create a new Hscode</a></div>-->
    {{--make hscode a field--}}
  </div>

{{--}}</div> <!--container fluid-->--}}
