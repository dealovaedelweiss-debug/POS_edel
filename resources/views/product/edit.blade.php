@extends('app')
@section('content')
    <form action="{{ route('product.update', $edit->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="" class="form-label">Category</label>
            <select name="category_id" id="" class="form-control">
                <option value="">Select One</option>
                @foreach ( $categories as $category )
                    <option {{ $edit->category_id == $category->id ? 'selected' : '' }}
                    value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="{{ $edit->name }}">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Price</label>
            <input type="number" class="form-control" name="price" value="{{ $edit->price }}">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Stok</label>
            <input type="number" class="form-control" name="stock" value="{{ $edit->stock }}">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Photo</label>
            @if($edit->photo)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $edit->photo) }}" alt="" width="100" class="rounded">
                </div>
            @endif
            <input type="file" class="form-control" name="photo">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
        </div>
    </form>
@endsection
