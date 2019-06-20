
    <table class="table table-borderless text-center table-condensed">
        <thead>
            <th>Symbol</th>
            <th>Exchange Source</th>
            <th>Exchange Target</th>
        </thead>
        <tbody id="processes-container">
        @foreach($processes AS $process)
            <tr data-id="{{ $process->id }}" data-symbol="{{ $process->symbol }}"
                data-source="{{ $process->exchange_source }}" data-target="{{ $process->exchange_target }}"
                class="state-{{ $process->type_id }}">
                <td>{{ $process->symbol }}</td>
                <td>{{ $process->exchange_source }}</td>
                <td>{{ $process->exchange_target }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>