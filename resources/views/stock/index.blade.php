@extends('app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Data Stok</h3>
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($products as $product)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $product->name }}
                            </td>

                            <td>
                                {{ $product->category->name ?? '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td>
                                <strong>
                                    {{ $product->stock }}
                                </strong>
                            </td>

                            <td>

                                @if($product->stock == 0)

                                    <span class="badge bg-danger">
                                        Habis
                                    </span>

                                @elseif($product->stock <= 5)

                                    <span class="badge bg-warning">
                                        Stok Menipis
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        Tersedia
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada produk.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection
