<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body> -->
    <div class="card">
        <div class="card-header bg-dark">
            <h3 class="text-white text-uppercase">Mail xác thực đăng ký tài khoản</h3>
        </div>
        <div class="card-body">
            <p>Kính chào quý phụ huynh <b>{{ $data['name'] }}</b></p>
            <p>Quý phụ huynnh vui lòng truy cập đường dẫn sau để kích hoạt tài khoản <a href={{ $data['url'] }}>SmartKids.com</a></p>
        </div>
    </div>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
<!-- </body>
</html> -->