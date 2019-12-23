<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Arbitrage</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

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
            .nomargin {
                margin: 0;
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
            .font-condensed{
              font-size: 0.75rem;
            }
            h7{
                font-size: 0.7rem;
            }
            #balances > table > tbody > tr > th, #balances > table > tbody > tr > td {
                 vertical-align: middle;
            }
            .bd-order-modal-sm .custom-radio{
                margin: 0 0.5rem 0 0;
            }
            #balances{
                margin: 0 2rem 0 0;
            }
            .hide{
                display: none !important;
            }
            .cursor{
                cursor: pointer;
            }
            .circles-text{
                color: transparent;
            }
            .selected{
                background-color: rgba(50, 115, 220, 0.3);
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
                <div class="row">
                    <div class="col-lg-6">
                        @if($last_updated)
                        <label class="float-lg-left">Last updated: {{ $last_updated->date }}</label>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="row position-absolute hide" id="balances" style="right: 0;">

                        </div>
                        <div class="row float-lg-right">
                            <div class="col-md-auto cursor">
                                <i class="material-icons" id="show-earnings"> attach_money </i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-1">
                        &nbsp;
                    </div>
                    <div class="col-lg-11">
                        <div class="row">
                            <div class="col-lg-auto">
                                <ul id="cols-group" class="list-group list-group-horizontal" style="text-align: left;  padding: 0 0 15px 0;">
                                  @foreach($exchanges as $exchange)
                                  <li class="wipevals list-group-item" val="col_{{ $exchange }}" show="on">{{ $exchange }}</li>
                                  @endforeach
                                </ul>
                            </div>
                            <div class="col-lg-2" style="text-align: left;">
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
                    <div class="col-md-11 padding-l0">
                        <table class="table table-bordered table-hover table-sm font-condensed">
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
                                <label class="cursor" data-toggle="modal" data-target=".bd-order-modal-sm" class="{{ strpos($pair['pair'],'ETH') ? 'eth' : (strpos($pair['pair'],'BTC') ? 'btc' : '') }}" data-toggle="tooltip" title="{{ $pair['volume_source'] . ' - ' . $pair['volume_target'] }}" data-pair="{{ $pair['pair'] }}" data-exchange-source="{{ $row['exchange'] }}" data-exchange-target="{{ $item['exchange'] }}" data-source-volume="{{ $pair['volume_source'] }}" data-target-volume="{{ $pair['volume_target'] }}" style="margin:0;">{{ str_replace(',','', $pair['margin']) > 200 ? "*" : "" }} {{ $pair['pair'] }} - {{ $pair['margin']}}% {{ str_replace(',','', $pair['margin']) > 200 ? "*" : "" }}</label><br/>
                                <!--div class="cursor level_bar level_{{ $pair['volume_source_level'] }} {{ strpos($pair['pair'],'ETH') ? 'eth' : (strpos($pair['pair'],'BTC') ? 'btc' : '') }}">{{ $pair['date'] }}</div>
                                <div class="cursor level_bar level_{{ $pair['volume_target_level'] }} {{ strpos($pair['pair'],'ETH') ? 'eth' : (strpos($pair['pair'],'BTC') ? 'btc' : '') }}">&nbsp;</div-->
                                <div style="background-color: #d3d3d3; color: black;">{{ $pair['date'] }}</div>
                                @endforeach
                              </td>
                              @endforeach
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
                <div class="row nomargin justify-content-center align-items-center hide">
                    <div class="col-lg-auto">
                        <div class="row">
                            <div class="col-lg-10">
                                <h3>Processes &amp; Orders</h3>
                            </div>
                            <div class="col-lg-2">
                                <select class="form-control" id="order_type" onchange="select_order_type(this)">
                                    <option value="-1">All</option>
                                    <option value="1">Open</option>
                                    <option value="2">Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" id="processes">

                        </div>
                    </div>
                    <div class="col-md-auto padding-r0">
                        &nbsp;
                    </div>
                    <div class="col-lg-3">
                        <h3>&nbsp;</h3>
                        <div class="row" id="orders">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-order-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="order_form">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bold content">Create an order</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                          <div class="row">
                              <div class="col" style="text-align: center;">
                                You are about to create a <b>pre-order</b> to process and transfer <h5 id="modal-pair-container" class="font-weight-bold"></h5>
                                <div class="row">
                                  <div class="col-8 col-sm-3 font-weight-bold">
                                    From: <h5 id="modal-exchange-source-container"></h5>
                                    <h7 id="modal-volume-source-container"></h7>
                                  </div>
                                  <div class="col-8 col-sm-6 my-auto">
                                    <div class="row" style="text-align: center;">
                                        Available
                                        <label style="padding: 0 0 0 10px;" id="modal-available-container" data-balance=""></label>
                                    </div>
                                    <div class="row font-condensed" id="balance-portion-container">
                                        <div class="custom-control custom-radio custom-control-inline">
                                          <input class="custom-control-input" type="radio" name="percent-radios" id="percent-25" value="0.25">
                                          <label class="custom-control-label" for="percent-25">
                                            25%
                                          </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                          <input class="custom-control-input" type="radio" name="percent-radios" id="percent-50" value="0.50">
                                          <label class="custom-control-label" for="percent-50">
                                            50%
                                          </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                          <input class="custom-control-input" type="radio" name="percent-radios" id="percent-75" value="0.75">
                                          <label class="custom-control-label" for="percent-75">
                                            75%
                                          </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                          <input class="custom-control-input" type="radio" name="percent-radios" id="percent-100" value="1">
                                          <label class="custom-control-label" for="percent-100">
                                            100%
                                          </label>
                                        </div>
                                    </div>
                                    <div class="row my-auto">
                                          <div class="form-group">
                                            <input type="number" class="form-control" id="qty" min="0" max="" placeholder="Quantity" required />
                                          </div>
                                    </div>
                                  </div>
                                  <div class="col-4 col-sm-3 font-weight-bold">
                                    To: <h5 id="modal-exchange-target-container"></h5>
                                    <h7 id="modal-volume-target-container"></h7>
                                  </div>
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="order_submit_btn">Start process</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script src="js/circles.min.js" type="text/javascript"></script>
    <script>
    $(document).ready(function(){
      setInterval(function(){
        if(!$('.bd-order-modal-sm').hasClass('show'))
            location.reload();
      }, 120000);
      $('body').on('click','#show-earnings', function(){
        $balances = $('#balances');
        if($balances.hasClass('hide'))
            $balances.removeClass('hide');
        else
            $balances.addClass('hide');
      });
      $('body').on('click', '#convert_currencies', function(){
          $this = $(this);
          $.get('{{ route("dashboard.convert_currency") }}', { _token: '{{ csrf_token() }}' }, function(data){
            data = $.parseJSON(data);
            if($this.attr('display-currency') == 'CRYPTO'){
                let postfix = ' <h8>USD</h8>';
                $('#open_btc_container').html('$' + (data.btc * $('#open_btc').val()).toFixed(2) + postfix);
                $('#close_btc_container').html('$' + (data.btc * $('#close_btc').val()).toFixed(2) + postfix);
                $('#open_eth_container').html('$' + (data.eth * $('#open_eth').val()).toFixed(2) + postfix);
                $('#close_eth_container').html('$' + (data.eth * $('#close_eth').val()).toFixed(2) + postfix);
                $this.attr('display-currency','USD');
            }
            else{
              $.get('{{ route("dashboard.get_balances") }}', { _token: '{{ csrf_token() }}' }, function(data){
                $('#balances').html(data);
              });
              $this.attr('display-currency','CRYPTO');
            }
          });
      });
      $.get('{{ route("dashboard.get_balances") }}', { _token: '{{ csrf_token() }}' }, function(data){
        $('#balances').html(data);
      });
      $.get('{{ route("dashboard.get_processes") }}', { _token: '{{ csrf_token() }}', type_id: '-1' }, function(data){
        $('#processes').html(data);
        $('.circle').each(function(index,obj){
            $obj = $(obj);
            Circles.create({
                id:           $obj.attr('id'),
                value:        $obj.attr('data-progress'),
                radius:       10,
                width:        10,
                duration:     100,
                colors:       ['#e6e6e6', '#29bb51']
            });
        });
      });
      $('body').on('click','#processes-container tr',function(){
        $('#orders').html('');
        $('#processes tr').removeClass('selected');
        $this = $(this);
        $this.addClass('selected');
        $.get('{{ route("dashboard.get_orders") }}', { _token: '{{ csrf_token() }}', process_id: $this.attr('data-id'),
            symbol: $this.attr('data-symbol'), source: $this.attr('data-source'), target: $this.attr('data-target') },
            function(data){
                $('#orders').html(data);
        });
      });

      $('body').on('click', '.eth,.btc', function(){
        let $this = $(this);
        $('#modal-pair-container').html($this.attr('data-pair'));
        $('#modal-exchange-source-container').html($this.attr('data-exchange-source'));
        $('#modal-exchange-target-container').html($this.attr('data-exchange-target'));
        $('#modal-volume-source-container').html('Volume: ' + $this.attr('data-source-volume'));
        $('#modal-volume-target-container').html('Volume: ' + $this.attr('data-target-volume'));
        $('#modal-available-container').html('');
        $.get('{{ route("dashboard.get_user_balance") }}', { _token: '{{ csrf_token() }}', user_id: 1 }, function(data){
            data = $.parseJSON(data);

            if($this.attr('data-pair').indexOf('ETH') > -1){
                $('#qty').attr('max', data.balance_eth);
                $('#modal-available-container').attr('data-balance', data.balance_eth).html(data.balance_eth + ' ETH');
            }
            else{
                $('#qty').attr('max', data.balance_btc);
                $('#modal-available-container').attr('data-balance', data.balance_btc).html(data.balance_btc + ' BTC');
            }
        });
      });

      var form = $( "#order_form" );
      form.validate();
      $( "#order_submit_btn" ).click(function() {
            console.log("Valid: " + form.valid() );
      });

      $('body').on('click', '#balance-portion-container input', function(){
        $('#qty').val($('#modal-available-container').attr('data-balance') * $(this).val());
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
        $obj = $(obj);
        $('#orders').html('');
        $.get('{{ route("dashboard.get_processes") }}', { _token: '{{ csrf_token() }}', type_id: $obj.val() }, function(data){
            $('#processes').html(data);
            $('.circle').each(function(index,circle){
                $circle = $(circle);
                Circles.create({
                    id:           $circle.attr('id'),
                    value:        $circle.attr('data-progress'),
                    radius:       10,
                    width:        10,
                    duration:     100,
                    colors:       ['#e6e6e6', '#29bb51']
                });
            });
        });
    }
    </script>
</html>
