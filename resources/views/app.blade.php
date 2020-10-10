<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <script type="text/javascript">
        window.csrf_token = "{{ csrf_token() }}"
    </script>
    @routes
</head>
<body>
@inertia
</body>
</html>
