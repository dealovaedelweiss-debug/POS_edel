@extends('app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="m-0 text-dark fw-bold px-4">Edit User</h2>
            {{-- <p class="page-subtitle px-4">Perbarui informasi pengguna melalui formulir di bawah ini.</p> --}}
        </div>
        <a href="{{ route('user.index') }}" class="btn-custom btn-custom-light">
            {{-- <i class="bi bi-arrow-left"></i> Kembali --}}
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-custom alert-custom-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill alert-custom-icon"></i>
            <div class="alert-custom-content">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label-custom">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <span class="form-feedback-custom invalid-custom">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label-custom">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="contoh@email.com" required>
                    @error('email')
                        <span class="form-feedback-custom invalid-custom">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label-custom">Password Baru <span class="text-muted fw-normal">(Opsional)</span></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                    <small class="text-muted mt-1 d-block">Biarkan kosong jika tidak ingin mengganti password lama.</small>
                    @error('password')
                        <span class="form-feedback-custom invalid-custom">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('user.index') }}" class="btn-custom btn-custom-light">Batal</a>
                    <button type="submit" class="btn-custom btn-custom-primary">Perbarui User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
