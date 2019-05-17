@extends('layouts.app')

@section('content')
    <div id="print">
    <h2>@lang('dictionary.statistics_text')</h2>
    <hr>

    <div class="row">


        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: #1b1e21;">
            <a href="{{ url('/departments') }}" style="text-decoration: none;color: #1b1e21;">
                <div class="card" style="border: 1px solid #2a2a2a;">
                    <div class="card-body" style="text-align: center; padding: 0;">
                        <h1>{{ $departments->count() }}</h1>
                    </div>
                    <div class="card-footer" style="text-align: center"><b>@lang('dictionary.total_departments_text')</b></div>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: #1b1e21;">
            <a href="{{ url('/doctypes') }}" style="text-decoration: none;color: #1b1e21;">
                <div class="card" style="border: 1px solid #2a2a2a;">
                    <div class="card-body" style="text-align: center; padding: 0;">
                        <h1>{{ $doctypes->count() }}</h1>
                    </div>
                    <div class="card-footer" style="text-align: center"><b>@lang('dictionary.total_doctypes_text')</b></div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: #1b1e21;">
            <a href="{{ url('/documents') }}" style="text-decoration: none;color: #1b1e21;">
                <div class="card" style="border: 1px solid #2a2a2a;">
                    <div class="card-body" style="text-align: center; padding: 0;">
                        <h1>{{ $docs_number }}</h1>
                    </div>
                    <div class="card-footer" style="text-align: center"><b>@lang('dictionary.total_documents_text')</b></div>
                </div>
            </a>
        </div>

    </div>

    <br>
    <div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <div id="chart_doctypes"></div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <div id="chart_departments"></div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <div id="chart_doctypes_departs"></div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <div id="chart_files"></div>
    </div>
    <div class="col-12">
        <div id="chart_users"></div>
    </div>
    </div>
    </div>
    <br>
    <br>
    <div class="col-12" style="text-align: center">
        <button type="button" class="btn btn-outline-info" onclick="printDiv()">@lang("dictionary.click_to_print_button")</button>
    </div>

    <br><br>
    <br><br>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>

        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(docs_per_type);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        @if($doctypes->count() > 0)
        function docs_per_type() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');
            data.addRows([
                @foreach($doctypes as $doctype)
                    ['{{ $doctype->name }}', {{ $doctype->getNumberOfDocuments() }}],
                @endforeach
            ]);

            // Set chart options
            var options = {'title':'@lang("dictionary.documents_per_doctypes_chart_title")',
                'height':300};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_doctypes'));
            chart.draw(data, options);
        }

        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(doctypes_per_department);
        function doctypes_per_department() {
            var data = google.visualization.arrayToDataTable([
                ['@lang("dictionary.department_name_chart_label")', '@lang("dictionary.number_of_doctype_chart_label")'],
                    @foreach($departments as $department)
                ['{{ $department->name }}', {{ $department->getNumberOfDoctypes() }}],
                @endforeach
            ]);

            var options = {
                title: '@lang("dictionary.doctypes_per_departments_chart_title")',
                //pieHole: 0.3,
                height:300
            };

            var chart = new google.visualization.PieChart(document.getElementById('chart_doctypes_departs'));
            chart.draw(data, options);
        }

        @endif

        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(docs_per_user);

        function docs_per_user() {

            var data = new google.visualization.DataTable();
            data.addColumn('string', '@lang("dictionary.user_chart_label")');
            data.addColumn('number', '@lang("dictionary.number_of_docs_chart_label")');

            data.addRows([
                    @foreach($users as $user)
                        ['{{ $user->name }}', {{ $user->getNumberOfDocuments() }}],
                    @endforeach
            ]);

            var options = {
                title: '@lang("dictionary.documents_per_user_chart_title")',
                hAxis: {
                    title: '@lang("dictionary.user_chart_label")',
                },
                vAxis: {
                    title: '@lang("dictionary.number_of_docs_chart_label")'
                },
                height: 400,
                legend: {position: 'none'}
            };

            var chart = new google.visualization.ColumnChart(
                document.getElementById('chart_users'));

            chart.draw(data, options);
        }

        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(docs_per_department);
        function docs_per_department() {
            var data = google.visualization.arrayToDataTable([
                ['@lang("dictionary.department_name_chart_label")', '@lang("dictionary.number_of_docs_chart_label")'],
                @foreach($departments as $department)
                    ['{{ $department->name }}', {{ $department->getNumberOfDocuments() }}],
                @endforeach
            ]);

            var options = {
                title: '@lang("dictionary.documents_per_departments_chart_title")',
                //pieHole: 0.3,
                //width:$(window).width()-100+'pt',
                height:300
            };

            var chart = new google.visualization.PieChart(document.getElementById('chart_departments'));
            chart.draw(data, options);
        }

        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(docs_with_files);

        function docs_with_files() {

            var data = new google.visualization.DataTable();
            data.addColumn('string', '@lang("dictionary.department_chart_label")');
            data.addColumn('number');

            data.addRows([
                    @foreach($doctypes as $doctype)
                ['{{ $doctype->name }}', {{ $doctype->getPercentOfDocsWithFileAttached() }}],
                @endforeach
            ]);

            var options = {
                title: '@lang("dictionary.percent_documents_files_per_type_chart_title")',
                hAxis: {
                    title: '@lang("dictionary.doctype_chart_label")',
                },
                vAxis: {
                    title: '@lang("dictionary.percent_of_docs_chart_label")'
                },
                height: 300,
                legend: {position: 'none'}
            };

            var chart = new google.visualization.ColumnChart(
                document.getElementById('chart_files'));

            chart.draw(data, options);
        }

        function printDiv()
        {

            var divToPrint=document.getElementById('print');

            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write('<html><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"><body onload="window.print()" style="padding: 20px">'+divToPrint.innerHTML+'</body></html>');

            newWin.document.close();

            setTimeout(function(){newWin.close();},10);

        }

    </script>

@stop