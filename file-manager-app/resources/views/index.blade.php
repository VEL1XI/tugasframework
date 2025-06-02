<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager - Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">
            <i class="fas fa-folder-open"></i> File Manager Laravel
        </h2>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Upload Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-upload"></i> Upload File</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <input type="file" class="form-control" name="file" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- File List -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-file-alt"></i> Daftar File ({{ count($fileList) }} file)</h5>
            </div>
            <div class="card-body">
                @if(count($fileList) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama File</th>
                                    <th>Ukuran</th>
                                    <th>Tanggal Modified</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fileList as $file)
                                    <tr>
                                        <td>
                                            <i class="fas fa-file"></i>
                                            {{ $file['name'] }}
                                        </td>
                                        <td>{{ number_format($file['size'] / 1024, 2) }} KB</td>
                                        <td>{{ date('d/m/Y H:i', $file['modified']) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Download Button -->
                                                <a href="{{ route('files.download', $file['name']) }}" 
                                                   class="btn btn-success btn-sm" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                
                                                <!-- Preview Button -->
                                                <a href="{{ route('files.stream', $file['name']) }}" 
                                                   class="btn btn-info btn-sm" target="_blank" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <!-- Delete Button -->
                                                <form action="{{ route('files.delete', $file['name']) }}" 
                                                      method="POST" style="display: inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada file yang diupload</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>