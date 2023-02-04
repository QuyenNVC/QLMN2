<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    
    <style>
        .fs-20 { font-size: 20px; }
        .fs-30 { font-size: 30px; }
        .text-center { text-align: center; }
        .mt-30 { margin-top: 30px; }
        #app {
            width: 220mm;
            height: 297mm;
        }
    </style>
    <div id="app">
       <print-pdf ma-nv="{{ $ma_nv }}" thang="{{ $thang }}" nam="{{ $nam }}"/>
    </div>
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>