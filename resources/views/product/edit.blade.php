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
                    <option {{ $edit->id == $category->id ? 'selected'  : '' }}
                    value="{{ $category->id }}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" {{$edit->name}}>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Price</label>
            <input type="number" class="form-control" name="price" {{$edit->price}}>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Stok</label>
            <input type="number" class="form-control" name="price" {{$edit->stock}}>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Photo</label>
            <input type="file" class="form-control" name="photo" {{$edit->photo}}>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection
