@extends('app')
@section('content')
    <h1 class="text-muted my-2">Selamat Datang di Dashboard</h1>
    <div class="row g-5 mb-5 mt-4">
                <div class="col-md-4">
                    <div class="card shadow p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <i class="bi bi-receipt" style="font-size: 2rem"></i>
                            </div>
                            <div>
                                <small class="text-muted">Today's Transaction</small>
                                <h4 class="mb-0 fw-bold">{{$todayTransactions}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <i class="bi bi-cart" style="font-size: 2rem"></i>
                            </div>
                            <div>
                                <small class="text-muted">Today's Sales</small>
                                <h4 class="mb-0 fw-bold">Rp. {{ number_format($todaySales, 0, ',', '.') }},-</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <i class="bi bi-box-seam" style="font-size: 2rem"></i>
                            </div>
                            <div>
                                <small class="text-muted">Product Sold</small>
                                <h4 class="mb-0 fw-bold">{{$productSold}}pcs</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
