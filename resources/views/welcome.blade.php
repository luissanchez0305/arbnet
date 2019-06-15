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
                background-color: rgba(0,53,255,0.5);

            }
            .level_2{
                background-color: rgba(80,187,74,0.5);                
            }
            .level_3{
                background-color: rgba(195,182,0,0.5);                
            }
            .level_4{
                background-color: rgba(255,135,7,0.5);                
            }
            .level_5{
                background-color: rgba(255,0,0,0.5);                
            }
            .level_bar{
                width: 49%; height: 5px; float: left;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .list-group-horizontal .list-group-item
            {
                display: inline-block;
            }
            .list-group-horizontal .list-group-item
            {
                margin-bottom: 0;
                margin-left:-4px;
                margin-right: 0;
                border-right-width: 0;
            }
            .list-group-horizontal .list-group-item:first-child
            {
                border-top-right-radius:0;
                border-bottom-left-radius:4px;
            }
            .list-group-horizontal .list-group-item:last-child
            {
                border-top-right-radius:4px;
                border-bottom-left-radius:0;
                border-right-width: 1px;
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

            <div style="width: 5%; top: 81px; position: fixed; left: 11%;">
                <ul class="list-group">
                  @foreach($exchanges as $exchange)
                  <li class="wipevals list-group-item" val="row_{{ $exchange }}" show="on">{{$exchange}}</li>
                  @endforeach
                </ul>
            </div>
            <div class="content">
                @if($last_updated)
                <labe>Last updated: {{$last_updated->date}}</labe>
                @endif
                <ul class="list-group list-group-horizontal">
                  @foreach($exchanges as $exchange)
                  <li class="wipevals list-group-item" val="col_{{ $exchange }}" show="on">{{$exchange}}</li>
                  @endforeach
                </ul>
                <table class="table table-bordered table-hover table-sm">
                  <thead>
                    <tr>
                      <th scope="col">&nbsp;</th>
                      @foreach($exchanges as $exchange)
                      <th scope="col" class="col_{{ $exchange }}" show="on">{{$exchange}}</th>
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($rows as $row)
                    <tr>
                      <th scope="row" class="row_{{ $row['exchange'] }}" show="on">{{$row['exchange']}}</th>
                      @foreach($row['items'] as $item)
                      <td class="col_{{ $item['exchange'] }} row_{{ $row['exchange'] }}" style="{{ $row['exchange'] == $item['exchange'] ? "background-color:gray;" : ""}}">
                        @foreach($item['pairs'] as $pair)
                        <label data-toggle="tooltip" title="{{ $pair['volume_source'] . ' - ' . $pair['volume_target'] }}" style="margin:0;">{{ $pair['margin'] > 200 ? "**" : "" }} {{$pair['pair']}} - {{$pair['margin']}}% {{ $pair['margin'] > 200 ? "**" : "" }}</label><br/>
                        <div class="level_bar level_{{ $pair['volume_source_level'] }}">&nbsp;</div>
                        <div class="level_bar level_{{ $pair['volume_target_level'] }}">&nbsp;</div>
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
      /*setInterval(function(){
        location.reload();
      }, 120000);*/
      $('body').on('click', '.wipevals', function(){
        let $this = $(this);
        if($this.attr('show') == 'on'){
            $('.' + $this.attr('val')).hide();
            $this.attr('show','off');
        }
        else {
            $('.' + $this.attr('val')).show();
            $this.attr('show','on');
        }
      });
    });
    </script>
</html>
