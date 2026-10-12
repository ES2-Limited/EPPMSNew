<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Added to Project</title>
</head>

<body>
    <h1>Hello {{ $type }},</h1>
    <p>You’ve been added to the project: <strong>{{ $project->name }}</strong>.</p>
    <p>
        <a href="{{ $url }}">Click here to view the project</a>
    </p>
    <p>Added by: {{ $creator_name }}</p>
</body>

</html>
