<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Client Dashboard</title>
    <style>
        body {
            display: flex;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            height: 100vh;
            background: #343a40;
            color: #fff;
        }
        #sidebar .nav-link {
            color: #ffffff;
        }
        #sidebar .nav-link.active {
            background-color: #28a745;
        }
        #content {
            width: 100%;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar" class="d-flex flex-column p-3 bg-dark">
        <h3 class="text-light">Client Panel</h3>
        <ul class="nav flex-column">
            <!-- <li class="nav-item">
                <a class="nav-link active" href="/client/dashboard">Dashboard</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link active" href="/client/questionnaire">View Questionnaire</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="/client/progress">Check Progress</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/client/profile">Profile Settings</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="/logout">Logout</a>
            </li>
        </ul>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <div class="row p-5">
