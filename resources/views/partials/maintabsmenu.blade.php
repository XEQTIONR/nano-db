
  <nav class="navbar-fixed-top navbar-intertrac">
    <div class="container">
      <ul class="nav nav-intertrac">
        <li role="presentation" id="reportMenuItem">
          <a id="reportLabel" href="#">
            <span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>
            Generate Reports
            <span class="caret"></span>
          </a>
        </li>

        <li role="presentation" id="infoMenuItem">
          <a id="infoLabel" href="#">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            View Information
            <span class="caret"></span>
          </a>
        </li>

        <li role="presentation" id="actionMenuItem">
          <a id="actionLabel" href="#">
            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
            Perform Actions
            <span class="caret"></span>
          </a>
        </li>
      </ul>
    </div> <!--container-->
    <div class="container-fluid">
        @include('partials.mainmenu')
    </div> <!--container-fluid-->
  </nav>
