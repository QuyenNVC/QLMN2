<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Info' => 'App\Policies\InfoPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\DailyFee' => 'App\Policies\DailyFeePolicy',
        'App\Models\MonthlyFee' => 'App\Policies\MonthlyFeePolicy',
        'App\Models\AnnualFee' => 'App\Policies\AnnualFeePolicy',
        'App\Models\ExtraService' => 'App\Policies\ExtraServicePolicy',
        'App\Models\Classes' => 'App\Policies\ClassesPolicy',
        'App\Models\Record' => 'App\Policies\RecordPolicy',
        'App\Models\HinhThucDiemDanh' => 'App\Policies\HinhThucDiemDanhPolicy',
        'App\Models\QuanLyThu' => 'App\Policies\QuanLyThuPolicy',
        'App\Models\QuanLyChi' => 'App\Policies\QuanLyChiPolicy',
        'App\Models\DanhMucChiPhi' => 'App\Policies\DanhMucChiPhiPolicy',
        'App\Models\NhaCungCap' => 'App\Policies\NhaCungCapPolicy',
        'App\Models\DanhMucCoSoVatChat' => 'App\Policies\DanhMucCoSoVatChatPolicy',
        'App\Models\SuatAn' => 'App\Policies\SuatAnPolicy',
        'App\Models\ThucDon' => 'App\Policies\ThucDonPolicy',
        'App\Models\PhongBan' => 'App\Policies\PhongBanPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // vì đây là bước khởi động ứng dụng (boot) nên chưa khởi tạo Auth để lấy id nên var_dump ra sẽ bị trả về false, bắt buộc phải so sánh id user truyền vào với ID_SUPER_ADMIN
        Gate::define('superAdmin', function(User $user) {
            return $user->isSuperAdmin();
        });
        //Thêm phân quyền
        if(!$this->app->runningInConsole()){
            // không phải ứng dụng chạy trong cửa sổ lệnh thì mới thực hiện kiểm tra
            $permissions = Permission::get();
            foreach ($permissions as $_pms){
                Gate::define($_pms->code, function (User $user) use ($_pms) {
                    return $user->isSuperAdmin() ? true : $user->hasPermission($_pms->id);
                });
            }

        }
    }
}
