@extends('adminlte::page')

@section('title', 'Klasör Yönetimi')

@section('content_header')
    <h1>Klasör Yönetimi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Klasörler</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createFolderModal">
                    <i class="fas fa-plus"></i> Yeni Klasör
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ad</th>
                            <th>Yol</th>
                            <th>Dosya Sayısı</th>
                            <th>Oluşturulma</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($folders as $folder)
                            <tr>
                                <td>
                                    <i class="fas fa-folder text-warning"></i>
                                    {{ $folder->name }}
                                </td>
                                <td>{{ $folder->path }}</td>
                                <td>{{ $folder->media_count }}</td>
                                <td>{{ $folder->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" onclick="editFolder({{ $folder->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteFolder({{ $folder->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Klasör Oluşturma Modal -->
    <div class="modal fade" id="createFolderModal" tabindex="-1" role="dialog" aria-labelledby="createFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.filemanagersystem.folders.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createFolderModalLabel">Yeni Klasör</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Klasör Adı</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Üst Klasör</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">Ana Klasör</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Oluştur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Klasör Düzenleme Modal -->
    <div class="modal fade" id="editFolderModal" tabindex="-1" role="dialog" aria-labelledby="editFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editFolderForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFolderModalLabel">Klasörü Düzenle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Klasör Adı</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_parent_id">Üst Klasör</label>
                            <select class="form-control" id="edit_parent_id" name="parent_id">
                                <option value="">Ana Klasör</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        function editFolder(id) {
            $.get(`/admin/filemanagersystem/folders/${id}/edit`, function(folder) {
                $('#edit_name').val(folder.name);
                $('#edit_parent_id').val(folder.parent_id);
                $('#editFolderForm').attr('action', `/admin/filemanagersystem/folders/${id}`);
                $('#editFolderModal').modal('show');
            });
        }

        function deleteFolder(id) {
            if (confirm('Bu klasörü silmek istediğinizden emin misiniz?')) {
                $.ajax({
                    url: `/admin/filemanagersystem/folders/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            }
        }
    </script>
@stop 