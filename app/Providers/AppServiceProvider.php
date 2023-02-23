<?php

namespace App\Providers;

use App\Models\ADiff;
use App\Models\ATest;
use App\Models\CDiff;
use App\Models\CTest;
use App\Models\Grade;
use App\Models\PDiff;
use App\Models\PTest;
use App\Models\CValue;
use App\Models\PValue;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Competency;
use App\Models\Department;
use App\Models\CenterPoint;
use App\Models\CompanyRank;
use App\Models\Designation;
use App\Models\CompanyADiff;

use App\Models\CompanyATest;
use App\Models\CompanyCDiff;
use App\Models\CompanyCTest;
use App\Models\CompanyDDiff;
use App\Models\CompanyDTest;
use App\Models\CompanyPDiff;
use App\Models\CompanyPTest;
use App\Models\CompanyCValue;
use App\Models\CompanyPValue;
use App\Models\EmployeeScore;
use App\Models\BusinessProfit;
use App\Models\CompanyBenefit;
use App\Models\EmployeeSalary;
use App\Models\CompanyBonusList;
use App\Models\CompanyFrequency;
use App\Observers\ADiffObserver;
use App\Observers\ATestObserver;
use App\Observers\CDiffObserver;
use App\Observers\CTestObserver;
use App\Observers\GradeObserver;
use App\Observers\PDiffObserver;
use App\Observers\PTestObserver;
use App\Models\CompanyRankDetail;
use App\Observers\CValueObserver;
use App\Observers\PValueObserver;
use App\Observers\SalaryObserver;
use App\Models\CompanyCenterPoint;
use App\Models\CompanyPerformance;
use App\Models\CompanyAllBonusList;
use App\Models\CompanyBonusSetting;
use App\Models\CompanySpecialBonus;
use App\Observers\EmployeeObserver;
use App\Models\CompanyBusinessProfit;
use App\Observers\CompetencyObserver;
use App\Observers\DepartmentObserver;
use Illuminate\Support\Facades\Blade;
use App\Models\CompanyBonusListDetail;
use App\Models\CompanyGuaranteedBonus;
use App\Models\CompanyManagementBonus;
use App\Observers\CenterPointObserver;
use App\Observers\CompanyRankObserver;
use App\Observers\DesignationObserver;
use Illuminate\Support\Facades\Schema;
use App\Models\CompanyBonusCalculation;
use App\Observers\CompanyADiffObserver;
use App\Observers\CompanyATestObserver;
use App\Observers\CompanyCDiffObserver;
use App\Observers\CompanyCTestObserver;
use App\Observers\CompanyDDiffObserver;
use App\Observers\CompanyDTestObserver;
use App\Observers\CompanyPDiffObserver;
use App\Observers\CompanyPTestObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Models\CompanySimpleMonthMethod;
use App\Observers\CompanyCValueObserver;
use App\Observers\CompanyPValueObserver;
use App\Observers\EmployeeScoreObserver;
use App\Models\CompanyComplexBonusMethod;
use App\Observers\CompanyBenefitObserver;
use App\Observers\EmployeeSalaryObserver;
use Illuminate\Support\Facades\Validator;
use App\Models\CompanyEvaluatingBonusList;

use App\Models\CompanyPerformanceBonusList;
use App\Observers\CompanyBonusListObserver;
use App\Observers\CompanyFrequencyObserver;
use App\Observers\CompanyRankDetailObserver;
use App\Observers\CompanyCenterPointObserver;
use App\Observers\CompanyPerformanceObserver;
use App\Observers\CompanyAllBonusListObserver;
use App\Observers\CompanyBonusSettingObserver;
use App\Observers\CompanySpecialBonusObserver;
use App\Observers\CompanyBusinessProfitObserver;
use App\Observers\CompanyBonusListDetailObserver;
use App\Observers\CompanyGuaranteedBonusObserver;
use App\Observers\CompanyManagementBonusObserver;
use App\Observers\CompanyBonusCalculationObserver;
use App\Observers\CompanySimpleMonthMethodObserver;
use App\Observers\CompanyComplexBonusMethodObserver;
use App\Observers\CompanyEvaluatingBonusListObserver;
use App\Observers\CompanyPerformanceBonusListObserver;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //測試使用 避免N+1
        Model::preventLazyLoading(!app()->isProduction());

        Schema::defaultStringLength(191);
        Department::observe(DepartmentObserver::class);
        Competency::observe(CompetencyObserver::class);
        Grade::observe(GradeObserver::class);
        Employee::observe(EmployeeObserver::class);
        Designation::observe(DesignationObserver::class);
        Salary::observe(SalaryObserver::class);
        CompanyFrequency::observe(CompanyFrequencyObserver::class);
        ATest::observe(ATestObserver::class);
        CompanyPerformance::observe(CompanyPerformanceObserver::class);
        ADiff::observe(ADiffObserver::class);
        CValue::observe(CValueObserver::class);
        CTest::observe(CTestObserver::class);
        CDiff::observe(CDiffObserver::class);
        PValue::observe(PValueObserver::class);
        PTest::observe(PTestObserver::class);
        PDiff::observe(PDiffObserver::class);
        CenterPoint::observe(CenterPointObserver::class);
        CompanyCenterPoint::observe(CompanyCenterPointObserver::class);
        EmployeeSalary::observe(EmployeeSalaryObserver::class);
        CompanySimpleMonthMethod::observe(CompanySimpleMonthMethodObserver::class);
        CompanyRank::observe(CompanyRankObserver::class);
        CompanyRankDetail::observe(CompanyRankDetailObserver::class);
        CompanyBonusList::observe(CompanyBonusListObserver::class);
        CompanyComplexBonusMethod::observe(CompanyComplexBonusMethodObserver::class);
        EmployeeScore::observe(EmployeeScoreObserver::class);
        CompanySpecialBonus::observe(CompanySpecialBonusObserver::class);
        CompanyManagementBonus::observe(CompanyManagementBonusObserver::class);
        CompanyBusinessProfit::observe(CompanyBusinessProfitObserver::class);
        CompanyBenefit::observe(CompanyBenefitObserver::class);
        CompanyGuaranteedBonus::observe(CompanyGuaranteedBonusObserver::class);
        CompanyBonusSetting::observe(CompanyBonusSettingObserver::class);
        CompanyPerformanceBonusList::observe(CompanyPerformanceBonusListObserver::class);
        CompanyEvaluatingBonusList::observe(CompanyEvaluatingBonusListObserver::class);
        CompanyCValue::observe(CompanyCValueObserver::class);
        CompanyCTest::observe(CompanyCTestObserver::class);
        CompanyCDiff::observe(CompanyCDiffObserver::class);
        CompanyPValue::observe(CompanyPValueObserver::class);
        CompanyPTest::observe(CompanyPTestObserver::class);
        CompanyPDiff::observe(CompanyPDiffObserver::class);

        CompanyATest::observe(CompanyATestObserver::class);
        CompanyADiff::observe(CompanyADiffObserver::class);
        CompanyDTest::observe(CompanyDTestObserver::class);
        CompanyDDiff::observe(CompanyDDiffObserver::class);

        CompanyBonusCalculation::observe(CompanyBonusCalculationObserver::class);
        CompanyAllBonusList::observe(CompanyAllBonusListObserver::class);
        CompanyBonusListDetail::observe(CompanyBonusListDetailObserver::class);
    }
}
