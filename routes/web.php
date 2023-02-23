<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ADiffController;
use App\Http\Controllers\Admin\ATestController;
use App\Http\Controllers\Admin\CDiffController;
use App\Http\Controllers\Admin\CTestController;
use App\Http\Controllers\Admin\PDiffController;
use App\Http\Controllers\Admin\PTestController;
use App\Http\Controllers\Front\LoginController;
use App\Http\Controllers\Admin\CValueController;
use App\Http\Controllers\Admin\GradesController;
use App\Http\Controllers\Admin\PValueController;
use App\Http\Controllers\Admin\SalariesController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\SuperAdmin\RankController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\ATestCDiffController;
use App\Http\Controllers\Admin\ATestCTestController;
use App\Http\Controllers\Admin\ATestPDiffController;
use App\Http\Controllers\Admin\ATestPTestController;
use App\Http\Controllers\SuperAdmin\PlansController;
use App\Http\Controllers\Admin\CenterPointController;
use App\Http\Controllers\Admin\CompanyRankController;
use App\Http\Controllers\Admin\DepartmentsController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\CompanyADiffController;
use App\Http\Controllers\Admin\CompanyATestController;
use App\Http\Controllers\Admin\CompanyBonusController;
use App\Http\Controllers\Admin\CompanyCDiffController;
use App\Http\Controllers\Admin\CompanyCTestController;
use App\Http\Controllers\Admin\CompanyDDiffController;
use App\Http\Controllers\Admin\CompanyDTestController;

use App\Http\Controllers\Admin\CompanyPDiffController;
use App\Http\Controllers\Admin\CompanyPTestController;
use App\Http\Controllers\Admin\CompetenciesController;
use App\Http\Controllers\SuperAdmin\BenefitController;
use App\Http\Controllers\SuperAdmin\RevenueController;
use App\Http\Controllers\Admin\CompanyCValueController;
use App\Http\Controllers\Admin\CompanyPValueController;
use App\Http\Controllers\Admin\CompanyReportController;
use App\Http\Controllers\Admin\EmployeeScoreController;
use App\Http\Controllers\Admin\CompanyBenefitController;
use App\Http\Controllers\SuperAdmin\CompaniesController;
use App\Http\Controllers\Admin\CompanyBonusListController;
use App\Http\Controllers\Admin\CompanyFrequencyController;
use App\Http\Controllers\SuperAdmin\PerformanceController;
use App\Http\Controllers\Admin\CompanyRankDetailController;
use App\Http\Controllers\Admin\EmployeeBonusListController;

use App\Http\Controllers\Admin\CompanyCenterPointController;
use App\Http\Controllers\Admin\CompanyPerformanceController;
use App\Http\Controllers\Admin\CompanyAllBonusListController;
use App\Http\Controllers\Admin\CompanyBonusSpecialController;
use App\Http\Controllers\Admin\CompanySpecialBonusController;
use App\Http\Controllers\Admin\EmployeeBonusDetailController;
use App\Http\Controllers\SuperAdmin\BusinessProfitController;
use App\Http\Controllers\Admin\CompanyBonusPersonalController;
use App\Http\Controllers\SuperAdmin\SuperAdminLoginController;
use App\Http\Controllers\SuperAdmin\SuperAdminUsersController;
use App\Http\Controllers\Admin\CompanyBusinessProfitController;
use App\Http\Controllers\Admin\CompanyBonusListDetailController;
use App\Http\Controllers\Admin\CompanyBonusManagementController;
use App\Http\Controllers\Admin\CompanyEvaluatingBonusController;
use App\Http\Controllers\Admin\CompanyGuaranteedBonusController;
use App\Http\Controllers\Admin\CompanyManagementBonusController;
use App\Http\Controllers\Admin\CompanyBonusCalculationController;
use App\Http\Controllers\Admin\CompanyBonusOverAllListController;
use App\Http\Controllers\Admin\CompanyPerformanceBonusController;
use App\Http\Controllers\Admin\CompanyATestCompanyCTestController;
use App\Http\Controllers\Admin\CompanyBonusDistributionController;
use App\Http\Controllers\Admin\CompanyDTestCompanyPTestController;
use App\Http\Controllers\Admin\CompanySimpleMonthMethodController;
use App\Http\Controllers\Admin\CompanyTotalAmountMethodController;
use App\Http\Controllers\Admin\CompanyComplexBonusMethodController;
use App\Http\Controllers\Admin\CompanySpecialBonusDetailController;
use App\Http\Controllers\Admin\CompanyEvaluatingBonusListController;
use App\Http\Controllers\Admin\CompanyPerformanceBonusListController;
use App\Http\Controllers\Admin\CompanyManagementBonusDetailController;
use App\Http\Controllers\Admin\CompanyComplexBonusMethodDetailController;


/*------------------------------------
  Employee LOGIN ROUTE
-------------------------------------*/

Route::group(['as' => 'front.'], function () {
    Route::get('/', [LoginController::class, 'index'])->name('getlogin');
    Route::post('/login', [LoginController::class, 'ajaxLogin'])->name('login');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});


# Employee Panel After Login
Route::group(['middleware' => ['auth.employee'], 'namespace' => 'Front'], function () {
    Route::resource('dashboard', '\App\Http\Controllers\Front\DashboardController');
    // Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});


/*------------------------------------
  ADMIN LOGIN ROUTE
-------------------------------------*/
Route::group(['prefix' => 'member', 'namespace' => 'Admin', 'as' => 'member.'], function () {
    Route::get('/', [AdminLoginController::class, 'index'])->name('getlogin');
    Route::get('logout', [AdminLoginController::class, 'logout'])->name('logout');
    Route::post('login', [AdminLoginController::class, 'ajaxAdminLogin'])->name('login');
});

// Admin Panel After Login
Route::group(['prefix' => 'member', 'middleware' => ['auth.admin'],  'namespace' => 'Admin', 'as' => 'member.'], function () {
    //	Dashboard Routing
    Route::resource('dashboard', '\App\Http\Controllers\Admin\AdminDashboardController');

    //  Employees Routing
    // Route::get('employees/export',[EmployeesController::class,'export'] ,['as' => 'admin.employees.export']);
    // Route::get('employees/employeeLogin/{id}',[EmployeesController::class,'employeesLogin'] , ['as' => 'admin.employees.employeeLogin']);
    // Route::get('employees/employeelist',[EmployeesController::class,'ajaxEmployees'] , ['as' => 'admin.employees.ajaxlist']);

    Route::get('employees/ajax_employees/', [EmployeesController::class, 'ajax_employees'])->name('employees.ajax_employees');
    Route::get('employees/edit/{id}', [EmployeesController::class, 'edit'])->name('employees.edit');
    Route::resource('employees', '\App\Http\Controllers\Admin\EmployeesController', ['except' => ['show', 'edit']]);



    // 員工績效考核
    Route::get('employee_scores/ajax_employee_score/', [EmployeeScoreController::class, 'ajax_employee_score'])->name('employee_scores.ajax_employee_score');

    Route::post('employee_scores/is_management/{id}', [EmployeeScoreController::class, 'changeManagement'])->name('employee_scores.changeManagement');
    Route::post('employee_scores/is_guaranteed/{id}', [EmployeeScoreController::class, 'changeGuaranteed'])->name('employee_scores.changeGuaranteed');
    Route::post('employee_scores/is_special/{id}', [EmployeeScoreController::class, 'changeSpecial'])->name('employee_scores.changeSpecial');


    // 全體更新，暫時採用
    Route::post('employee_scores/update_all', [EmployeeScoreController::class, 'updateAll'])->name('employee_scores.updateAll');

    Route::resource('employee_scores', '\App\Http\Controllers\Admin\EmployeeScoreController', ['except' => ['show']]);





    //  Department Routing
    Route::get('departments/ajax_department/', [DepartmentsController::class, 'ajaxDepartments'])->name('departments.ajax_department');

    // Route::put('departments/ajaxUpdate/{id}', [DepartmentsController::class, 'ajaxUpdate'])->name('departments.ajaxUpdate');
    Route::resource('departments', '\App\Http\Controllers\Admin\DepartmentsController', ['except' => ['show']]);


    //  Competency Routing
    Route::get('competencies/ajax_competency/', [CompetenciesController::class, 'ajaxCompetencies'])->name('competencies.ajax_competency');
    Route::resource('competencies', '\App\Http\Controllers\Admin\CompetenciesController', ['except' => ['show']]);


    //  Grade Routing
    Route::get('grades/ajax_designation/', [GradesController::class, 'ajax_designation'])->name('grades.ajax_designation');
    Route::get('grades/ajax_grade/', [GradesController::class, 'ajaxGrades'])->name('grades.ajax_grade');
    Route::get('grades/designation/', [GradesController::class, 'designation'])->name('grades.designation');

    Route::get('/grades/getGradesByDesignation', [GradesController::class, 'getGradesByDesignation'])->name('grades.getGradesByDesignation');


    Route::resource('grades', '\App\Http\Controllers\Admin\GradesController', ['except' => ['show']]);


    Route::get('/designations', [DesignationController::class, 'index'])->name('designations.index');
    Route::get('/designations/getDesignations', [DesignationController::class, 'getDesignations'])->name('designations.getDesignations');



    //  salary Routing 公司的薪資架構//基本薪資等
    Route::get('salaries/ajax_salary/', [SalariesController::class, 'ajaxSalaries'])->name('salaries.ajax_salary');
    Route::resource('salaries', '\App\Http\Controllers\Admin\SalariesController', ['except' => ['show']]);

    Route::post('salaries/is_generally/{id}', [SalariesController::class, 'changeGenerally'])->name('salaries.changeGenerally');
    Route::post('salaries/is_bonus/{id}', [SalariesController::class, 'changeBonus'])->name('salaries.changeBonus');
    Route::post('salaries/is_raise/{id}', [SalariesController::class, 'changeRaise'])->name('salaries.changeRaise');
    Route::post('salaries/is_promotion/{id}', [SalariesController::class, 'changePromotion'])->name('salaries.changePromotion');
    Route::post('salaries/is_performance/{id}', [SalariesController::class, 'changePerformance'])->name('salaries.changePerformance');



    //  Frequency Routing
    Route::get('frequencies/ajax_frequency/', [CompanyFrequencyController::class, 'ajaxFrequencies'])->name('frequencies.ajax_frequency');
    Route::resource('frequencies', '\App\Http\Controllers\Admin\CompanyFrequencyController', ['except' => ['show']]);

    //公司獎金設定表

    Route::get('bonus_settings/ajax_bonus_settings/', [CompanyBonusController::class, 'ajax_index'])->name('bonus_settings.ajax_bonus_settings');
    Route::resource('bonus_settings', '\App\Http\Controllers\Admin\CompanyBonusController');

    // 編輯獎金設定名稱
    Route::get('bonus_settings/edit_name/{id}', [CompanyBonusController::class, 'edit_name'])->name('bonus_settings.edit_name');
    Route::PUT('bonus_settings/edit_name/{id}', [CompanyBonusController::class, 'update_name'])->name('bonus_settings.update_name');


    //獎金設定
    Route::get('bonus_calculation/ajax_index/', [CompanyBonusCalculationController::class, 'ajax_index'])->name('bonus_calculation.ajax_index');

    Route::resource('bonus_calculation', '\App\Http\Controllers\Admin\CompanyBonusCalculationController');


    // C P value
    // 增加 cvalue 和產生間距ctest ,cdiff 為 ctest間距
    Route::get('cvalues/ajax_cvalues/', [CValueController::class, 'ajax_cvalues'])->name('cvalues.ajax_cvalues');
    Route::resource('cvalues', '\App\Http\Controllers\Admin\CValueController');

    Route::get('c_tests/ajax_ctests/', [CTestController::class, 'ajax_ctests'])->name('ctests.ajax_ctests');
    Route::resource('ctests', '\App\Http\Controllers\Admin\CTestController');

    Route::post('cvalues/changeCenter/{id}', [CValueController::class, 'changeCenter'])->name('cvalues.changeCenter');


    // // C 模擬用
    // Route::get('company_cvalues/ajax_cvalues/{id}', [CompanyCValueController::class, 'ajax_cvalues'])->name('company_cvalues.ajax_cvalues');
    // // Route::resource('company_cvalues', '\App\Http\Controllers\Admin\CompanyCValueController');
    // Route::get('company_cvalues/{id}', [CompanyCValueController::class, 'index'])->name('company_cvalues.index');
    // Route::get('company_cvalues/create/{id}', [CompanyCValueController::class, 'create'])->name('company_cvalues.create');
    // Route::post('company_cvalues/{id}', [CompanyCValueController::class, 'store'])->name('company_cvalues.store');

    // Route::get('company_cvalues/edit/{setting_id}/{id}', [CompanyCValueController::class, 'edit'])->name('company_cvalues.edit');
    // Route::put('company_cvalues/{setting_id}/{id}', [CompanyCValueController::class, 'update'])->name('company_cvalues.update');
    // 模擬用 當選擇項目時

    // CR 和 P 的中間值
    Route::get('company_center_points/ajax_center_points/{category}', [CompanyCenterPointController::class, 'ajax_center_points'])->name('company_center_points.ajax_center_points');

    Route::get('company_center_points/category/{category}', [CompanyCenterPointController::class, 'categoryCreate'])->name('company_center_points.categoryCreate');

    Route::get('company_center_points/category', [CompanyCenterPointController::class, 'category']);

    Route::post('company_center_points/changeStatus/{id}', [CompanyCenterPointController::class, 'changeStatus'])->name('company_center_points.changeStatus');

    Route::resource('company_center_points', '\App\Http\Controllers\Admin\CompanyCenterPointController');

    Route::get('company_cvalues/ajax_cvalues/{id}', [CompanyCValueController::class, 'ajax_cvalues'])->name('company_cvalues.ajax_cvalues');
    // Route::resource('company_cvalues', '\App\Http\Controllers\Admin\CompanyCValueController');

    Route::get('company_cvalues/{id}', [CompanyCValueController::class, 'index'])->name('company_cvalues.index');
    Route::get('company_cvalues/create/{id}', [CompanyCValueController::class, 'create'])->name('company_cvalues.create');
    Route::post('company_cvalues/{id}', [CompanyCValueController::class, 'store'])->name('company_cvalues.store');
    Route::get('company_cvalues/edit/{id}', [CompanyCValueController::class, 'edit'])->name('company_cvalues.edit');
    Route::PUT('company_cvalues/{id}', [CompanyCValueController::class, 'update'])->name('company_cvalues.update');

    Route::DELETE('company_cvalues/{id}', [CompanyCValueController::class, 'destroy'])->name('company_cvalues.destroy');
    // Route::resource('company_cvalues', '\App\Http\Controllers\Admin\CompanyCValueController');
    // Route::resource('company_cvalues', '\App\Http\Controllers\Admin\CompanyCValueController');
    // Route::resource('company_cvalues', '\App\Http\Controllers\Admin\CompanyCValueController');
    // Route::resource('company_cvalues', '\App\Http\Controllers\Admin\CompanyCValueController');

    Route::get('company_ctests/ajax_ctests/{id}', [CompanyCTestController::class, 'ajax_ctests'])->name('company_ctests.ajax_ctests');


    // Route::resource('company_ctests', '\App\Http\Controllers\Admin\CompanyCTestController');
    Route::get('company_ctests/{id}', [CompanyCTestController::class, 'index'])->name('company_ctests.index');
    Route::get('company_ctests/create/{id}', [CompanyCTestController::class, 'create'])->name('company_ctests.create');
    Route::post('company_ctests/{id}', [CompanyCTestController::class, 'store'])->name('company_ctests.store');
    Route::get('company_ctests/edit/{id}', [CompanyCTestController::class, 'edit'])->name('company_ctests.edit');
    Route::PUT('company_ctests/{id}', [CompanyCTestController::class, 'update'])->name('company_ctests.update');

    Route::DELETE('company_ctests/{id}', [CompanyCTestController::class, 'destroy'])->name('company_ctests.destroy');


    Route::post('company_cvalues/changeCenter/{id}', [CompanyCValueController::class, 'changeCenter'])->name('company_cvalues.changeCenter');


    Route::get('company_cdiffs/ajax_cdiffs/{id}', [CompanyCDiffController::class, 'ajax_cdiffs'])->name('company_cdiffs.ajax_cdiffs');
    // Route::resource('company_cdiffs', '\App\Http\Controllers\Admin\CompanyCDiffController');


    Route::get('company_cdiffs/{id}', [CompanyCDiffController::class, 'index'])->name('company_cdiffs.index');
    Route::get('company_cdiffs/create/{id}', [CompanyCDiffController::class, 'create'])->name('company_cdiffs.create');
    Route::post('company_cdiffs/{id}', [CompanyCDiffController::class, 'store'])->name('company_cdiffs.store');
    Route::get('company_cdiffs/edit/{id}', [CompanyCDiffController::class, 'edit'])->name('company_cdiffs.edit');
    Route::PUT('company_cdiffs/{id}', [CompanyCDiffController::class, 'update'])->name('company_cdiffs.update');

    Route::DELETE('company_cdiffs/{id}', [CompanyCDiffController::class, 'destroy'])->name('company_cdiffs.destroy');


    // Cvalue 模擬 使用 A
    Route::get('company_atests/ajax_atests/{id}', [CompanyATestController::class, 'ajax_atests'])->name('company_atests.ajax_atests');
    // Route::resource('company_atests', '\App\Http\Controllers\Admin\CompanyATestController');

    Route::get('company_atests/{id}', [CompanyATestController::class, 'index'])->name('company_atests.index');
    Route::get('company_atests/create/{id}', [CompanyATestController::class, 'create'])->name('company_atests.create');
    Route::post('company_atests/{id}', [CompanyATestController::class, 'store'])->name('company_atests.store');
    Route::get('company_atests/edit/{id}', [CompanyATestController::class, 'edit'])->name('company_atests.edit');
    Route::PUT('company_atests/{id}', [CompanyATestController::class, 'update'])->name('company_atests.update');

    Route::DELETE('company_atests/{id}', [CompanyATestController::class, 'destroy'])->name('company_atests.destroy');

    //company_adiffs
    Route::get('company_adiffs/ajax_adiffs/{id}', [CompanyADiffController::class, 'ajax_adiffs'])->name('company_adiffs.ajax_adiffs');
    // Route::resource('company_adiffs', '\App\Http\Controllers\Admin\CompanyADiffController');

    Route::get('company_adiffs/{id}', [CompanyADiffController::class, 'index'])->name('company_adiffs.index');
    Route::get('company_adiffs/create/{id}', [CompanyADiffController::class, 'create'])->name('company_adiffs.create');
    Route::post('company_adiffs/{id}', [CompanyADiffController::class, 'store'])->name('company_adiffs.store');
    Route::get('company_adiffs/edit/{id}', [CompanyADiffController::class, 'edit'])->name('company_adiffs.edit');
    Route::PUT('company_adiffs/{id}', [CompanyADiffController::class, 'update'])->name('company_adiffs.update');

    Route::DELETE('company_adiffs/{id}', [CompanyADiffController::class, 'destroy'])->name('company_adiffs.destroy');



    // p value p value 產生 p test,p diff
    Route::get('pvalues/ajax_pvalues/', [PValueController::class, 'ajax_pvalues'])->name('pvalues.ajax_pvalues');
    Route::resource('pvalues', '\App\Http\Controllers\Admin\PValueController');

    Route::post('pvalues/changeCenter/{id}', [PValueController::class, 'changeCenter'])->name('pvalues.changeCenter');


    Route::get('p_tests/ajax_ptests/', [PTestController::class, 'ajax_ptests'])->name('ptests.ajax_ptests');
    Route::resource('ptests', '\App\Http\Controllers\Admin\PTestController');


    //p 模擬用

    Route::get('company_pvalues/ajax_pvalues/{id}', [CompanyPValueController::class, 'ajax_pvalues'])->name('company_pvalues.ajax_pvalues');
    // Route::resource('company_pvalues', '\App\Http\Controllers\Admin\CompanyPValueController');

    Route::get('company_pvalues/{id}', [CompanyPValueController::class, 'index'])->name('company_pvalues.index');
    Route::get('company_pvalues/create/{id}', [CompanyPValueController::class, 'create'])->name('company_pvalues.create');
    Route::post('company_pvalues/{id}', [CompanyPValueController::class, 'store'])->name('company_pvalues.store');
    Route::get('company_pvalues/edit/{id}', [CompanyPValueController::class, 'edit'])->name('company_pvalues.edit');
    Route::PUT('company_pvalues/{id}', [CompanyPValueController::class, 'update'])->name('company_pvalues.update');


    Route::DELETE('company_pvalues/{id}', [CompanyPValueController::class, 'destroy'])->name('company_pvalues.destroy');


    Route::post('company_pvalues/changeCenter/{id}', [CompanyPValueController::class, 'changeCenter'])->name('company_pvalues.changeCenter');


    Route::get('company_ptests/ajax_ptests/{id}', [CompanyPTestController::class, 'ajax_ptests'])->name('company_ptests.ajax_ptests');
    // Route::resource('company_ptests', '\App\Http\Controllers\Admin\CompanyPTestController');

    Route::get('company_ptests/{id}', [CompanyPTestController::class, 'index'])->name('company_ptests.index');
    Route::get('company_ptests/create/{id}', [CompanyPTestController::class, 'create'])->name('company_ptests.create');
    Route::post('company_ptests/{id}', [CompanyPTestController::class, 'store'])->name('company_ptests.store');
    Route::get('company_ptests/edit/{id}', [CompanyPTestController::class, 'edit'])->name('company_ptests.edit');
    Route::PUT('company_ptests/{id}', [CompanyPTestController::class, 'update'])->name('company_ptests.update');


    Route::DELETE('company_ptests/{id}', [CompanyPTestController::class, 'destroy'])->name('company_ptests.destroy');




    Route::get('company_pdiffs/ajax_pdiffs/{id}', [CompanyPDiffController::class, 'ajax_pdiffs'])->name('company_pdiffs.ajax_pdiffs');
    // Route::resource('company_pdiffs', '\App\Http\Controllers\Admin\CompanyPDiffController');
    Route::get('company_pdiffs/{id}', [CompanyPDiffController::class, 'index'])->name('company_pdiffs.index');
    Route::get('company_pdiffs/create/{id}', [CompanyPDiffController::class, 'create'])->name('company_pdiffs.create');
    Route::post('company_pdiffs/{id}', [CompanyPDiffController::class, 'store'])->name('company_pdiffs.store');
    Route::get('company_pdiffs/edit/{id}', [CompanyPDiffController::class, 'edit'])->name('company_pdiffs.edit');
    Route::PUT('company_pdiffs/{id}', [CompanyPDiffController::class, 'update'])->name('company_pdiffs.update');


    Route::DELETE('company_pdiffs/{id}', [CompanyPDiffController::class, 'destroy'])->name('company_pdiffs.destroy');

    // Pvalue 模擬 使用 D
    Route::get('company_dtests/ajax_dtests/{id}', [CompanyDTestController::class, 'ajax_dtests'])->name('company_dtests.ajax_dtests');
    // Route::resource('company_dtests', '\App\Http\Controllers\Admin\CompanyDTestController');

    Route::get('company_dtests/{id}', [CompanyDTestController::class, 'index'])->name('company_dtests.index');
    Route::get('company_dtests/create/{id}', [CompanyDTestController::class, 'create'])->name('company_dtests.create');
    Route::post('company_dtests/{id}', [CompanyDTestController::class, 'store'])->name('company_dtests.store');
    Route::get('company_dtests/edit/{id}', [CompanyDTestController::class, 'edit'])->name('company_dtests.edit');
    Route::PUT('company_dtests/{id}', [CompanyDTestController::class, 'update'])->name('company_dtests.update');

    Route::DELETE('company_dtests/{id}', [CompanyDTestController::class, 'destroy'])->name('company_dtests.destroy');

    //company_adiffs
    Route::get('company_ddiffs/ajax_ddiffs/{id}', [CompanyDDiffController::class, 'ajax_ddiffs'])->name('company_ddiffs.ajax_ddiffs');
    // Route::resource('company_ddiffs', '\App\Http\Controllers\Admin\CompanyDDiffController');

    Route::get('company_ddiffs/{id}', [CompanyDDiffController::class, 'index'])->name('company_ddiffs.index');
    Route::get('company_ddiffs/create/{id}', [CompanyDDiffController::class, 'create'])->name('company_ddiffs.create');
    Route::post('company_ddiffs/{id}', [CompanyDDiffController::class, 'store'])->name('company_ddiffs.store');
    Route::get('company_ddiffs/edit/{id}', [CompanyDDiffController::class, 'edit'])->name('company_ddiffs.edit');
    Route::PUT('company_ddiffs/{id}', [CompanyDDiffController::class, 'update'])->name('company_ddiffs.update');

    Route::DELETE('company_ddiffs/{id}', [CompanyDDiffController::class, 'destroy'])->name('company_ddiffs.destroy');

    // DP company

    // 建立時同時比對
    Route::get('company_dptests/{id}', [CompanyDTestCompanyPTestController::class, 'index'])->name('company_dptest.index');


    // AC company

    // 建立時同時比對
    Route::get('company_actests/{id}', [CompanyATestCompanyCTestController::class, 'index'])->name('company_actest.index');

    // 編輯更新 actests 最後決定頁面
    Route::get('company_actests/{id}/edit', [CompanyATestCompanyCTestController::class, 'edit'])->name('company_actest.edit');

    Route::put('company_actests/{id}', [CompanyATestCompanyCTestController::class, 'update'])->name('company_actest.update');




    //4.1獎金分配表 - 績效評等範圍
    Route::get('a_tests/ajax_atests/', [ATestController::class, 'ajax_atests'])->name('atests.ajax_atests');
    Route::resource('atests', '\App\Http\Controllers\Admin\ATestController');

    Route::get('a_diffs/ajax_adiffs/', [ADiffController::class, 'ajax_adiffs'])->name('adiffs.ajax_adiffs');
    Route::resource('adiffs', '\App\Http\Controllers\Admin\ADiffController');

    //4.3獎金分配表 - CR法
    Route::get('c_diffs/ajax_cdiffs/', [CDiffController::class, 'ajax_cdiffs'])->name('cdiffs.ajax_cdiffs');
    Route::resource('cdiffs', '\App\Http\Controllers\Admin\CDiffController');


    //CR + 績效
    Route::get('actests', [ATestCTestController::class, 'index'])->name('actest.index');
    Route::get('actests/edit', [ATestCTestController::class, 'edit'])->name('actest.edit');
    Route::put('actests/update', [ATestCTestController::class, 'update'])->name('actest.update');

    // Route::get('acdiffs', [ATestCTestController::class, 'index'])->name('acdiff.index');
    // Route::get('acdiffs/edit', [ATestCTestController::class, 'edit'])->name('acdiff.edit');
    // Route::put('acdiffs/update', [ATestCTestController::class, 'update'])->name('acdiff.update');

    //4.4獎金分配表 - P值法
    Route::get('p_diffs/ajax_pdiffs/', [PDiffController::class, 'ajax_pdiffs'])->name('pdiffs.ajax_pdiffs');
    Route::resource('pdiffs', '\App\Http\Controllers\Admin\PDiffController');

    //P + 績效
    Route::get('aptests', [ATestPTestController::class, 'index'])->name('aptest.index');
    Route::get('aptests/edit', [ATestPTestController::class, 'edit'])->name('aptest.edit');
    Route::put('aptests/update', [ATestPTestController::class, 'update'])->name('aptest.update');

    // Route::get('apdiffs', [ATestPDiffController::class, 'index'])->name('apdiff.index');
    // Route::get('apdiffs/edit', [ATestPDiffController::class, 'edit'])->name('apdiff.edit');
    // Route::put('apdiffs/update', [ATestPDiffController::class, 'update'])->name('apdiff.update');

    // CR 和 P 的中間值
    Route::get('center_points/ajax_center_points/{category}', [CenterPointController::class, 'ajax_center_points'])->name('center_points.ajax_center_points');

    Route::get('center_points/category/{category}', [CenterPointController::class, 'categoryCreate'])->name('center_points.categoryCreate');

    Route::get('center_points/category', [CenterPointController::class, 'category']);

    Route::post('center_points/changeStatus/{id}', [CenterPointController::class, 'changeStatus'])->name('center_points.changeStatus');

    Route::resource('center_points', '\App\Http\Controllers\Admin\CenterPointController');


    // 公司獎金次數
    //    frequency
    // Route::resource('frequencies', '\App\Http\Controllers\Admin\CompanyFrequencyController');

    // 薪資表 Rank
    Route::get('ranks/ajax', [CompanyRankController::class, 'ajax_index'])->name('ranks.ajax_index');
    Route::resource('ranks', '\App\Http\Controllers\Admin\CompanyRankController');
    // 薪資表detail
    Route::get('rank_details/ajax/{id}', [CompanyRankDetailController::class, 'ajax_index'])->name('rank_details.ajax_index');
    // Route::resource('rank_details', '\App\Http\Controllers\Admin\CompanyRankDetailController');
    Route::get('rank_details/{id}', [CompanyRankDetailController::class, 'index'])->name('rank_details.index');
    Route::get('rank_details/edit/{id}', [CompanyRankDetailController::class, 'edit'])->name('rank_details.edit');
    Route::PUT('rank_details/{id}', [CompanyRankDetailController::class, 'update'])->name('rank_details.update');


    // *****績效評等範圍'
    Route::get('company_performances/ajax_performances/', [CompanyPerformanceController::class, 'ajax_performances'])->name('company_performances.ajax_performances');

    Route::resource('company_performances', '\App\Http\Controllers\Admin\CompanyPerformanceController');

    //1.1 獎金預算設定-總金額制
    //    totalAmount
    //ajax_bonus_settings
    Route::get('total_amount/ajax_index/', [CompanyTotalAmountMethodController::class, 'ajax_index'])->name('total_amount.ajax_index');

    Route::resource('total_amount', '\App\Http\Controllers\Admin\CompanyTotalAmountMethodController');

    //1.2 獎金預算設定-簡單月數制
    //    simpleMonth
    Route::get('simple_month/ajax', [CompanySimpleMonthMethodController::class, 'ajax_index'])->name('simple_month.ajax_index');

    Route::resource('simple_month', '\App\Http\Controllers\Admin\CompanySimpleMonthMethodController');

    //1.3 獎金預算設定-複雜月數制
    //    complexBonus
    Route::get('complex_bonus/ajax', [CompanyComplexBonusMethodController::class, 'ajax_index'])->name('complex_bonus.ajax_index');

    Route::resource('complex_bonus', '\App\Http\Controllers\Admin\CompanyComplexBonusMethodController');


    Route::get('complex_bonus_details/ajax/{id}', [CompanyComplexBonusMethodDetailController::class, 'ajax_index'])->name('complex_bonus_details.ajax_index');

    Route::get('complex_bonus_details/{id}', [CompanyComplexBonusMethodDetailController::class, 'index'])->name('complex_bonus_details.index');
    Route::get('complex_bonus_details/edit/{id}', [CompanyComplexBonusMethodDetailController::class, 'edit'])->name('complex_bonus_details.edit');
    Route::PUT('complex_bonus_details/{id}', [CompanyComplexBonusMethodDetailController::class, 'update'])->name('complex_bonus_details.update');


    //2. 事業部特別獎金
    //    special
    Route::get('special_bonus/ajax', [CompanySpecialBonusController::class, 'ajax_index'])->name('special_bonus.ajax_index');

    Route::resource('special_bonus', '\App\Http\Controllers\Admin\CompanySpecialBonusController');


    Route::get('special_bonus_details/ajax/{id}', [CompanySpecialBonusDetailController::class, 'ajax_index'])->name('special_bonus_details.ajax_index');

    Route::get('special_bonus_details/{id}', [CompanySpecialBonusDetailController::class, 'index'])->name('special_bonus_details.index');
    Route::get('special_bonus_details/edit/{id}', [CompanySpecialBonusDetailController::class, 'edit'])->name('special_bonus_details.edit');
    Route::PUT('special_bonus_details/{id}', [CompanySpecialBonusDetailController::class, 'update'])->name('special_bonus_details.update');



    //3. 管理職責任獎金
    //    management

    Route::get('management_bonus/ajax', [CompanyManagementBonusController::class, 'ajax_index'])->name('management_bonus.ajax_index');
    Route::resource('management_bonus', '\App\Http\Controllers\Admin\CompanyManagementBonusController');

    Route::get('management_bonus_details/ajax/{id}', [CompanyManagementBonusDetailController::class, 'ajax_index'])->name('management_bonus_details.ajax_index');

    Route::get('management_bonus_details/{id}', [CompanyManagementBonusDetailController::class, 'index'])->name('management_bonus_details.index');
    Route::get('management_bonus_details/edit/{id}', [CompanyManagementBonusDetailController::class, 'edit'])->name('management_bonus_details.edit');
    Route::PUT('management_bonus_details/{id}', [CompanyManagementBonusDetailController::class, 'update'])->name('management_bonus_details.update');

    // 保證獎金
    // guaranteed
    Route::get('guaranteed_bonus/ajax_index', [CompanyGuaranteedBonusController::class, 'ajax_index'])->name('guaranteed_bonus.ajax_index');
    Route::resource('guaranteed_bonus', '\App\Http\Controllers\Admin\CompanyGuaranteedBonusController');


    //4.1 個人獎金計算方式 績效結果法
    //  performance

    // 修改為 list + detail 內容
    Route::get('performance_bonus_list/ajax', [CompanyPerformanceBonusListController::class, 'ajax_index'])->name('performance_bonus_list.ajax_index');

    Route::resource('performance_bonus_list', '\App\Http\Controllers\Admin\CompanyPerformanceBonusListController');

    Route::get('performance_bonus/ajax', [CompanyPerformanceBonusController::class, 'ajax_index'])->name('performance_bonus.ajax_index');
    Route::resource('performance_bonus', '\App\Http\Controllers\Admin\CompanyPerformanceBonusController');

    //拆解成 details

    Route::get('performance_bonus_details/ajax/{id}', [CompanyPerformanceBonusController::class, 'ajax_index'])->name('performance_bonus_details.ajax_index');

    Route::get('performance_bonus_details/{id}', [CompanyPerformanceBonusController::class, 'index'])->name('performance_bonus_details.index');
    Route::get('performance_bonus_details/edit/{id}', [CompanyPerformanceBonusController::class, 'edit'])->name('performance_bonus_details.edit');
    Route::PUT('performance_bonus_details/{id}', [CompanyPerformanceBonusController::class, 'update'])->name('performance_bonus_details.update');


    //4.2 個人獎金計算方式 評價利益法
    //  evaluating

    // 修改為 list + detail 內容
    Route::get('evaluating_bonus_list/ajax', [CompanyEvaluatingBonusListController::class, 'ajax_index'])->name('evaluating_bonus_list.ajax_index');

    Route::resource('evaluating_bonus_list', '\App\Http\Controllers\Admin\CompanyEvaluatingBonusListController');


    Route::get('evaluating_bonus/ajax', [CompanyEvaluatingBonusController::class, 'ajax_index'])->name('evaluating_bonus.ajax_index');
    Route::resource('evaluating_bonus', '\App\Http\Controllers\Admin\CompanyEvaluatingBonusController');


    //拆解成 details

    Route::get('evaluating_bonus_details/ajax/{id}', [CompanyEvaluatingBonusController::class, 'ajax_index'])->name('evaluating_bonus_details.ajax_index');

    Route::get('evaluating_bonus_details/{id}', [CompanyEvaluatingBonusController::class, 'index'])->name('evaluating_bonus_details.index');
    Route::get('evaluating_bonus_details/edit/{id}', [CompanyEvaluatingBonusController::class, 'edit'])->name('evaluating_bonus_details.edit');
    Route::PUT('evaluating_bonus_details/{id}', [CompanyEvaluatingBonusController::class, 'update'])->name('evaluating_bonus_details.update');

    //獎金分配表 - 個人
    // Bonus distribution table ->bdt_personal
    Route::get('bdt_personal/ajax', [CompanyBonusPersonalController::class, 'ajax_index'])->name('bdt_personal.ajax_index');
    Route::resource('bdt_personal', '\App\Http\Controllers\Admin\CompanyBonusPersonalController');

    //獎金分配表 - 全社
    // bdt_company
    Route::get('bdt_company/ajax', [CompanyBonusDistributionController::class, 'ajax_index'])->name('bdt_company.ajax_index');
    Route::resource('bdt_company', '\App\Http\Controllers\Admin\CompanyBonusDistributionController');

    //獎金分配表 - 部門特別獎金
    // bdt_department_special
    Route::get('bdt_special/ajax', [CompanyBonusSpecialController::class, 'ajax_index'])->name('bdt_special.ajax_index');
    Route::resource('bdt_special', '\App\Http\Controllers\Admin\CompanyBonusSpecialController');

    //獎金分配表 - 管理職責任獎金
    // bdt_management
    Route::get('bdt_management/ajax', [CompanyBonusManagementController::class, 'ajax_index'])->name('bdt_management.ajax_index');
    Route::resource('bdt_management', '\App\Http\Controllers\Admin\CompanyBonusManagementController');

    //獎金明細表
    // employee_bonus_list

    Route::get('employee_bonus_list/ajax', [EmployeeBonusListController::class, 'ajax_index'])->name('employee_bonus_list.ajax_index');

    Route::resource('employee_bonus_list', '\App\Http\Controllers\Admin\EmployeeBonusListController');


    //增加 details

    Route::get('employee_bonus_details/ajax/{id}', [EmployeeBonusDetailController::class, 'ajax_index'])->name('employee_bonus_details.ajax_index');

    Route::get('employee_bonus_details/{id}', [EmployeeBonusDetailController::class, 'index'])->name('employee_bonus_details.index');
    // Route::get('employee_bonus_details/create/{id}', [EmployeeBonusDetailController::class, 'create'])->name('employee_bonus_details.create');
    // Route::post('employee_bonus_details/{id}', [EmployeeBonusDetailController::class, 'store'])->name('employee_bonus_details.store');
    Route::get('employee_bonus_details/edit/{id}', [EmployeeBonusDetailController::class, 'edit'])->name('employee_bonus_details.edit');
    Route::PUT('employee_bonus_details/{id}', [EmployeeBonusDetailController::class, 'update'])->name('employee_bonus_details.update');
    Route::DELETE('employee_bonus_details/{id}', [EmployeeBonusDetailController::class, 'destroy'])->name('employee_bonus_details.destroy');

    Route::post('employee_bonus_details/update_all/{bonus_id}', [EmployeeBonusDetailController::class, 'updateAll'])->name('employee_bonus_details.updateAll');



    // 獎金分配表 list
    Route::get('bonus_over_all_lists/ajax', [CompanyBonusOverAllListController::class, 'ajax_index'])->name('bonus_over_all_lists.ajax_index');

    Route::resource('bonus_over_all_lists', '\App\Http\Controllers\Admin\CompanyBonusOverAllListController');

    // bonus_list 模擬 all_bonus_list

    Route::get('all_bonus_list/ajax', [CompanyAllBonusListController::class, 'ajax_index'])->name('all_bonus_list.ajax_index');

    Route::resource('all_bonus_list', '\App\Http\Controllers\Admin\CompanyAllBonusListController');

    //增加 details

    Route::get('bonus_list_details/ajax/{id}', [CompanyBonusListDetailController::class, 'ajax_index'])->name('bonus_list_details.ajax_index');

    Route::get('bonus_list_details/{id}', [CompanyBonusListDetailController::class, 'index'])->name('bonus_list_details.index');
    // Route::get('bonus_list_details/create/{id}', [CompanyBonusListDetailController::class, 'create'])->name('bonus_list_details.create');
    // Route::post('bonus_list_details/{id}', [CompanyBonusListDetailController::class, 'store'])->name('bonus_list_details.store');
    Route::get('bonus_list_details/edit/{id}', [CompanyBonusListDetailController::class, 'edit'])->name('bonus_list_details.edit');
    Route::PUT('bonus_list_details/{id}', [CompanyBonusListDetailController::class, 'update'])->name('bonus_list_details.update');
    Route::DELETE('bonus_list_details/{id}', [CompanyBonusListDetailController::class, 'destroy'])->name('bonus_list_details.destroy');

    Route::post('bonus_list_details/update_all/{bonus_id}', [CompanyBonusListDetailController::class, 'updateAll'])->name('bonus_list_details.updateAll');




    // bonus_list 轉成 bonus
    Route::get('bonus_list/ajax', [CompanyBonusListController::class, 'ajax_index'])->name('bonus_list.ajax_index');
    Route::resource('bonus_list', '\App\Http\Controllers\Admin\CompanyBonusListController');

    Route::post('bonus_list/update_all', [CompanyBonusListController::class, 'updateAll'])->name('bonus_list.updateAll');

    Route::resource('all_bonus_list', '\App\Http\Controllers\Admin\CompanyAllBonusListController');


    //部門明細表
    // bonus_department_list
    Route::get('bonus_department_list/ajax', [CompanyBonusDepartmentListController::class, 'ajax_index'])->name('bonus_department_list.ajax_index');
    Route::resource('bonus_department_list', '\App\Http\Controllers\Admin\CompanyBonusDepartmentListController');

    //員工-績效
    // employee_performance
    Route::get('employee_performance/ajax', [CompanyEmployeePerformanceController::class, 'ajax_index'])->name('employee_performance.ajax_index');
    Route::resource('employee_performance', '\App\Http\Controllers\Admin\CompanyEmployeePerformanceController');

    //員工-薪資
    // employee_salary
    Route::get('employee_salary/ajax', [CompanyEmployeeSalaryController::class, 'ajax_index'])->name('employee_salary.ajax_index');
    Route::resource('employee_salary', '\App\Http\Controllers\Admin\CompanyEmployeeSalaryController');

    // 公司基本設定-獎金
    // CompanyBonusSetting
    Route::get('bonus_setting/ajax', [CompanyBonusSettingController::class, 'ajax_index'])->name('bonus_setting.ajax_index');
    Route::resource('bonus_setting', '\App\Http\Controllers\Admin\CompanyBonusSettingController');

    // 公司基本設定-調薪
    // CompanyRaiseSetting
    Route::get('raise_setting/ajax', [CompanyRaiseSettingController::class, 'ajax_index'])->name('raise_setting.ajax_index');
    Route::resource('raise_setting', '\App\Http\Controllers\Admin\CompanyRaiseSettingController');

    // benefit & BusinessProfit 評價利益法-參考
    // 事業利益
    Route::get('business_profit/ajax', [CompanyBusinessProfitController::class, 'ajax_index'])->name('business_profit.ajax_index');
    Route::resource('business_profit', '\App\Http\Controllers\Admin\CompanyBusinessProfitController');

    // 營業額
    Route::get('benefit/ajax', [CompanyBenefitController::class, 'ajax_index'])->name('benefit.ajax_index');
    Route::resource('benefit', '\App\Http\Controllers\Admin\CompanyBenefitController');


    Route::get('reports/depbase', [CompanyReportController::class, 'depbase'])->name('reports.depbase.index');
});


/*------------------------------------
 Super ADMIN LOGIN ROUTE
-------------------------------------*/
Route::group(['prefix' => 'super-admin', 'namespace' => 'SuperAdmin', 'as' => 'super-admin.'], function () {

    Route::get('/', [SuperAdminLoginController::class, 'index'])->name('getlogin');
    Route::get('logout', [SuperAdminLoginController::class, 'logout'])->name('logout');
    Route::post('login', [SuperAdminLoginController::class, 'ajaxAdminLogin'])->name('login');
});

Route::group(['prefix' => 'super-admin', 'middleware' => ['auth.super_admin'],  'namespace' => 'SuperAdmin', 'as' => 'super-admin.'], function () {

    //	Dashboard Routing
    Route::resource('dashboard', '\App\Http\Controllers\SuperAdmin\SuperAdminDashboardController');


    //Admin Users
    Route::get('ajax_superadmin_users/', [SuperAdminUsersController::class, 'ajax_superadmin_users'])->name('ajax_superadmin_users');
    Route::resource('superadmin_users', '\App\Http\Controllers\SuperAdmin\SuperAdminUsersController');

    //  Company
    // Route::get('company_theme/', [CompaniesController::class, 'theme'])->name('companies.theme');
    Route::get('ajax_company/', [CompaniesController::class, 'ajax_company'])->name('companies.ajax_company');
    Route::get('change_company/', [CompaniesController::class, 'change_company'])->name('change_company');
    Route::post('change_status/', [CompaniesController::class, 'change_status'])->name('companies.change_status');
    Route::get('companies/editPackage/{companyId}', [CompaniesController::class, 'editPackage'])->name('companies.edit-package.get');
    Route::put('companies/editPackage/{companyId}', [CompaniesController::class, 'updatePackage'])->name('companies.edit-package.post');

    Route::get('companies/editModel/{companyId}', [CompaniesController::class, 'editModel'])->name('companies.edit-model.get');
    Route::put('companies/editModel/{companyId}', [CompaniesController::class, 'updateModel'])->name('companies.edit-model.post');

    Route::resource('companies', '\App\Http\Controllers\SuperAdmin\CompaniesController');


    // plan
    Route::get('ajax_plans/', [PlansController::class, 'ajax_plans'])->name('plans');
    Route::resource('plans', '\App\Http\Controllers\SuperAdmin\PlansController');

    //Admin Users
    Route::get('ajax_superadmin_users/', [SuperAdminUsersController::class, 'ajax_superadmin_users'])->name('ajax_superadmin_users');
    Route::resource('superadmin_users', '\App\Http\Controllers\SuperAdmin\SuperAdminUsersController');

    // Ranks Routing
    Route::get('ajax_ranks/', [RankController::class, 'ajax_ranks'])->name('ajax_ranks');
    Route::resource('ranks', '\App\Http\Controllers\SuperAdmin\RankController');


    // Performances
    Route::post('ajax_performances/', [PerformanceController::class, 'ajax_performances'])->name('ajax_performances');

    Route::get('performances/editDifference/{id}', [PerformanceController::class, 'editDifference'])->name('performances.editDifference');
    Route::put('performances/editDifference/{id}', [PerformanceController::class, 'updateDifference'])->name('performances.updateDifference');

    Route::resource('performances', '\App\Http\Controllers\SuperAdmin\PerformanceController');

    //benefits
    Route::get('ajax_benefits/', [BenefitController::class, 'ajax_benefits'])->name('ajax_benefits');
    Route::resource('benefits', '\App\Http\Controllers\SuperAdmin\BenefitController');

    // 事業
    Route::get('ajax_revenue/', [RevenueController::class, 'ajax_revenues'])->name('ajax_revenues');
    Route::resource('revenues', '\App\Http\Controllers\SuperAdmin\RevenueController');

    // business
    Route::get('ajax_businessProfit/', [BusinessProfitController::class, 'ajax_businessProfit'])->name('ajax_businessProfit');
    Route::resource('business_profits', '\App\Http\Controllers\SuperAdmin\BusinessProfitController');


    Route::get('ajax_cvalues/', ['\App\Http\Controllers\SuperAdmin\CValueController', 'ajax_cvalues'])->name('ajax_cvalues');
    Route::resource('cvalues', '\App\Http\Controllers\SuperAdmin\CValueController');

    Route::get('ajax_pvalues/', ['\App\Http\Controllers\SuperAdmin\PValueController', 'ajax_pvalues']);
    Route::resource('pvalues', '\App\Http\Controllers\SuperAdmin\PValueController');


    //   Routing for setting
    Route::resource('settings', '\App\Http\Controllers\SuperAdmin\SettingsController', ['only' => ['index', 'edit', 'update']]);

    //ccc


});
