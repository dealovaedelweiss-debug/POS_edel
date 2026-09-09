@extends('app') {{-- Sesuaikan dengan nama file layout utama kamu --}}

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 text-dark px-4">Data User</h2>
        <a href="{{ route('user.create') }}" class="btn btn-primary">
            Create New User
        </a>
    </div>

    {{-- Menampilkan pesan sukses jika ada (setelah proses tambah/edit/hapus) --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th class="px-3">No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                {{-- Menampilkan nomor urut yang sesuai dengan pagination --}}
                                <td>{{ $index += 1}}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-success">Edit</a>

                                    {{-- Tombol Hapus: Harus dibungkus dalam Form agar aman dari celah CSRF --}}
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
