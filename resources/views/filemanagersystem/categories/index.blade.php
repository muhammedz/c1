@extends('adminlte::page')

@section('title', 'Kategori Yönetimi')

@section('content_header')
    <h1>Kategori Yönetimi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Kategoriler</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal">
                    <i class="fas fa-plus"></i> Yeni Kategori
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ad</th>
                            <th>Renk</th>
                            <th>Dosya Sayısı</th>
                            <th>Oluşturulma</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    <i class="fas fa-tag" style="color: {{ $category->color }}"></i>
                                    {{ $category->name }}
                                </td>
                                <td>
                                    <span class="badge" style="background-color: {{ $category->color }}">
                                        {{ $category->color }}
                                    </span>
                                </td>
                                <td>{{ $category->media_count }}</td>
                                <td>{{ $category->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" onclick="editCategory({{ $category->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteCategory({{ $category->id }})">
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

    <!-- Kategori Oluşturma Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.filemanagersystem.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCategoryModalLabel">Yeni Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Kategori Adı</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="color">Renk</label>
                            <input type="color" class="form-control" id="color" name="color" value="#000000" required>
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Üst Kategori</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">Ana Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
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

    <!-- Kategori Düzenleme Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Kategoriyi Düzenle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Kategori Adı</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_color">Renk</label>
                            <input type="color" class="form-control" id="edit_color" name="color" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_parent_id">Üst Kategori</label>
                            <select class="form-control" id="edit_parent_id" name="parent_id">
                                <option value="">Ana Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
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
        function editCategory(id) {
            $.get(`/admin/filemanagersystem/categories/${id}/edit`, function(category) {
                $('#edit_name').val(category.name);
                $('#edit_color').val(category.color);
                $('#edit_parent_id').val(category.parent_id);
                $('#editCategoryForm').attr('action', `/admin/filemanagersystem/categories/${id}`);
                $('#editCategoryModal').modal('show');
            });
        }

        function deleteCategory(id) {
            if (confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')) {
                $.ajax({
                    url: `/admin/filemanagersystem/categories/${id}`,
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