<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hapus Akun</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CDN Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
        }
        .delete-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="delete-container">
        <h4 class="mb-4 text-center">Konfirmasi Hapus Akun</h4>

        {{-- Success Message --}}
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Gagal!</strong> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('delete.account') }}">
            @csrf

            <div class="mb-3">
                <label for="login" class="form-label">Username atau Email</label>
                <input type="text" name="login" class="form-control" required placeholder="Masukkan username atau email">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
            </div>

            <button type="submit" class="btn btn-danger w-100">Hapus Akun</button>
        </form>
    </div>
</div>

</body>
</html>
