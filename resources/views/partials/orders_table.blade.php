    {{ $symbol }} - {{ $exchange_source }} - {{ $exchange_target }}
    <table class="table table-striped table-borderless text-center font-condensed">
        <thead>
            <th>Price</th>
            <th>Qty</th>
            <th>Commission</th>
        </thead>
        <tbody>
        @foreach($orders AS $order)
            <tr class="state-{{ $order->status_id }}">
                <td>{{ $order->price }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ $order->commission }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>