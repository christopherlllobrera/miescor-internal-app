<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I AM MIESCOR</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/miescor/favicon.ico') }}">
    <meta name="description" content="MIESCOR is a leading engineering and construction company in the Philippines.">
    <meta name="keywords" content="MIESCOR, Engineering, Construction, Philippines">
</head>
<body>
    @include('employee-portal.navigation')
    @include('employee-portal.heropage', ['carousels' => $carousels])
    @include('employee-portal.departments.department-main-folder', ['departments' => $departments])
    @include('employee-portal.newspage.newspage')
    @include('employee-portal.events.events', ['events' => $events])
    @include('employee-portal.footer')
</body>
</html>
