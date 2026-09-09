@extends('app')
@section('content')
    <form action="{{ route('category.update', $edit->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="" class="form-label">Edit Name</label>
            <input type="text" class="form-control" name="name" value="{{ $edit->name }}">
        </div>

        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection
