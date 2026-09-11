@extends('app')

@section('content')

<div class="table-responsive">

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('product.create') }}" class="btn btn-primary">
        Tambah Product
    </a>
</div>

<table class="table table-bordered table-striped align-middle">

    <thead>
        <tr>
            <th>No</th>
            <th>Photo</th>
            <th>Name</th>
            <th>Category Name</th>
            <th>Price</th>
            <th>Stok</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($product as $index => $value)

            <tr>

                {{-- No --}}
                <td>
                    {{ $index + 1 }}
                </td>

                {{-- Photo --}}
                <td>
                    <img
                        src="{{ asset('storage/' . $value->photo) }}"
                        alt="{{ $value->name }}"
                        width="50"
                        height="50"
                        style="object-fit: cover;"
                    >
                </td>

                {{-- Name --}}
                <td>
                    {{ $value->name }}
                </td>

                {{-- Category Name --}}
                <td>
                    {{ $value->category->name }}
                </td>

                {{-- Price --}}
                <td>
                    Rp. {{ number_format($value->price, 0, ',', '.') }}
                </td>

                {{-- Stock --}}
                <td>
                    {{ $value->stock }}
                </td>

                {{-- Description --}}
                <td>
                    {{ $value->description }}
                </td>

                {{-- Action --}}
                <td>
                    <div class="d-flex gap-2">

                        {{-- Edit --}}
                        <a
                            href="{{ route('product.edit', $value->id) }}"
                            class="btn btn-success"
                        >
                            Edit
                        </a>

                        {{-- Delete --}}
                        <form
                            action="{{ route('product.destroy', $value->id) }}"
                            method="POST"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('For real?')"
                            >
                                Delete
                            </button>

                        </form>

                    </div>
                </td>

            </tr>

        @endforeach

    </tbody>

</table>

</div>

@endsection