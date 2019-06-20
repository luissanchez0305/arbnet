<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Arbitrage</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

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

            .padding-r0{
                padding-right:0;
            }
            .padding-l0{
                padding-left:0;
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
            .level_0{
                background-color: rgba(255,255,255,0);
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
            .state-1{
                background-color: rgba(255,135,7,0.5) !important;
            }
            .state-2{
                background-color: rgba(195,182,0,0.5) !important;
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
            .wipevals[show="on"]{
                background-color: white;
            }
            .wipevals[show="off"]{
                background-color: gray;
                color: white;
            }
            .table-condensed{
              font-size: 0.75rem;
            }
            #balances > table > tbody > tr > th, #balances > table > tbody > tr > td {
                 vertical-align: middle;
            }
            @media only screen and (max-device-width : 640px) {
                .list-group{
                    display: none;
                }
            }

            @media only screen and (max-device-width: 768px) {
                .list-group{
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="position-ref">
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
                <label>Last updated: {{ $last_updated->date }}</label>
                @endif
                    <div class="row">
                        <div class="col-lg-1">
                            &nbsp;
                        </div>
                        <div class="col">
                            <ul id="cols-group" class="list-group list-group-horizontal" style="text-align: left;  padding: 0 0 15px 0;">
                              @foreach($exchanges as $exchange)
                              <li class="wipevals list-group-item" val="col_{{ $exchange }}" show="on">{{ $exchange }}</li>
                              @endforeach
                            </ul>
                        </div>
                        <div class="col col-lg-2">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" checked="checked" class="form-check-input quote_select" id="quote_select_btc" value="btc" />
                                <label class="form-check-label" for="quote_select_btc">BTC</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" checked="checked" class="form-check-input quote_select" id="quote_select_eth" value="eth" />
                                <label class="form-check-label" for="quote_select_eth">ETH</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-auto padding-r0">
                            <ul id="rows-group" class="list-group"  style="width: 130px; float: left; padding: 0 10px 0 0;">
                              @foreach($exchanges as $exchange)
                              <li class="wipevals list-group-item" val="row_{{ $exchange }}" show="on">{{ $exchange }}</li>
                              @endforeach
                            </ul>
                        </div>
                        <div class="col-md-8 padding-l0">
                            <table class="table table-bordered table-hover table-sm table-condensed">
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
                                  <th scope="row" class="row_{{ $row['exchange'] }}" show="on">{{ $row['exchange'] }}</th>
                                  @foreach($row['items'] as $item)
                                  <td class="col_{{ $item['exchange'] }} row_{{ $row['exchange'] }}" style="{{ $row['exchange'] == $item['exchange'] ? "background-color:gray;" : "" }}">
                                    @foreach($item['pairs'] as $pair)
                                    <label class="{{ strpos($pair['pair'],'ETH') ? 'eth' : (strpos($pair['pair'],'BTC') ? 'btc' : '') }}" data-toggle="tooltip" title="{{ $pair['volume_source'] . ' - ' . $pair['volume_target'] }}" style="margin:0;">{{ str_replace(',','', $pair['margin']) > 200 ? "*" : "" }} {{ $pair['pair'] }} - {{ $pair['margin']}}% {{ str_replace(',','', $pair['margin']) > 200 ? "*" : "" }}</label><br/>
                                    <div class="level_bar level_{{ $pair['volume_source_level'] }} {{ strpos($pair['pair'],'ETH') ? 'eth' : (strpos($pair['pair'],'BTC') ? 'btc' : '') }}">&nbsp;</div>
                                    <div class="level_bar level_{{ $pair['volume_target_level'] }} {{ strpos($pair['pair'],'ETH') ? 'eth' : (strpos($pair['pair'],'BTC') ? 'btc' : '') }}">&nbsp;</div>
                                    @endforeach
                                  </td>
                                  @endforeach
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                        </div>
                        <div class="col-lg-3">
                            <h3>Earnings</h3>
                            <div class="row" id="balances">

                            </div>
                            <h3>Processes &amp; Orders</h3>
                            <select class="form-control" id="order_type" onchange="select_order_type(this)">
                                <option value="-1">All</option>
                                <option value="1" class="state-1">Open</option>
                                <option value="2" class="state-2">Closed</option>
                            </select>
                            <div class="row" id="processes">

                            </div>
                            <div class="row" id="orders">
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function(){
      setInterval(function(){
        location.reload();
      }, 120000);
      $.get('{{ route("dashboard.get_balances") }}', { _token: '{{ csrf_token() }}' }, function(data){
        $('#balances').html(data);
      });
      $.get('{{ route("dashboard.get_processes") }}', { _token: '{{ csrf_token() }}', type_id: '-1' }, function(data){
        $('#processes').html(data);
      });
      $('body').on('click','#processes-container tr',function(){
        $.get('{{ route("dashboard.get_orders") }}', { _token: '{{ csrf_token() }}', process_id: $(this).attr('data-id'),
            symbol: $(this).attr('data-symbol'), source: $(this).attr('data-source'), target: $(this).attr('data-target') },
            function(data){
                $('#orders').html(data);
        });
      });

      $('[data-toggle="tooltip"]').tooltip();
      $('body').on('click', '.quote_select', function(){
        if($('.quote_select').is(':checked')){
            $('.quote_select').each(function(index, obj){
                let $obj = $(obj);
                if($obj.is(':checked'))
                    $('.' + $obj.val()).show();
                else
                    $('.' + $obj.val()).hide();
            });

            let quotes_off = '';
            $('.quote_select').each(function(index, obj){
                let $obj = $(obj);
                if(!$obj.is(':checked'))
                    quotes_off += $obj.val() + ',';
            });
            localStorage.setItem('quotes', quotes_off);
        }
        else
            return false;
      });
      $('body').on('click', '.wipevals', function(){
            let $this = $(this);
            let val = $this.attr('val');
            let direction = val.split('_');
            if($this.attr('show') == 'on'){
                $('.' + val).hide();
                $this.attr('show','off');
            }
            else {
                $('.' + val).each(function(index, obj1){
                    $(obj1).show();

                    // al mostrar reactivar la column fijarse si la celda de la row de ese exchange se esta mostrando o no
                    if(direction[0] == 'col'){
                        $('#rows-group li').each(function(index2, obj2){
                            if($('ul#rows-group li[val="row_' + $(obj2).html() + '"]').attr('show') == 'off')
                                $('.row_' + $(obj2).html()).hide();
                        });
                    }
                    // al mostrar reactivar la row fijarse si la celda de la column de ese exchange se esta mostrando o no
                    else{
                        $('#cols-group li').each(function(index3, obj3){
                            if($('ul#cols-group li[val="col_' + $(obj3).html() + '"]').attr('show') == 'off')
                                $('.col_' + $(obj3).html()).hide();

                        });
                    }
                });
                $this.attr('show','on');
            }
            // recorrer todas las rows y las colums para actualizar el localstorage
            if(direction[0] == 'row'){
                let rows_off = '';
                $('#rows-group li').each(function(index, obj){
                    let $obj = $(obj);
                    if($obj.attr('show') == 'off'){
                        rows_off += $obj.attr('val').split('_')[1] + ',';
                    }
                });
                localStorage.setItem('rows', rows_off);
                localStorage.getItem('rows');
            }
            else{
                let cols_off = '';
                $('#cols-group li').each(function(index, obj){
                    let $obj = $(obj);
                    if($obj.attr('show') == 'off')
                        cols_off += $obj.attr('val').split('_')[1] + ',';
                });
                localStorage.setItem('cols', cols_off);
                localStorage.getItem('cols');
            }
      });

      if(localStorage.getItem('rows')){
        let rows = localStorage.getItem('rows');
        let rows_array = rows.substring(0, rows.length - 1).split(',');
        for(let i = 0; i < rows_array.length; i++){
            let row = rows_array[i];
            $('li[val="row_' + row + '"]').click();
        }
      }

      if(localStorage.getItem('cols')){
        let cols = localStorage.getItem('cols');
        let cols_array = cols.substring(0, cols.length - 1).split(',');
        for(let i = 0; i < cols_array.length; i++){
            let col = cols_array[i];
            $('li[val="col_' + col + '"]').click();
        }
      }

      if(localStorage.getItem('quotes')){
        let quote = localStorage.getItem('quotes')
        quote = quote.substring(0, quote.length - 1).split(',');
        $('.quote_select[value="'+quote+'"]').click();
      }
    });
    function select_order_type(obj){
        $('#orders').html('');
        $.get('{{ route("dashboard.get_processes") }}', { _token: '{{ csrf_token() }}', type_id: $(obj).val() }, function(data){
            $('#processes').html(data);
        });
    }
    </script>
</html>
