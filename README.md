Tentu, saya akan membantu Anda belajar Laravel dari awal dengan tutorial CRUD yang lengkap. Berikut adalah langkah-langkah rinci untuk membuat aplikasi CRUD sederhana di Laravel, termasuk penjelasan setiap langkahnya:

### 1. Instalasi Laravel

**Langkah:**
1. Pastikan Anda memiliki Composer terinstal di sistem Anda. Jika belum, Anda dapat mengunduhnya dari [getcomposer.org](https://getcomposer.org/).
2. Buka terminal dan jalankan perintah berikut untuk menginstal Laravel:

```bash
composer create-project --prefer-dist laravel/laravel my_crud_app
```

**Penjelasan:**
- **Composer:** Manajer dependensi untuk PHP, digunakan untuk menginstal dan mengelola paket dan pustaka PHP.
- **Laravel:** Framework PHP yang digunakan untuk pengembangan aplikasi web.

### 2. Konfigurasi Database

**Langkah:**
1. Buat database baru di MySQL atau MariaDB.
2. Buka file `.env` di root proyek Laravel Anda dan sesuaikan pengaturan database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_crud_app
DB_USERNAME=root
DB_PASSWORD=secret
```

**Penjelasan:**
- **.env:** File konfigurasi lingkungan yang berisi informasi sensitif seperti pengaturan database dan kunci aplikasi.

### 3. Membuat Migration

**Langkah:**
1. Buat migration untuk tabel `users`:

```bash
php artisan make:migration create_users_table --create=users
```

2. Buka file migration yang baru dibuat di `database/migrations/` dan tambahkan kolom yang diperlukan:

```php
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamps();
    });
}
```

3. Jalankan migration untuk membuat tabel `users`:

```bash
php artisan migrate
```

**Penjelasan:**
- **Migration:** Skrip untuk membuat, mengubah, dan menghapus tabel database. Memudahkan versi kontrol pada struktur database.
- **php artisan:** CLI untuk menjalankan perintah terkait Laravel.
- **Schema::create:** Membuat tabel baru di database.

### 4. Membuat Model

**Langkah:**
1. Buat model untuk `User`:

```bash
php artisan make:model User
```

**Penjelasan:**
- **Model:** Representasi dari tabel database dan menyediakan metode untuk berinteraksi dengan tabel tersebut.

### 5. Membuat Controller dan Routes

**Langkah:**
1. Buat controller untuk CRUD `User`:

```bash
php artisan make:controller UserController --resource
```

2. Tambahkan routes di `routes/web.php`:

```php
Route::resource('users', UserController::class);
```

**Penjelasan:**
- **Controller:** Mengontrol logika aplikasi dan bertanggung jawab untuk menangani permintaan HTTP.
- **Route:** Mendefinisikan URL yang dapat diakses pengguna dan mengaitkannya dengan controller.

### 6. Membuat Views

**Langkah:**
1. Buat folder `users` di `resources/views/`.
2. Buat file Blade template untuk index, create, edit, dan show:

- **index.blade.php**:

```blade
@extends('layouts.app')

@section('content')
    <h1>Users</h1>
    <a href="{{ route('users.create') }}">Create New User</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('users.show', $user->id) }}">View</a>
                    <a href="{{ route('users.edit', $user->id) }}">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
```

- **create.blade.php**:

```blade
@extends('layouts.app')

@section('content')
    <h1>Create User</h1>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div>
            <label>Name:</label>
            <input type="text" name="name">
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email">
        </div>
        <button type="submit">Submit</button>
    </form>
@endsection
```

- **edit.blade.php**:

```blade
@extends('layouts.app')

@section('content')
    <h1>Edit User</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ $user->name }}">
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ $user->email }}">
        </div>
        <button type="submit">Update</button>
    </form>
@endsection
```

- **show.blade.php**:

```blade
@extends('layouts.app')

@section('content')
    <h1>User Details</h1>
    <p>Name: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
    <a href="{{ route('users.index') }}">Back</a>
@endsection
```

**Penjelasan:**
- **Blade Template:** Template engine bawaan Laravel yang memudahkan penulisan kode HTML yang dinamis.

### 7. Mengimplementasikan CRUD di Controller

**Langkah:**
1. Buka file `app/Http/Controllers/UserController.php` dan implementasikan metode CRUD:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        User::create($request->all());
        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->all());
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
```

**Penjelasan:**
- **Request:** Mengelola data yang diterima dari form dan memvalidasi input.
- **View:** Mengembalikan tampilan yang sesuai dengan data yang diberikan.

### 8. Menjalankan Aplikasi

**Langkah:**
1. Jalankan server pengembangan Laravel:

```bash
php artisan serve
```

2. Akses aplikasi di browser melalui URL: `http://127.0.0.1:8000/users`.

**Penjelasan:**
- **php artisan serve:** Menjalankan server pengembangan Laravel sehingga Anda dapat mengakses aplikasi melalui browser.