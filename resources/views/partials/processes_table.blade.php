
    <table class="table table-bordered table-hover text-center font-condensed">
        <thead>
            <th>Progress</th>
            <th>Symbol</th>
            <th>Exchange Source</th>
            <th>Exchange Target</th>
            <th>My Order Price</th>
            <th>Desired Margin</th>
            <th>Price</th>
            <th>Current Margin</th>
            <th>Earning</th>
            <th>Date Created</th>
            <th>Date Updated</th>
        </thead>
        <tbody id="processes-container">
        @foreach($processes AS $process)
            <tr data-id="{{ $process['id'] }}" data-symbol="{{ $process['symbol'] }}"
                data-source="{{ $process['exchange_source'] }}" data-target="{{ $process['exchange_target'] }}"
                class="cursor">
                <td><div class="circle" id="circles-{{ $process['id'] }}" data-progress="{{ $process['progress'] }}" data-max="{{ $process['max'] }}"></div>
                <td>{{ $process['symbol'] }}</td>
                <td>{{ $process['exchange_source'] }}</td>
                <td>{{ $process['exchange_target'] }}</td>
                <td>{{ $process['order_target_price'] }}</td>
                <td>{{ $process['process_margin'] }}</td>
                <td>{{ $process['exchange_target_price'] }}</td>
                <td>{{ $process['current_margin'] }}</td>
                <td>{{ $process['earning'] }}%</td>
                <td>{{ $process['created_date'] }}</td>
                <td>{{ $process['updated_date'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>