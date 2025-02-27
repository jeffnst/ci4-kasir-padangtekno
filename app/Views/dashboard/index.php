<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css') ?>">
</head>

<body>
    <div class="wrapper">
        <nav class="navbar navbar-expand navbar-light bg-light">
            <a href="#" class="navbar-brand">POS Dashboard</a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="<?= base_url('/logout') ?>" class="nav-link">Logout</a>
                </li>
            </ul>
        </nav>
        <div class="container mt-5">
            <h1>Selamat Datang, <?= session('name') ?>!</h1>
            <p>Role Anda: <?= session('role') ?></p>
        </div>
    </div>
</body>

</html>