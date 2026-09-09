@extends('app')
@section('content')
    <form action="{{ route('role.update', $role->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="" class="form-label">Role Name</label>
            <input type="text" class="form-control" name="name" value="{{ $role->name }}">
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
