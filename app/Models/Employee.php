<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\UserTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class Employee extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use HasFactory;
    use Sluggable, SluggableScopeHelpers;
    use Authenticatable, Authorizable, CanResetPassword;

    protected static function boot()
    {
        parent::boot();


        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                $builder->where('employees.company_id', admin()->company_id);
            }
        });
    }



    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['employeeId']
            ]
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'employeeID';
    }

    // Don't forget to fill this array
    protected $fillable = ['employeeID', 'department_id', 'designation_id', 'fullName', 'fatherName', 'gender', 'email', 'password', 'date_of_birth', 'mobileNumber', 'localAddress', 'joiningDate', 'permanentAddress', 'score', 'slug'];
    protected $guarded = ['id'];

    protected $hidden = ['password'];
    protected $dates = [
        'created_at',
        'updated_at',
        'last_login',
        'joining_date',
        'exit_date',
        'date_of_birth',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function bonus()
    {
        return $this->hasOne(CompanyBonusList::class,  'employee_id', 'id');
    }

    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class);
    }

    public function employee_score()
    {
        return $this->hasOne('App\Models\EmployeeScore');
    }

    public function employee_salaries()
    {
        return $this->hasMany('App\Models\EmployeeSalary');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class,  'designation_id', 'id');
    }


    public function department()
    {
        return $this->belongsTo(Department::class,  'department_id', 'id');
    }
    public static function currentMonthBirthday($company_id)
    {
        $birthdays = Employee::where('company_id', $company_id)->select('full_name', 'date_of_birth', 'profile_image')
            ->whereRaw("MONTH(date_of_birth) = ?", [date('m')])->where('status', '=', 'active')
            ->orderBy('date_of_birth', 'asc')->get();

        return $birthdays;
    }


    public function scopeManager($query)
    {
        if (admin()->manager == 1) {
            return $query->join('designations', 'designations.id', '=', 'employees.designation_id')
                ->join('grades', 'designations.grade_id', '=', 'grades.id')
                ->join('department_managers', 'department_managers.department_id', '=', 'departments.id')
                ->join('admins', 'admins.id', '=', 'department_managers.manager_id')
                ->join('departments', 'departments.id', '=', 'employees.department_id')
                ->where('department_managers.manager_id', '=', admin()->id);
        }
        return $query->join('designations', 'designations.id', '=', 'employees.designation_id')
            ->join('grades', 'designations.grade_id', '=', 'grades.id')
            ->join('departments', 'departments.id', '=', 'employees.department_id');
    }





    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = date('Y-m-d', strtotime($value));
    }

    public function setJoiningDateAttribute($value)
    {
        $this->attributes['joining_date'] = date('Y-m-d', strtotime($value));
    }


    public $preventAttrSet = false;

    public function toPortableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }

    public function getEncrypted()
    {
        return $this->encrypted;
    }






    /**
     * Return Model in array type, with all datas decrypted.
     * @return array
     */
    public function decryptToArray()
    {
        $model = [];
        foreach ($this->attributes as $attributeKey => $attributeValue) {
            $model[$attributeKey] = $this->$attributeKey;
        }

        return $model;
    }

    /**
     * Return Model in collection type, with all datas decrypted.
     * @return array
     */
    public function decryptToCollection()
    {
        $model = collect();
        foreach ($this->attributes as $attributeKey => $attributeValue) {
            $model->$attributeKey = $this->$attributeKey;
        }

        return $model;
    }

    protected $encrypted = [
        'fullName',
        'mobile_number',
        'father_name',
        'local_address',
        'permanent_address'
    ];
}
