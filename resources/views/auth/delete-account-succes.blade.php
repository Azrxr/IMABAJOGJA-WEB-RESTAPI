<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Akun Dihapus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
        }
        .success-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="success-container">
        <h4 class="text-success mb-4">Akun Anda berhasil dihapus.</h4>
        <a href="{{ route('login') }}" class="btn btn-primary">Kembali ke Login</a>
    </div>
</div>

</body>
</html>
