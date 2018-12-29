<!doctype html>
<html lang="en">
    <head>
        <title>
            Patient Appointments System
        </title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-animate.js"></script>
        <!--paging for view all patients-->
        <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
        @include('layouts.style_css')
        <script>
            var index = 'true';
            var patientrecord = '';
            var appointmentrecord = '';
            var date_of = '';
        </script>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="nav navbar-nav">
                    <li><a href="{{ route('home') }}" ><span class="glyphicon glyphicon-home"><span class="icon-margin-right"></span>Home</span></a></li>
                    <li class="dropdown">
                        <span class="dropdown-toggle"></span>
                        <a data-toggle="dropdown" href="{{ route('appointments', ['appointment_id'=>'1', 'patient_id'=>'1', 'type'=>'create', 'searchdate'=>'1', 'loadfrom1'=>'0']) }}"><span class="glyphicon glyphicon-list-alt"><span class="icon-margin-right"></span>Appointments</span><span class="caret"></span></a>
                        <ol class="dropdown-menu">
                            <li><a href="{{ route('appointments', ['appointment_id'=>'1', 'patient_id'=>'1', 'type'=>'create', 'searchdate'=>'1', 'loadfrom1'=>'0']) }}"><span class="icon-margin-right"></span>Appointment Calendar</a></li>
                            <li><a href="{{ route('appointments.all') }}" >Show all Appointments</a></li>
                        </ol>
                    </li>
                    <li><a href="{{ route('patients') }}" ><span class="glyphicon glyphicon-user"><span class="icon-margin-right"></span>Patients</span></a></li>
                    <li><a href="{{ route('dentists.index') }}" ><span class="glyphicon glyphicon-zoom-in"><span class="icon-margin-right"></span>Dentists</span></a></li>
                </div>
                <div class="nav navbar-nav navbar-right">
                    <li>
                        <?php if (Auth::check()) { ?><a href="{{ route('get.logout') }}"><span class="glyphicon glyphicon-log-in"><span class="icon-margin-right-3"></span>Logout</span></a><?php }else{ ?>
                        <a href="{{ route('get.login') }}"><span class="glyphicon glyphicon-log-in"><span class="icon-margin-right-3"></span>Login</span></a><?php } ?>
                    </li>
                    {{--
                    <li><a href="{{ route('get.register') }}">Register </a></li>
                    --}}
                    <?php 
                        if (Auth::check()) {
                            $user = Auth::user();
                            echo "<li><b>Logged in: ".$user->name."&nbsp;</b></li>";
                        }else{
                            echo "<li><b>Not logged in&nbsp;</b></li>";
                        }
                         ?>
                </div>
            </div>
        </nav>
        <main>
            <div class="container">
                @yield('content')
            </div>
        </main>
    </body>
    <footer>
        @include('layouts.master_javascript')
    </footer>
</html>