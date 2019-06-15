<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Arbitrage</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
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
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .level_1{
                color: #0035ff;
            }
            .level_2{
                color: #50bb4a;                
            }
            .level_3{
                color: #c3b600;                
            }
            .level_4{
                color: #ff8707;                
            }
            .level_5{
                color: #ff0000;                
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                @if($last_updated)
                <labe>Last updated: {{$last_updated->date}}</labe>
                @endif
                <table class="table table-bordered table-hover table-sm">
                  <thead>
                    <tr>
                      <th scope="col">&nbsp;</th>
                      @foreach($exchanges as $exchange)
                      <th scope="col">{{$exchange}}</th>
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($rows as $row)
                    <tr>
                      <th scope="row">{{$row['exchange']}}</th>
                      @foreach($row['items'] as $item)
                      <td style="{{ $row['exchange'] == $item['exchange'] ? "background-color:gray;" : ""}}">
                        @foreach($item['pairs'] as $pair)
                        <label data-toggle="tooltip" title="{{ $pair['volume_source'] . ' - ' . $pair['volume_target'] }}" style="margin:0;" class="level_{{ $pair['volume_target_level'] }}">{{ $pair['margin'] > 200 ? "**" : "" }} {{$pair['pair']}} - {{$pair['margin']}}% {{ $pair['margin'] > 200 ? "**" : "" }}</label><br/>
                        @endforeach
                      </td>
                      @endforeach
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
      setInterval(function(){
        location.reload();
      }, 120000)
    });
    </script>
</html>
