@extends('app')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">
        Laporan Transaksi
    </h3>


    {{-- FILTER TANGGAL --}}
    <div class="card mb-4">

        <div class="card-body">

            <form action="{{ route('report.index') }}" method="GET">

                <div class="row">

                    <div class="col-md-4">

                        <label>
                            Dari Tanggal
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ $startDate }}"
                        >

                    </div>


                    <div class="col-md-4">

                        <label>
                            Sampai Tanggal
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ $endDate }}"
                        >

                    </div>


                    <div class="col-md-4 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Tampilkan
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- RINGKASAN --}}

    <div class="row mb-4">

        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <h6>
                        Total Transaksi
                    </h6>

                    <h3>
                        {{ $totalTransactions }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <h6>
                        Total Pendapatan
                    </h6>

                    <h3>
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- TABEL TRANSAKSI --}}

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>No Order</th>

                            <th>Total Harga</th>

                            <th>Metode Pembayaran</th>

                            <th>Status</th>

                            <th>Tanggal</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($orders as $order)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>
                                {{ $order->order_number }}
                            </td>


                            <td>
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>


                            <td>

                                @if($order->payment_method == 0)

                                    Cash

                                @elseif($order->payment_method == 1)

                                    Midtrans

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @if($order->payment_status == 0)

                                    <span class="badge bg-warning">
                                        Pending
                                    </span>

                                @elseif($order->payment_status == 1)

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif($order->payment_status == 2)

                                    <span class="badge bg-danger">
                                        Failed
                                    </span>

                                @elseif($order->payment_status == 3)

                                    <span class="badge bg-secondary">
                                        Cancelled
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center"
                            >
                                Belum ada transaksi.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
