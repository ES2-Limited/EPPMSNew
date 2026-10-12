<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
    <title>@yield('title')</title>
    <style>
        .text-dark-custom {
            color: #121212 !important;
        }

        @media print {
            button {
                display: none;
            }
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .footer {
            border-top: 2px solid #000;
            padding-top: 10px;
            text-align: center;
            font-size: 12px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body class="container mt-4 text-dark-custom">
    <div class="table-responsive ">
        <table class="table table-striped rounded-4 overflow-hidden">
            @yield('content')
        </table>
    </div>
    <button class="btn btn-primary no-print" onclick="window.print()">Print</button>
    <script>
        document.getElementById('currentDate').innerText = new Date().toLocaleDateString();
    </script>

</body>

</html>
