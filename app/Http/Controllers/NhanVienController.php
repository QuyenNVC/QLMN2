<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NhanVien;
use App\Models\PhuCap;
use App\Models\PhuCapNhanVien;
use App\Models\TangGiamLuongNhanVien;
use App\Models\UngLuongNhanVien;
use App\Models\PhongBan;
use App\Models\ChamCong;
use App\Services\ApiResultService;
use App\Services\EmployeeService;
use App\Services\NhanVienService;

class NhanVienController extends BaseController
{
    protected static $service = NhanVienService::class;
    protected static $keyItems = 'nhan_vien';
    protected static $model = NhanVien::class;
    /**
     * Create or update nhân viên
     * Author: HaoDT
     */
    public function updateNhanVien(Request $request) {
        $nhanVien = NhanVien::where('ma_nv', $request->ma_nv)->first();
        if (!$nhanVien) {
            $nhanVien = new NhanVien();
        }
        foreach($request->all() as $key => $value) {
            $nhanVien->$key = $value;
        }
        // 1: nghỉ làm
        // 0: đang làm
        if ($request->status_nghi_viec) {
            $nhanVien->status_nghi_viec = 1;
        } else {
            $nhanVien->status_nghi_viec = 0;
        }

        $nhanVien->save();
        $data = [
            'status' => true
        ];
        return json_encode($data);
    }  
    
    /**
     * api create user
     * Author: HaoDT
     */
    public function deleteNhanVien(Request $request, $id) {
        try {
            NhanVien::where('ma_nv', $id)->delete();
            ChamCong::where('ma_nv', $id)->delete();
            UngLuongNhanVien::where('ma_nv', $id)->delete();
            PhuCapNhanVien::where('ma_nv', $id)->delete();
            TangGiamLuongNhanVien::where('ma_nv', $id)->delete();

            $data = [
                'status' => true
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
        
    }

    public function createMaNV() {
        $count = NhanVien::withTrashed()->count();
        // $ma_nv = $item->ma_nv;
        // var_dump($ma_nv);
        // $ma_nv = (int)(substr($ma_nv, 2))+1;
        $ma_nv = 'NV'.($count+1);
        $data = [
            'status' => true,
            'item' => $ma_nv
        ];
        return response()->json($data, 200);
    }

     /**
     * api get list nhanvien
     * Author: HaoDT
     */
    public function getList2(Request $request) {
        try {
            $treeData = [];
            $treeType = [];

            $phongBans = PhongBan::all();
            
            $treeType_0 = [];
            $treeType_0["type"] = "#"; 
            $valid_children_0 = [];

            for($i=0; $i<count($phongBans); $i++) {
                $valid_children_0[] = "PB".$phongBans[$i]['id'];

                $treeType_i = [
                    "icon" => "far fa-hospital",
                    "is_content" => false,
                    "type" => "PB".$phongBans[$i]['id']
                ];
                $valid_children_i = [];

                //tree data
                $treeData_i = [
                    "count" => 0,
                    "id" => "PB".$phongBans[$i]['id'],
                    "is_content" => false,
                    "text" => $phongBans[$i]['name'],
                    "type" => "PB".$phongBans[$i]['id']
                ];
                $treeData_children_i = [];

                $nhanVienPhongBan =  NhanVien::where('id_phong_ban', $phongBans[$i]['id'])->get();
                for($j=0; $j<count($nhanVienPhongBan); $j++) {
                    $valid_children_i[] = $nhanVienPhongBan[$j]["ma_nv"];

                    //tree data
                    $treeData_children_item = [
                        "count" => 0,
                        "id" => $nhanVienPhongBan[$j]["ma_nv"],
                        "is_content" => true,
                        "text" => $nhanVienPhongBan[$j]["fullname"],
                        "type" => $nhanVienPhongBan[$j]["ma_nv"],
                        'nghiViec' => ($nhanVienPhongBan[$j]["status_nghi_viec"]) ? true : false
                    ];
                    $treeData_children_i[] = $treeData_children_item;
                }

                $treeData_i["children"] = $treeData_children_i;
                $treeData[] = $treeData_i;


                $treeType_i['valid_children'] = $valid_children_i;
                $treeType[] = $treeType_i;

                $phongBans[$i] = $nhanVienPhongBan;
            }

            $treeType_0['valid_children'] = $valid_children_0;

            $treeType[] = $treeType_0;

            $allNhanVien = NhanVien::all();
            for($i=0; $i<count($allNhanVien); $i++) {

                $treeType_i = [
                    "icon" => "far fa-user",
                    "type" => $allNhanVien[$i]["ma_nv"],
                    'valid_children' => []
                ];
                
                $treeType[] = $treeType_i;
            }


            $data = [
                'status' => true,
                'treeType' => $treeType,
                'treeData' => $treeData
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }

     /**
     * api get list nhanvien
     * Author: HaoDT
     */
    public function getListDangLamViec(Request $request) {
        try {
            $user = NhanVien::where('status_nghi_viec', '0')->get();

            $data = [
                'status' => true,
                'nhan_vien' => $user
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }


     /**
     * api get list nhanviens by id phòng ban
     * Author: HaoDT
     */
    public function getNhanViensByIdPhongBan(Request $request, $idPhongBan) {
        try {
            $user = NhanVien::where('id_phong_ban', $idPhongBan)->get();

            $data = [
                'status' => true,
                'nhan_vien' => $user
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }

     /**
     * api get list nhanviens by id phòng ban
     * Author: HaoDT
     */
    public function getNhanViensByIdPhongBanDangLamViec(Request $request, $idPhongBan) {
        try {
            $user = NhanVien::where('id_phong_ban', $idPhongBan)->where('status_nghi_viec', '0')->get();

            $data = [
                'status' => true,
                'nhan_vien' => $user
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }


     /**
     * api get list nhanviens by id phòng ban
     * Author: HaoDT
     */
    public function getNhanViensByMaNV(Request $request, $manv) {
        try {
            $user = NhanVien::where('ma_nv', $manv)->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->select(['nhan_vien.*', 'phong_ban.name as phong_ban'])->first();

            $data = [
                'status' => true,
                'nhan_vien' => $user
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }

    /**
     * api get list nhanviens nghi viec by id phòng ban
     * Author: HaoDT
     */
    public function getListNhanVienNghiViec(Request $request, $idPhongBan = -1) {
        try {
            if ($idPhongBan == -1) {
                $user = NhanVien::where('status_nghi_viec', '1')->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->select(['nhan_vien.*', 'phong_ban.name as phong_ban'])->get();
    
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            } else {
                $user = NhanVien::where('status_nghi_viec', '1')->where('id_phong_ban', $idPhongBan)->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->select(['nhan_vien.*', 'phong_ban.name as phong_ban'])->get();
    
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }

    /**
     * api get list nhanviens dang lam viec by id phòng ban
     * Author: HaoDT
     */
    public function getListNhanVienDangLamViecPhuCap(Request $request, $thang, $nam, $idPhongBan = -1) {
        try {
            if ($idPhongBan == -1) {
                $user = NhanVien::where('status_nghi_viec', '0')->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban');
                if($request->id_coso) {
                    $user = $user->where('id_coso', $request->id_coso);
                }
                $user = $user->select(['nhan_vien.*', 'phong_ban.name as phong_ban'])->get();
                for ($i=0; $i<count($user); $i++) {
                    $phuCap = PhuCapNhanVien::whereRaw('month = ? and year = ? and ma_nv = ?', [$thang, $nam, $user[$i]["ma_nv"]])->select('data')->first();

                    if ($phuCap) {
                        $user[$i]['data'] = $phuCap['data'];
                    } else {
                        $user[$i]['data'] = null;
                    }
                }
                // select `nhan_vien`.*, `phong_ban`.`name` as `phong_ban`, `phu_cap_nhan_vien`.`data` from `nhan_vien` left join `phu_cap_nhan_vien` on `phu_cap_nhan_vien`.`ma_nv` = `nhan_vien`.`ma_nv` inner join `phong_ban` on `phong_ban`.`d` = `nhan_vien`.`id_phong_ban` where `status_nghi_viec` = 0 and phu_cap_nhan_vien.month = 5 and phu_cap_nhan_vien.year = 2021)
                // ->whereRaw('phu_cap_nhan_vien.month = ? and phu_cap_nhan_vien.year = ?', [$thang, $nam])
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            } else {
                $user = NhanVien::where('status_nghi_viec', '0')->where('id_phong_ban', $idPhongBan)->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->select(['nhan_vien.*', 'phong_ban.name as phong_ban'])->get();
                for ($i=0; $i<count($user); $i++) {
                    $phuCap = PhuCapNhanVien::whereRaw('month = ? and year = ? and ma_nv = ?', [$thang, $nam, $user[$i]["ma_nv"]])->select('data')->first();

                    if ($phuCap) {
                        $user[$i]['data'] = $phuCap['data'];
                    } else {
                        $user[$i]['data'] = null;
                    }
                }
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }


    /**
     * api get list nhanviens dang lam viec by id phòng ban
     * Author: HaoDT
     */
    public function getListNhanVienDangLamViecPhuCapBaoCao(Request $request, $thang, $nam, $idPhongBan = -1) {
        try {
            if ($idPhongBan == -1) {
                $user = NhanVien::where('status_nghi_viec', '0')->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->join('phu_cap_nhan_vien', 'phu_cap_nhan_vien.ma_nv', '=', 'nhan_vien.ma_nv')->whereRaw('month = ? and year = ?', [$thang, $nam])->select(['nhan_vien.*', 'phong_ban.name as phong_ban', 'phu_cap_nhan_vien.data'])->get();

                // select `nhan_vien`.*, `phong_ban`.`name` as `phong_ban`, `phu_cap_nhan_vien`.`data` from `nhan_vien` left join `phu_cap_nhan_vien` on `phu_cap_nhan_vien`.`ma_nv` = `nhan_vien`.`ma_nv` inner join `phong_ban` on `phong_ban`.`d` = `nhan_vien`.`id_phong_ban` where `status_nghi_viec` = 0 and phu_cap_nhan_vien.month = 5 and phu_cap_nhan_vien.year = 2021)
                // ->whereRaw('phu_cap_nhan_vien.month = ? and phu_cap_nhan_vien.year = ?', [$thang, $nam])
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            } else {
                $user = NhanVien::where('status_nghi_viec', '0')->where('id_phong_ban', $idPhongBan)->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->join('phu_cap_nhan_vien', 'phu_cap_nhan_vien.ma_nv', '=', 'nhan_vien.ma_nv')->whereRaw('month = ? and year = ?', [$thang, $nam])->select(['nhan_vien.*', 'phong_ban.name as phong_ban', 'phu_cap_nhan_vien.data'])->get();
                for ($i=0; $i<count($user); $i++) {
                    $phuCap = PhuCapNhanVien::whereRaw('month = ? and year = ? and ma_nv = ?', [$thang, $nam, $user[$i]["ma_nv"]])->select('data')->first();

                    if ($phuCap) {
                        $user[$i]['data'] = $phuCap['data'];
                    } else {
                        $user[$i]['data'] = null;
                    }
                }
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }


    /**
     * api get list nhanviens dang lam viec by id phòng ban
     * Author: HaoDT
     */
    public function getListNhanVienDangLamViecTangGiamLuong(Request $request, $thang, $nam, $idPhongBan = -1) {
        try {
            if ($idPhongBan == -1) {
                $user = NhanVien::where('status_nghi_viec', '0')->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban');
                if($request->id_coso) {
                    $user = $user->where('id_coso', $request->id_coso);
                }
                $user = $user->select(['nhan_vien.*', 'phong_ban.name as phong_ban'])->get();
                for ($i=0; $i<count($user); $i++) {
                    $phuCap = TangGiamLuongNhanVien::whereRaw('month = ? and year = ? and ma_nv = ?', [$thang, $nam, $user[$i]["ma_nv"]])->select('data')->first();

                    if ($phuCap) {
                        $user[$i]['data'] = $phuCap['data'];
                    } else {
                        $user[$i]['data'] = null;
                    }
                }
                // select `nhan_vien`.*, `phong_ban`.`name` as `phong_ban`, `phu_cap_nhan_vien`.`data` from `nhan_vien` left join `phu_cap_nhan_vien` on `phu_cap_nhan_vien`.`ma_nv` = `nhan_vien`.`ma_nv` inner join `phong_ban` on `phong_ban`.`d` = `nhan_vien`.`id_phong_ban` where `status_nghi_viec` = 0 and phu_cap_nhan_vien.month = 5 and phu_cap_nhan_vien.year = 2021)
                // ->whereRaw('phu_cap_nhan_vien.month = ? and phu_cap_nhan_vien.year = ?', [$thang, $nam])
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            } else {
                $user = NhanVien::where('status_nghi_viec', '0')->where('id_phong_ban', $idPhongBan)->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->select(['nhan_vien.*', 'phong_ban.name as phong_ban'])->get();
                for ($i=0; $i<count($user); $i++) {
                    $phuCap = TangGiamLuongNhanVien::whereRaw('month = ? and year = ? and ma_nv = ?', [$thang, $nam, $user[$i]["ma_nv"]])->select('data')->first();

                    if ($phuCap) {
                        $user[$i]['data'] = $phuCap['data'];
                    } else {
                        $user[$i]['data'] = null;
                    }
                }
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }

    /**
     * api get list nhanviens dang lam viec by id phòng ban
     * Author: HaoDT
     */
    public function getListNhanVienDangLamViecUngLuong(Request $request, $thang, $nam, $idPhongBan = -1) {
        try {
            if ($idPhongBan == -1) {
                $user = NhanVien::where('status_nghi_viec', '0')->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->select(['nhan_vien.*', 'phong_ban.name as phong_ban'])->get();
                for ($i=0; $i<count($user); $i++) {
                    $phuCap = UngLuongNhanVien::whereRaw('month = ? and year = ? and ma_nv = ?', [$thang, $nam, $user[$i]["ma_nv"]])->select('tien_ung')->first();

                    if ($phuCap) {
                        $user[$i]['tien_ung'] = $phuCap['tien_ung'];
                    } else {
                        $user[$i]['tien_ung'] = '';
                    }
                }
                // select `nhan_vien`.*, `phong_ban`.`name` as `phong_ban`, `phu_cap_nhan_vien`.`data` from `nhan_vien` left join `phu_cap_nhan_vien` on `phu_cap_nhan_vien`.`ma_nv` = `nhan_vien`.`ma_nv` inner join `phong_ban` on `phong_ban`.`d` = `nhan_vien`.`id_phong_ban` where `status_nghi_viec` = 0 and phu_cap_nhan_vien.month = 5 and phu_cap_nhan_vien.year = 2021)
                // ->whereRaw('phu_cap_nhan_vien.month = ? and phu_cap_nhan_vien.year = ?', [$thang, $nam])
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            } else {
                $user = NhanVien::where('status_nghi_viec', '0')->where('id_phong_ban', $idPhongBan)->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->select(['nhan_vien.*', 'phong_ban.name as phong_ban'])->get();
                for ($i=0; $i<count($user); $i++) {
                    $phuCap = UngLuongNhanVien::whereRaw('month = ? and year = ? and ma_nv = ?', [$thang, $nam, $user[$i]["ma_nv"]])->select('tien_ung')->first();

                    if ($phuCap) {
                        $user[$i]['tien_ung'] = $phuCap['tien_ung'];
                    } else {
                        $user[$i]['tien_ung'] = '';
                    }
                }
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }

    /**
     * api get list nhanviens dang lam viec by id phòng ban
     * Author: HaoDT
     */
    public function getListNhanVienDangLamViecUngLuongBaoCao(Request $request, $thang, $nam, $idPhongBan = -1) {
        try {
            if ($idPhongBan == -1) {
                $user = NhanVien::where('status_nghi_viec', '0')->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->join('ung_luong_nhan_vien', 'ung_luong_nhan_vien.ma_nv', '=', 'nhan_vien.ma_nv')->whereRaw('month = ? and year = ?', [$thang, $nam])->select(['nhan_vien.*', 'phong_ban.name as phong_ban', 'ung_luong_nhan_vien.tien_ung'])->get();

                // select `nhan_vien`.*, `phong_ban`.`name` as `phong_ban`, `phu_cap_nhan_vien`.`data` from `nhan_vien` left join `phu_cap_nhan_vien` on `phu_cap_nhan_vien`.`ma_nv` = `nhan_vien`.`ma_nv` inner join `phong_ban` on `phong_ban`.`d` = `nhan_vien`.`id_phong_ban` where `status_nghi_viec` = 0 and phu_cap_nhan_vien.month = 5 and phu_cap_nhan_vien.year = 2021)
                // ->whereRaw('phu_cap_nhan_vien.month = ? and phu_cap_nhan_vien.year = ?', [$thang, $nam])
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            } else {
                $user = NhanVien::where('status_nghi_viec', '0')->where('id_phong_ban', $idPhongBan)->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')->join('ung_luong_nhan_vien', 'ung_luong_nhan_vien.ma_nv', '=', 'nhan_vien.ma_nv')->whereRaw('month = ? and year = ?', [$thang, $nam])->select(['nhan_vien.*', 'phong_ban.name as phong_ban', 'ung_luong_nhan_vien.tien_ung'])->get();
                for ($i=0; $i<count($user); $i++) {
                    $phuCap = PhuCapNhanVien::whereRaw('month = ? and year = ? and ma_nv = ?', [$thang, $nam, $user[$i]["ma_nv"]])->select('data')->first();

                    if ($phuCap) {
                        $user[$i]['data'] = $phuCap['data'];
                    } else {
                        $user[$i]['data'] = null;
                    }
                }
                $data = [
                    'status' => true,
                    'nhan_vien' => $user
                ];
                return json_encode($data);
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }

    /**
     * Function get data employee resume
     * @author HaoDT
     */
    public function getEmployeeResume(Request $request)
    {
        try {
            $staffIds = $request->get('staff_ids', []);
            $excludes = $request->get('excludes_ids', []);
            $name = $request->get('name', '');
            $departments = $request->get('department', []);
            $workingStatus = $request->get('working_status', -1);
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $data = EmployeeService::getData($staffIds, $excludes, $name, $departments, $workingStatus, $pageSize, $page);
            return ApiResultService::success($data);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }
}
