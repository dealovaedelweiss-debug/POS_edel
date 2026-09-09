@extends('app')
@section('content')
    <form action="{{ route('category.store') }}" method="post" class="container-fluid px-4 pt-4">
        @csrf
        <div class="mb-3">
            <label for="" class="form-label">Category</label>
            <input type="text" class="form-control" name="name">
        </div>

        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection
