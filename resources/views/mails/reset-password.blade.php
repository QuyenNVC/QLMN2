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
            <h3 class="text-white text-uppercase">Mail lấy lại mật khẩu</h3>
        </div>
        <div class="card-body">
            <p>Xin chào <b>{{ $data['name'] }}</b></p>
            <p>Để lấy lại tài khoản vui lòng truy cập vào đường link <a href={{ $data['url'] }}>này</a> để đặt lại mật khẩu</p>
        </div>
    </div>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
<!-- </body>
</html> -->