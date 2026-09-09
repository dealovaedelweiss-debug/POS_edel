@extends('app')
@section('content')
    <form action="{{ route('role.store') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="" class="form-label">Role Name</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="mb-3">
            <select name="role" id="">
                <option value="">Choose Role</option>
                <option value="">Administrator</option>
                <option value="">Cashier</option>
                <option value="">Manager</option>
            </select>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection
