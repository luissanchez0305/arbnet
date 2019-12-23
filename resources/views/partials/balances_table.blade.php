    <div class="col-md-auto float-left"><span class="badge badge-success cursor" id="convert_currencies" display-currency="CRYPTO">Convert</span></div>
    <div class="col-md-auto float-left text-center">
        <input type="hidden" id="open_btc" value="{{ $earning_open_btc }}">
        <input type="hidden" id="close_btc" value="{{ $earning_close_btc }}">
        <input type="hidden" id="open_eth" value="{{ $earning_open_eth }}">
        <input type="hidden" id="close_eth" value="{{ $earning_close_eth }}">
        <table class="table table-striped table-bordered font-condensed">
            <tbody>
                <tr>
                    <th rowspan="2"><h5>BTC</h5></th>
                    <td>OPEN</td>
                    <td id="open_btc_container">{{ $earning_open_btc }} <h8>BTC</h8></td>
                    <th rowspan="2"><h5>ETH</h5></th>
                    <td>OPEN</td>
                    <td id="open_eth_container">{{ $earning_open_eth }} <h8>ETH</h8></td>
                </tr>
                <tr>
                    <td>CLOSED</td>
                    <td id="close_btc_container">{{ $earning_close_btc }} <h8>BTC</h8></td>
                    <td>CLOSED</td>
                    <td id="close_eth_container">{{ $earning_close_eth }} <h8>ETH</h8></td>
                </tr>
            </tbody>
        </table>
    </div>