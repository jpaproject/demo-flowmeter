@extends('layouts.main')
@push('css')
    <!-- CSS Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush
@section('content')
<div class="container">
    <h2 class="mb-4">Manage Roles & Permissions</h2>

    {{-- Notifikasi Success --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Notifikasi Error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        {{-- Form Tambah Role --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Create New Role</div>
                <div class="card-body">
                    <form action="{{ route('admin.roles-permissions.storeRole') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="role_name" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="role_name" name="name" required>
                        </div>
                        <button class="btn btn-primary" type="submit">Add Role</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Form Tambah Permission --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Create New Permission</div>
                <div class="card-body">
                    <form action="{{ route('admin.roles-permissions.storePermission') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="permission_name" class="form-label">Permission Name</label>
                            <input type="text" class="form-control" id="permission_name" name="name" required>
                        </div>
                        <button class="btn btn-primary" type="submit">Add Permission</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Roles + Assign Permissions --}}
    <div class="card">
        <div class="card-header">Roles & Assign Permissions</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th>Assign Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                    <tr>
                        {{-- Nama Role & Edit --}}
                        <td>
                            <form action="{{ route('admin.roles-permissions.updateRole', $role->id) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $role->name }}" class="form-control form-control-sm me-2" style="width: 150px;" required>
                                <button class="btn btn-sm btn-success" type="submit" title="Update Role">Save</button>
                            </form>
                        </td>

                        {{-- Daftar Permission Role --}}
                        <td>
                            @foreach ($role->permissions as $permission)
                                <span class="badge bg-info text-dark me-1">{{ $permission->name }}</span>
                            @endforeach
                        </td>

                        {{-- Assign Permission --}}
                        <td>
                            <form action="{{ route('admin.roles-permissions.assignPermissions', $role->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="permissions[]" multiple class="form-select form-select-sm select2" style="min-width: 200px;">
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->id }}" 
                                            {{ $role->permissions->contains('id', $permission->id) ? 'selected' : '' }}>
                                            {{ $permission->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-primary mt-1" type="submit" title="Assign Permissions">Assign</button>
                            </form>
                        </td>

                        {{-- Hapus Role --}}
                        <td>
                            <form action="{{ route('admin.roles-permissions.destroyRole', $role->id) }}" method="POST" onsubmit="return confirm('Delete role {{ $role->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit" title="Delete Role">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Permissions --}}
    <div class="card mt-4">
        <div class="card-header">Permissions</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Permission Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                    <tr>
                        {{-- Edit Permission --}}
                        <td>
                            <form action="{{ route('admin.roles-permissions.updatePermission', $permission->id) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $permission->name }}" class="form-control form-control-sm me-2" required>
                                <button class="btn btn-sm btn-success" type="submit" title="Update Permission">Save</button>
                            </form>
                        </td>

                        {{-- Hapus Permission --}}
                        <td>
                            <form action="{{ route('admin.roles-permissions.destroyPermission', $permission->id) }}" method="POST" onsubmit="return confirm('Delete permission {{ $permission->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit" title="Delete Permission">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('js')
<!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        $('select[name="permissions[]"]').select2({
            placeholder: "Select permissions",
            allowClear: true,
            width: 'style' // supaya menyesuaikan lebar select box
        });
    });
</script>

@endpush