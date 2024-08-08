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

## Membuat Fitur Register dan Login

### Langkah 1: Membuat Model dan Controller

1. **Model User**: Model `User` sudah disediakan oleh Laravel di `app/Models/User.php`.
2. **Auth Controller**: Buat controller untuk autentikasi.
   ```bash
   php artisan make:controller AuthController
   ```

### Langkah 4: Membuat Routes

Tambahkan routes di file `routes/web.php`:
```php
use App\Http\Controllers\AuthController;

Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
```

### Langkah 5: Membuat Form Register

Buat view untuk form register di `resources/views/register.blade.php`:
```html
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        <div>
            <button type="submit">Register</button>
        </div>
    </form>
</body>
</html>
```

### Langkah 6: Membuat Form Login

Buat view untuk form login di `resources/views/login.blade.php`:
```html
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>
```

### Langkah 7: Implementasi Register dan Login di Controller

Edit `AuthController` untuk menambahkan logika register dan login:
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Menampilkan form register
    public function showRegisterForm()
    {
        return view('register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful, please login.');
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('dashboard');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}
```

### Penjelasan Kode

1. **Form Register**: Form ini mengirim data ke route `register` menggunakan metode `POST`. Menggunakan `@csrf` untuk melindungi dari serangan CSRF.
2. **Form Login**: Mirip dengan form register, tetapi untuk login.
3. **AuthController**:
   - `showRegisterForm()`: Menampilkan view register.
   - `register()`: Memvalidasi data, membuat pengguna baru, dan mengenkripsi password menggunakan `Hash::make()`.
   - `showLoginForm()`: Menampilkan view login.
   - `login()`: Memvalidasi data dan menggunakan `Auth::attempt()` untuk mencoba login. Jika berhasil, diarahkan ke `dashboard`.

### Langkah 8: Menambahkan Middleware Auth

Pastikan halaman `dashboard` atau halaman lainnya dilindungi middleware auth. Tambahkan di `routes/web.php`:
```php
Route::get('dashboard', function () {
    return 'Welcome to your dashboard!';
})->middleware('auth');
```

Dengan langkah-langkah ini, Anda akan memiliki sistem login dan register dasar di Laravel tanpa menggunakan scaffolding. Anda bisa mengembangkan lebih lanjut dengan menambahkan fitur-fitur tambahan seperti logout, reset password, dan lainnya.