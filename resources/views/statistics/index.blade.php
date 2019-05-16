@extends('layouts.app')

@section('content')
    <h2>@lang('dictionary.statistics_text')</h2>
    <hr>

    <div class="row">


        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: #1b1e21;">
            <a href="{{ url('/departments') }}" style="text-decoration: none;color: #1b1e21;">
                <div class="card" style="border: 1px solid #2a2a2a;">
                    <div class="card-body" style="text-align: center; padding: 0;">
                        <h1>{{ $departments_number }}</h1>
                    </div>
                    <div class="card-footer" style="text-align: center"><b>@lang('dictionary.total_departments_text')</b></div>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: #1b1e21;">
            <a href="{{ url('/doctypes') }}" style="text-decoration: none;color: #1b1e21;">
                <div class="card" style="border: 1px solid #2a2a2a;">
                    <div class="card-body" style="text-align: center; padding: 0;">
                        <h1>{{ $doctypes_number }}</h1>
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

    <div id="chart_div"></div>


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>

        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        @if($doctypes->count() > 0)
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');
            data.addRows([
                @foreach($doctypes as $doctype)
                    ['{{ $doctype->name }}', {{ $doctype->getDocumentsNumber() }}],
                @endforeach
            ]);

            // Set chart options
            var options = {'title':'@lang("dictionary.documents_per_doctypes_chart_title")',
                'width':$(window).width()-200+'pt',
                'height':300};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
        @endif

    </script>

@stop