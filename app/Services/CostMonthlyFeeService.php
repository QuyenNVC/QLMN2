<?php
namespace App\Services;
use App\Models\MonthlyFee;
use App\Models\CostMonthlyFee;
use App\Models\CostMonthlyFeeMonthlyFee;

class CostMonthlyFeeService {
    public function getData($id_coso = null, $grade = null, $school_year = null) {
        $costMonthlyFees = MonthlyFee::join('cost_monthly_fee_monthly_fee', 'monthly_fee.id', '=', 'cost_monthly_fee_monthly_fee.id_type')->rightJoin('cost_monthly_fee', 'cost_monthly_fee_monthly_fee.id_cost', '=', 'cost_monthly_fee.id')->leftJoin('school_year', 'cost_monthly_fee.school_year', '=', 'school_year.id')->leftJoin('grade', 'cost_monthly_fee.grade', '=', 'grade.id')->whereNull('cost_monthly_fee.isDeleted');
        if($id_coso) {
            $costMonthlyFees = $costMonthlyFees->where('id_coso', $id_coso);
        }
        if($grade) {
            $costMonthlyFees = $costMonthlyFees->where('grade', $grade);
        }
        if($school_year) {
            $costMonthlyFees = $costMonthlyFees->where('school_year', $school_year);
        }
        $costMonthlyFees = $costMonthlyFees->distinct('id')->select(['cost_monthly_fee.*', 'id_coso', 'school_year.period as school_year_name', 'grade.name as grade_name'])->orderBy('id', 'desc')->get();
        foreach($costMonthlyFees as $costMonthlyFee) {
            $costMonthlyFeeMonthlyFees = CostMonthlyFeeMonthlyFee::join('monthly_fee', 'cost_monthly_fee_monthly_fee.id_type', '=', 'monthly_fee.id')
            ->where('cost_monthly_fee_monthly_fee.id_cost', '=', $costMonthlyFee['id'])->whereNull('monthly_fee.isDeleted')->get();
            $fees = [];
            foreach($costMonthlyFeeMonthlyFees as  $costMonthlyFeeMonthlyFee) {
                $fees[] = [
                    'name' => $costMonthlyFeeMonthlyFee['name'],
                    'cost' => $costMonthlyFeeMonthlyFee['cost'] != null ? $costMonthlyFeeMonthlyFee['cost'] : ''
                ];
            }
                
            $costMonthlyFee['fees'] = $fees;
        }
        return $costMonthlyFees;
    }
}