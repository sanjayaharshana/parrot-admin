<x-subscriptions::layouts.master>
    <h2>Your Invoices</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->date()->toDateString() }}</td>
                    <td>{{ $invoice->total() }}</td>
                    <td>{{ $invoice->status }}</td>
                    <td>
                        <a href="{{ route('subscriptions.invoices.download', $invoice->id) }}">Download</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No invoices yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-subscriptions::layouts.master>


