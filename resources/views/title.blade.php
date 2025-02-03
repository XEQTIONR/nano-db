<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                /*height: 100vh;*/
                margin: 0;
            }

            .full-height {
                height: auto;
                margin-top: 30vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
                width: 100%;
            }

            .title {
                font-size: 84px;
            }

            .title span{
              color: orange;
            }

            .links{
              /*display : block;
              width : auto;
              margin : 0;
              background-color: teal;*/
            }

            .links table
            {
              margin-left: auto;
              margin-right: auto;
            }

            .links > a, td > a, a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .links > a:hover, td > a:hover{
              color: orange;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .mainMenu{
              /*width: 100%;*/
              display : block;
              margin : 0;
            }

            .mainMenu ul{
              list-style-type: none;
            }

            .mainMenuImage{
              display : block;
              margin : auto;
            }

            .subMenu{
              width: 100%;
              background-color: orange;
              height: auto;

              display: block;
              margin: 0;
            }

            .subMenu ul{
              margin: 0;
              color: white;
              list-style-type: none;
            }

            .subMenu li{
              display: block;
              width: 33%;
              float: left;
            }

            .subMenu li a{
              color: #FFF;
            }

            .subMenu li a:hover{
              color: #CCC;
            }


        </style>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <script>
          $(document).ready(function(){

            $(".subMenu").hide();         //slideToggle changes this to show
            $(".icons8-Bar-Chart, #reportLabel").click(function(){

                $("#theMenu").slideToggle();
                $("#theMenu2").slideUp();
                $("#theMenu3").slideUp();
            });
            $(".icons8-Visible, #infoLabel").click(function(){
              $("#theMenu2").slideToggle();
              $("#theMenu").slideUp();
              $("#theMenu3").slideUp();
            });
            $(".icons8-Add, #actionLabel").click(function(){
              $("#theMenu3").slideToggle();
              $("#theMenu2").slideUp();
              $("#theMenu").slideUp();
            });
          });
        </script>
    </head>

    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <span>nano</span>DB
                </div>





                <div class="links">
                  <table>
                  <tr>

                    <td>
                      <!-- Bar Chart icon by Icons8 -->
                      <img class="icon icons8-Bar-Chart" width="50" height="50" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAA1UlEQVRoQ+2ZWw6AIAwE9f6H1kQh4iMEuiU2ZPwWcLs7gLAukzzrJDoWhERzMpIjm7E4hwaEGKtXa5YdaS3u7f3WRgO++9UlQqIy0poSojWaExiBkUEZI1rTRkvatDnEzS1a0wkxrawRHUGI6Io7IziCI2cFiNZzZZcqIsYKR/JJUDlDqY6oOwNpfIR8zDJSRSPNWghJM55UiBGMWLc4CPFeEKWKAnu6GoER1pFrd/lkSmKMaBEtolW/c4QRGIGRfkZ6D0QyZ17/7Kbxv2A3dVQw80v7HX6EvjPxvEFEAAAAAElFTkSuQmCC">
                    </td>

                    <td>
                      <!-- Visible icon by Icons8 -->
                      <img class="icon icons8-Visible" width="50" height="50" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAD2ElEQVRoQ+3Zi5HURhAG4L4IIAQTARCBfREAEdiOADsC7AhsIgAiACKwicAQAQ4BIoD6KPVV3+yMVrtCBXWlqdpCexr19P/onllxETdkXNwQHLED+d6U3BXZFdmIgd1aGxF7dthdkbOp2+jBXZEOsbcj4seIuBcRP0WE767reBsRHyLi34hw/Wb6vlqntYpI9kFEPJw+5yT0KiJ8Xq8BdS6QHyLicUT8MjGfADCMbR/MY70OCnk2VaNgDvOfR8TTiPj/VEZOBUKBJxHxW7PQnxHx9xmMiieWmHWIJSZwi8YpQCjwR6OARViCtdYM1mLRjxFxawoEhPUodHQsAcIKz6YCFpB9MPZyin5nYAXggFf4Brth2b/tsMb76Y+PJpXSdub/esxux4Cogb8mFbDFBnzs78C9mK7bxNwzpzeAwXQ7xP15Sto1IvxLIer8Pn3vBp0DUpNhH4mlZ9MKmLJYHRJItUaWuOwok+RQPFVUQ+KzneHamgejB8TDEslgmGClOshN+p6t8t4IhL/XZHOeTvbf4B4ncIYhPvtdawQtECD+mdojK2G35+lPU9AeEXlvDoh7c8/27gEqF1bT1ql6BaY+YCIlFN67yUrtPpDJfQsg1pYje91twSQQSpA1QbDVXA8HULD7nU1vibV6LTuthcT2aFPVlas1roFJIEB4WJBjIATdstiX7EsVDFCXCSRZPPDewOhZfKP2qznYQ3pj1H6TnF5zaePUWv7SOKq1DuSaqda6gY02RMraL3Jjs6DvxzbEUbxMp4K4clBb7LWQtLi5w1tuYJg0d83QZHTIkcK1RdvfDsqg135TGcWuxY06F1Xc0w6X2GEENG2q3Ys5ajKStzVQ5KCWRxsiltMSFhod3OouPjdvBEId5WZLVev2Rp3Hotad3RBrkLROdilHgx5bebTIedQ59nsC83bqPDX3jjriYZ+Vct7QeksOjRjLgxvWBWtHPeC5hwQWZYG0Jmvo/ZpAHijZyXVPCQdIawNTD6xdyY4B8RD2JFaP1Vhva8c8XUkCSwZCENOqDDC18qzHSsDOqrwESCZlUYnmDx8sqp22nQJEIYm4poJBHcmY79k2MfPVQtqICtZrD6xnK1IfJLPgdbOjjMUwd6w22iQApTSS6rHk5J/OpyhSk5CAxUmeCrkPVH35gNVaI+YiA/s+NXlzWRgppxKy+n+sJJWvgvLHz5L6qHOcrfKV0OKXDe0i5yrSSzaZri/osj5yvjqpL+jytdGp4A/mf00gq5NZE2AHsoa9LZ7dFdmC1TUxd0XWsLfFs7siW7C6JuauyBr2tnj2xijyGZrT8TNGmdBrAAAAAElFTkSuQmCC">
                    </td>

                    <td>
                      <!-- Add icon by Icons8 -->
                      <img class="icon icons8-Add" width="50" height="50" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAADcElEQVRoQ+2agZHUMAxF/1XAdQBUAFQAVABUAFRwUAFQAXQAVABUAFQAVHBHB1ABzJuxGCexEzlRsrsZNLOzu7OOrS99S7K8Z9qJnO0Eh/4DqXjygaTbku5JOk+f86HfJf2S9EUSnz9FMSLCIyj9WNKTmUq9k/Q+gZs5hRZR66Gk15JuZKt/zax9layeK4e3GM87z9/KfmT8c0kf56CZ4xEUeZvow5o/Jb1JCqBMizAXgJ5Jup4ehHZPJTXN1QoE+uAF+P87KQA1IoS5Mci1tI8A4/ZOCxAAYDkETvOZjRspGAjDEDSQl5JeeRbwAoFKtpmxVJQXajqyFmsirMWao+IBgrsvEpXgMxzeQlgLEFANr+CdqkwByS1zf0MQpjBgPqQvj8b2zBgQIsq3tLG3oNMUzdiPd2rRbAzI5xRi2dhzk10UBaEYSRdaw4yB1ICYSwmxeCY6OrUCJJqRV9gvRYrVgFwmAIekVB+s7VdqNCjWkRIQaidoRcbOy49WK64xHq9QAQwCTwmI8ZG6h9B7TEISJjEP9m0JyJ+k+c3WeqeCmMiHDOgww0IwBNojHd37QGyTU8VCsQgxw0zlLO9a7BGq5g69+pOTPV94Mql3VUnRQIo69oEQp+/WQlyD8vnQaCBF1vSBwGcOPfAZF0ZINBD0Q89OGO4DiV4UQ2wyZyQQ82aLF4vJzTHBwDiRQCyaOPT4N+RHodPieX5VIDUFDkIts+opbPaON3cbfk8hIdrRu3P8PcUSxaLjaImSx/2ootESK4lsqbiLRhbaTRlvBysOMXjlmMROrq6DFYrbSewYj7rFJDrVfKDpgFeOofmAN2hCNDUf8IqV9K6W5cr8o0lH+V498E016Ig4tGAOSTHrntCaIvIVrxumjp/uluVKHnGvPwUE/SyTsk+a7iwWggMEHXn2xeImtuliuYXvW9Asb567WrYejxgYq8MsadL3io5mWB8v4A1k0hOmXAsQnsnvLABBwwyLRQhNamhs13p4ZZWrN1OWegeq0W2x5IkC3Jk3XWCmlizXbBjE2rOEWEA0zdXqkdzyeAcAdhvLb4RrrMg7veN+J4bwyXi7ns4LSTI29HV7IVdmCRCbh9oMC0KNOQI18fCiK70IILnygLIXXM//EMA4rG5/4UDxRcpHe2SOF8KfifZIuILeCXcD5C8mC9AzPucDHQAAAABJRU5ErkJggg==">

                    </td>

                  </tr>

                  <tr>
                    <td>
                      <a id="reportLabel" href="#">Generate Reports</a>
                    </td>
                    <td>
                      <a id="infoLabel" href="#">View Information</a>
                    </td>
                    <td>
                      <a id="actionLabel" href="#">Perform Actions</a>
                    </td>
                  </tr>
                  </table>




                </div>


                <div id="theMenu" class="subMenu">
                  <ul>
                    <li><a href="#">Order Report</a></li>
                    <li><a href="#">Payment Report</a></li>
                    <li><a href="#">Expenditure Report</a></li>
                    <li><a href="#">Outstanding Balance Report</a></li>
                    <li><a href="#">Profit/Loss Report</a></li>
                  </ul>
                  <div style="clear: both;"></div>
                </div>

                <div id="theMenu2" class="subMenu" style="background-color: red;">
                  <ul>
                    <li><a href="#">Cutomer</a></li>
                    <li><a href="#">Tyre</a></li>
                    <li><a href="#">Consignments</a></li>
                    <li><a href="#">LCs</a></li>
                    <li><a href="#">Expenses</a></li>
                    <li><a href="#">Order</a></li>
                    <li><a href="#">Payment</a></li>
                    <li><a href="#">Stock</a></li>
                  </ul>
                  <div style="clear: both;"></div>
                </div>

                <div id="theMenu3" class="subMenu" style="background-color: green;">
                  <ul>
                    <li><a href="#">Add new LC</a></li>
                    <li><a href="#">Add new Performa Invoice</a></li>
                    <li><a href="#">Add new consignments</a></li>
                    <li><a href="#">Add new expense</a></li>

                    <li><a href="#">Create new Order</a></li>
                    <li><a href="#">Create new Payment Invoice</a></li>
                  </ul>
                  <div style="clear: both;"></div>
                </div>

            </div>
        </div>
        <div class="mainContent" style="width: 100%; height: 100px; background-color: #999;">


        </div>
    </body>
</html>
