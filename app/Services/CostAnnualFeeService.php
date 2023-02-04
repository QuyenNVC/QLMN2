<?php
namespace App\Services;
use App\Models\AnnualFee;
use App\Models\CostAnnualFee;
use App\Models\CostAnnualFeeAnnualFee;

class CostAnnualFeeService {
    public function getData($id_coso = null, $grade = null, $school_year = null) {
        $costAnnualFees = AnnualFee::join('cost_annual_fee_annual_fee', 'annual_fee.id', '=', 'cost_annual_fee_annual_fee.id_type')->rightJoin('cost_annual_fee', 'cost_annual_fee_annual_fee.id_cost', '=', 'cost_annual_fee.id')->leftJoin('school_year', 'cost_annual_fee.school_year', '=', 'school_year.id')->leftJoin('grade', 'cost_annual_fee.grade', '=', 'grade.id')->whereNull('cost_annual_fee.isDeleted');
        if($id_coso) {
            $costAnnualFees = $costAnnualFees->where('id_coso', $id_coso);
        }
        if($grade) {
            $costAnnualFees = $costAnnualFees->where('grade', $grade);
        }
        if($school_year) {
            $costAnnualFees = $costAnnualFees->where('school_year', $school_year);
        }
        $costAnnualFees = $costAnnualFees->select(['cost_annual_fee.*', 'school_year.period as school_year_name', 'grade.name as grade_name', 'id_coso'])->distinct('id')->orderBy('id', 'desc')->get();
        foreach($costAnnualFees as $costAnnualFee) {
            $costAnnualFeeAnnualFees = CostAnnualFeeAnnualFee::join('annual_fee', 'cost_annual_fee_annual_fee.id_type', '=', 'annual_fee.id')
            ->where('cost_annual_fee_annual_fee.id_cost', '=', $costAnnualFee['id'])->get();
            $fees = [];
            foreach($costAnnualFeeAnnualFees as  $costAnnualFeeAnnualFee) {
                $fees[] = [
                    'name' => $costAnnualFeeAnnualFee['name'],
                    'cost' => $costAnnualFeeAnnualFee['cost'] != null ? $costAnnualFeeAnnualFee['cost'] : ''
                ];
            }
                
            $costAnnualFee['fees'] = $fees;
        }
        return $costAnnualFees;
    }
}