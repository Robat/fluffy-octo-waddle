<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ATest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'numbering', 'rank_from', 'rank_to', 'sort'];


    protected static function boot()
    {
        parent::boot();


        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {


                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('a_tests.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }

    public function scopeCompany($query, $id)
    {
        return $query->where('company_frequencies.company_id', '=', $id);
    }

    public function c_tests()
    {
        return $this->belongsToMany(CTest::class)->where('a_test_c_test.frequency_id', '=', admin()->company->frequency()->id)->withPivot('score', 'c_sort', 'a_sort')->withTimestamps();
    }

    public function c_diffs()
    {
        return $this->belongsToMany(CDiff::class)->where('a_test_c_diff.frequency_id', '=', admin()->company->frequency()->id)->withPivot('score', 'c_sort', 'a_sort')->withTimestamps();
    }


    public function p_tests()
    {
        return $this->belongsToMany(PTest::class)->where('a_test_p_test.frequency_id', '=', admin()->company->frequency()->id)->withPivot('score', 'p_sort', 'a_sort')->withTimestamps();
    }

    public function p_diffs()
    {
        return $this->belongsToMany(PDiff::class)->where('a_test_p_diff.frequency_id', '=', admin()->company->frequency()->id)->withPivot('score', 'p_sort', 'a_sort')->withTimestamps();
    }


    public function updateBatch($multipleData = [])
    {
        try {
            if (empty($multipleData)) {
                throw new \Exception("資料不能為空");
            }
            $tableName = DB::getTablePrefix() . $this->getTable(); // 表名
            $firstRow  = current($multipleData);

            $updateColumn = array_keys($firstRow);
            // 預設以id為條件更新，如果沒有ID則以第一個欄位為條件
            $referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
            unset($updateColumn[0]);
            // 拼接sql語句
            $updateSql = "UPDATE " . $tableName . " SET ";
            $sets      = [];
            $bindings  = [];
            foreach ($updateColumn as $uColumn) {
                $setSql = "`" . $uColumn . "` = CASE ";
                foreach ($multipleData as $data) {
                    $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                    $bindings[] = $data[$referenceColumn];
                    $bindings[] = $data[$uColumn];
                }
                $setSql .= "ELSE `" . $uColumn . "` END ";
                $sets[] = $setSql;
            }
            $updateSql .= implode(', ', $sets);
            $whereIn   = collect($multipleData)->pluck($referenceColumn)->values()->all();
            $bindings  = array_merge($bindings, $whereIn);
            $whereIn   = rtrim(str_repeat('?,', count($whereIn)), ',');
            $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
            // 傳入預處理sql語句和對應繫結資料
            return DB::update($updateSql, $bindings);
        } catch (\Exception $e) {
            return false;
        }
    }
}
