<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .register-card {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>

    <!-- Registration Form -->
    <div class="register-card shadow p-4 bg-white rounded">
        <h3 class="text-center mb-4">Register</h3>
        <form action="/register/process" method="post">
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group mb-4">
                <label for="role" class="form-label">Select Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="" disabled selected>Select a role</option>
                    <option value="Admin">Admin</option>
                    <option value="Client">Client</option>
                </select>
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-success btn-block">Register</button>
            </div>
            <p class="text-center">
                <a href="/" class="text-decoration-none">Already have an account? Login here</a>
            </p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
