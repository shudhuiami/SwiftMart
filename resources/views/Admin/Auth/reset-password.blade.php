<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
<h1>Password Reset</h1>

<form method="POST">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div>
        <label for="password">New Password:</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>
    </div>

    <button type="submit">Reset Password</button>
</form>
</body>
</html>
