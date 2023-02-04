<?php
namespace App\Services;
use App\Models\ExtraService;
use App\Models\CostExtraService;
use App\Models\CostExtraServiceExtraService;

class CostExtraServiceService {
    public function getData($id_coso = null, $grade = null, $school_year = null) {
        $costExtraServices = ExtraService::join('cost_extra_service_extra_service', 'extra_service.id', '=', 'cost_extra_service_extra_service.id_type')->rightJoin('cost_extra_service', 'cost_extra_service_extra_service.id_cost', '=', 'cost_extra_service.id')->leftJoin('school_year', 'cost_extra_service.school_year', '=', 'school_year.id')->leftJoin('grade', 'cost_extra_service.grade', '=', 'grade.id')->whereNull('cost_extra_service.isDeleted');
        if($id_coso) {
            $costExtraServices = $costExtraServices->where('id_coso', $id_coso);
        }
        if($grade) {
            $costExtraServices = $costExtraServices->where('grade', $grade);
        }
        if($school_year) {
            $costExtraServices = $costExtraServices->where('school_year', $school_year);
        }
        $costExtraServices = $costExtraServices->select(['cost_extra_service.*', 'school_year.period as school_year_name', 'grade.name as grade_name', 'id_coso'])->distinct('id')->orderBy('id', 'desc')->get();
        foreach($costExtraServices as $costExtraService) {
            $costExtraServiceExtraServices = CostExtraServiceExtraService::join('extra_service', 'cost_extra_service_extra_service.id_type', '=', 'extra_service.id')
            ->where('cost_extra_service_extra_service.id_cost', '=', $costExtraService['id'])->get();
            $fees = [];
            foreach($costExtraServiceExtraServices as  $costExtraServiceExtraService) {
                $fees[] = [
                    'id' => $costExtraServiceExtraService['id_type'],
                    'name' => $costExtraServiceExtraService['name'],
                    'cost' => $costExtraServiceExtraService['cost'] != null ? $costExtraServiceExtraService['cost'] : ''
                ];
            }
                
            $costExtraService['fees'] = $fees;
        }
        return $costExtraServices;
    }
}