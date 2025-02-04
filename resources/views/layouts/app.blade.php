<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>intertracNano | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
{{--<link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">--}}
<!-- Font Awesome -->
{{--<link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">--}}
<!-- Ionicons -->
{{--<link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">--}}
<!-- Theme style -->
    {{--<link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">--}}

    <link rel="stylesheet" href="/css/app2.css">
    <link rel="stylesheet" href="/css/styles.css">
    {{--<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.1/css/all.css">--}}
    {{--<link rel="stylesheet" href='https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css'>--}}
  <!-- iCheck -->
{{--<link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">--}}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('header-scripts')
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>

<body class="hold-transition skin-black sidebar-mini">


<div class="wrapper" style="overflow: hidden">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>n</b>DB</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>nano</b>DB</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                {{--<li class="dropdown messages-menu">--}}
                {{--<!-- Menu toggle button -->--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                {{--<i class="far fa-envelope"></i>--}}
                {{--<span class="label label-success">4</span>--}}
                {{--</a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li class="header">You have 4 messages</li>--}}
                {{--<li>--}}
                {{--<!-- inner menu: contains the messages -->--}}
                {{--<ul class="menu">--}}
                {{--<li><!-- start message -->--}}
                {{--<a href="#">--}}
                {{--<div class="pull-left">--}}
                {{--<!-- User Image -->--}}
                {{--<img src="http://www.faidamarketlink.or.tz/faidamalimis/dist/img/user.png" class="img-circle" alt="User Image">--}}
                {{--</div>--}}
                {{--<!-- Message title and timestamp -->--}}
                {{--<h4>--}}
                {{--Support Team--}}
                {{--<small><i class="fa fa-clock-o"></i> 5 mins</small>--}}
                {{--</h4>--}}
                {{--<!-- The message -->--}}
                {{--<p>Why not buy a new awesome theme?</p>--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<!-- end message -->--}}
                {{--</ul>--}}
                {{--<!-- /.menu -->--}}
                {{--</li>--}}
                {{--<li class="footer"><a href="#">See All Messages</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                <!-- /.messages-menu -->

                    <!-- Notifications Menu -->
                {{--<li class="dropdown notifications-menu">--}}
                {{--<!-- Menu toggle button -->--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                {{--<i class="far fa-bell"></i>--}}
                {{--<span class="label label-warning">10</span>--}}
                {{--</a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li class="header">You have 10 notifications</li>--}}
                {{--<li>--}}
                {{--<!-- Inner Menu: contains the notifications -->--}}
                {{--<ul class="menu">--}}
                {{--<li><!-- start notification -->--}}
                {{--<a href="#">--}}
                {{--<i class="fa fa-users text-aqua"></i> 5 new members joined today--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<!-- end notification -->--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="footer"><a href="#">View all</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                <!-- Tasks Menu -->
                {{--<li class="dropdown tasks-menu">--}}
                {{--<!-- Menu Toggle Button -->--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                {{--<i class="far fa-pennant"></i>--}}
                {{--<span class="label label-danger">9</span>--}}
                {{--</a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li class="header">You have 9 tasks</li>--}}
                {{--<li>--}}
                {{--<!-- Inner menu: contains the tasks -->--}}
                {{--<ul class="menu">--}}
                {{--<li><!-- Task item -->--}}
                {{--<a href="#">--}}
                {{--<!-- Task title and progress text -->--}}
                {{--<h3>--}}
                {{--Design some buttons--}}
                {{--<small class="pull-right">20%</small>--}}
                {{--</h3>--}}
                {{--<!-- The progress bar -->--}}
                {{--<div class="progress xs">--}}
                {{--<!-- Change the css width attribute to simulate progress -->--}}
                {{--<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"--}}
                {{--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
                {{--<span class="sr-only">20% Complete</span>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<!-- end task item -->--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="footer">--}}
                {{--<a href="#">View all tasks</a>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="/images/icon-user.png" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{Auth::user()->name}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="/images/icon-user.png" class="img-circle" alt="User Image">

                                <p>
                                    {{Auth::user()->name}}
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                        {{--<li class="user-body">--}}
                        {{--<div class="row">--}}
                        {{--<div class="col-xs-4 text-center">--}}
                        {{--<a href="#">Followers</a>--}}
                        {{--</div>--}}
                        {{--<div class="col-xs-4 text-center">--}}
                        {{--<a href="#">Sales</a>--}}
                        {{--</div>--}}
                        {{--<div class="col-xs-4 text-center">--}}
                        {{--<a href="#">Friends</a>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<!-- /.row -->--}}
                        {{--</li>--}}
                        <!-- Menu Footer-->
                            <li class="">
                                {{--<div class="pull-left">--}}
                                {{--<a href="#" class="btn btn-default btn-flat">Profile</a>--}}
                                {{--</div>--}}
                                <div class="">
                                    <a href="{{ route('logout') }}" class="btn btn-danger btn-flat btn-block"
                                       onclick="event.preventDefault();
                             document.getElementById('Logout-form').submit();">Sign out</a>
                                </div>

                                <form id="Logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{csrf_field()}}
                                </form>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    {{--<li>--}}
                    {{--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>--}}
                    {{--</li>--}}
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    @include('components.sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
              @if(\Route::current()->getName() != 'home')
                @yield('title')
              @endif
                <small>@yield('subtitle')</small>
            </h1>
            @yield('level')

        </section>

        <!-- Main content -->
        <section id="app" class="content container-fluid">

            @yield('modal')

            @yield('body')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer no-print">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            v {{config('app.version')}}
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2020 <a href="/">Intertrac Nano</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
{{--<aside class="control-sidebar control-sidebar-dark">--}}
{{--<!-- Create the tabs -->--}}
{{--<ul class="nav nav-tabs nav-justified control-sidebar-tabs">--}}
{{--<li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>--}}
{{--<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>--}}
{{--</ul>--}}
{{--<!-- Tab panes -->--}}
{{--<div class="tab-content">--}}
{{--<!-- Home tab content -->--}}
{{--<div class="tab-pane active" id="control-sidebar-home-tab">--}}
{{--<h3 class="control-sidebar-heading">Recent Activity</h3>--}}
{{--<ul class="control-sidebar-menu">--}}
{{--<li>--}}
{{--<a href="javascript:;">--}}
{{--<i class="menu-icon fa fa-birthday-cake bg-red"></i>--}}

{{--<div class="menu-info">--}}
{{--<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>--}}

{{--<p>Will be 23 on April 24th</p>--}}
{{--</div>--}}
{{--</a>--}}
{{--</li>--}}
{{--</ul>--}}
{{--<!-- /.control-sidebar-menu -->--}}

{{--<h3 class="control-sidebar-heading">Tasks Progress</h3>--}}
{{--<ul class="control-sidebar-menu">--}}
{{--<li>--}}
{{--<a href="javascript:;">--}}
{{--<h4 class="control-sidebar-subheading">--}}
{{--Custom Template Design--}}
{{--<span class="pull-right-container">--}}
{{--<span class="label label-danger pull-right">70%</span>--}}
{{--</span>--}}
{{--</h4>--}}

{{--<div class="progress progress-xxs">--}}
{{--<div class="progress-bar progress-bar-danger" style="width: 70%"></div>--}}
{{--</div>--}}
{{--</a>--}}
{{--</li>--}}
{{--</ul>--}}
{{--<!-- /.control-sidebar-menu -->--}}

{{--</div>--}}
{{--<!-- /.tab-pane -->--}}
{{--<!-- Stats tab content -->--}}
{{--<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>--}}
{{--<!-- /.tab-pane -->--}}
{{--<!-- Settings tab content -->--}}
{{--<div class="tab-pane" id="control-sidebar-settings-tab">--}}
{{--<form method="post">--}}
{{--<h3 class="control-sidebar-heading">General Settings</h3>--}}

{{--<div class="form-group">--}}
{{--<label class="control-sidebar-subheading">--}}
{{--Report panel usage--}}
{{--<input type="checkbox" class="pull-right" checked>--}}
{{--</label>--}}

{{--<p>--}}
{{--Some information about this general settings option--}}
{{--</p>--}}
{{--</div>--}}
{{--<!-- /.form-group -->--}}
{{--</form>--}}
{{--</div>--}}
{{--<!-- /.tab-pane -->--}}
{{--</div>--}}
{{--</aside>--}}
<!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
{{--<script src="node_modules/jquery/dist/jquery.min.js"></script>--}}
{{--<script src="js/jquery.min.js"></script>--}}

<!-- Bootstrap 3.3.7 -->
{{--<script src="~/bootstrap/dist/js/bootstrap.min.js"></script>--}}
{{--<script src="js/bootstrap.min.js"></script>--}}

<!-- AdminLTE App -->
{{--<script src="dist/js/adminlte.min.js"></script>--}}
{{--<script src="js/adminlte.min.js"></script>--}}

<!-- Vue JS DEV-->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<!-- Vue JS PROD-->
{{--<script src="https://cdn.jsdelivr.net/npm/vue"></script>--}}

<script src="/js/app.js"></script>
{{--<script src="/js/jquery.inputmask.bundle.js"></script>--}}
{{--<script src='js/jquerydataTables.min.js'></script>--}}
{{--<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>--}}

<script>
    var table;
    $(".date").text(function(index, text){

        var temp = text.split(" ");

        if(temp.length == 2)
            return temp[0].split('-').reverse().join('/')+" "+temp[1];
        return text.split('-').reverse().join('/');

    });

    $(document).ready(function() {
     table =   $('#table_id').DataTable();
        $(".date").inputmask( "dd/mm/yyyy");
    } );

    //console.log("CURRENCY : " + currencies.BDT);

    function commafy(number)
    {
        let str = "" + number.toFixed(2);
        let split = str.split(".");

        let whole = split[0].split("").reverse().join("");

        let spaces = [3,2,2];

        let output = ""

        let l = 0;
        let i = 0;

        while(l<whole.length)
        {
            output = output + whole.substr(l, spaces[i])+',';

            l = l+ spaces[i];
            i = (i+1) % spaces.length

        }

        split[0] = output.substr(0, output.length-1).split("").reverse().join("");


        return split.join(".");

    }

    function customFormatDataTable(columns)
    {
        let targets = columns.map( (item) => item.number);

        table = $('#table_id').DataTable({
            destroy : true,
            columnDefs : [{
                targets : targets,
                render : function(data, type, row){
                    if(type == "display")
                        return data;

                    else
                        return data.replace(/,/g, '');
                }
            }],

            footerCallback : function(row, data, start, end, display){
                var api = this.api();

                var page = $('.dataTables_filter input').val().length>0 ? 'current' : 'all';


                for(let i=0; i<columns.length; i++)
                {
                    var total = api
                        .column( columns[i].number, {page: page} )
                        .data()
                        .reduce( function (a, b) {
                            return parseFloat(a) + parseFloat(b.replace(/,/g, ''));
                        }, 0 );

                    $( api.column( columns[i].number ).footer() ).html(columns[i].prefix+commafy(total));

                }

                var footer_label = (page == 'current') ? 'TOTAL (current page)' : 'TOTAL (all pages)';



                $( api.column( 0 ).footer() ).html(footer_label);
            }
        });
    }
</script>


<script>



    function number_format(float_val, decimal_places){
        return float_val.toFixed(decimal_places).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    //<!-- Vue Filters -->
    Vue.filter('currency', function (value) {
        return commafy(parseFloat(value));
    });

    Vue.filter('percentage_rounded', function (value) {
        return parseFloat(value).toFixed(5);
    });

    Vue.filter('date', function(value){
        return value.split("-").reverse().join("/");
    });

    Vue.filter('transactionid_zerofill', function(value){

        return value.toString().padStart(10, "0");
    });

    Vue.filter('ddmmyyyy', function(value){

        return value.split(" ")[0].split("-").reverse().join('/');
    });

    //<!-- Vue Components -->
    Vue.component('v-select', VueSelect.VueSelect);
</script>




@yield('footer-scripts')

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>

</html>
