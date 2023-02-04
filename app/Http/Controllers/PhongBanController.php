<?php

namespace App\Http\Controllers;

use App\Models\PhongBan;
use App\Services\PhongBanService;

class PhongBanController extends BaseController
{
    protected static $service = PhongBanService::class;
    protected static $keyItems = 'phong_ban';
    protected static $model = PhongBan::class;
}
