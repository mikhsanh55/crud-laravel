<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-4 mb-4">Edit User</h1>
        <form action="/update" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $user['id'] }}" />
            <input type="text" name="name" value="{{ $user['name'] }}" />
            <input type="text" name="email" value="{{ $user['email'] }}" />
            <button type="submit" class="btn btn-primary">
                Update Data
            </button>
        </form>
    </div>
</body>
</html>