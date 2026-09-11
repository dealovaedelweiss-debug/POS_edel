@extends('app')

@section('content')
<div class="container-fluid p-4">
    <h3 class="fw-bold mb-4">Laporan Transaksi</h3>

    <!-- Form Filter Tanggal -->
    <form action="{{ route('report.index') }}" method="GET" class="card shadow-sm border-0 p-4 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-semibold">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-semibold">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </div>
    </form>

    <!-- Ringkasan Card -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
                <span class="text-muted">Total Transaksi</span>
                <h3 class="fw-bold">{{ $totalTransaksi ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
                <span class="text-muted">Total Pendapatan</span>
                <h3 class="fw-bold">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Tabel Data Transaksi -->
    <div class="card shadow-sm border-0 p-3">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Order / Invoice</th>
                        <th>Total Harga</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $index => $trx)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $trx->order_number ?? 'INV/' . $trx->id }}</td>
                            <td>Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                            <td><span class="badge bg-success text-uppercase">{{ $trx->payment_method }}</span></td>
                            <td><span class="badge bg-info">Success</span></td>
                            <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
