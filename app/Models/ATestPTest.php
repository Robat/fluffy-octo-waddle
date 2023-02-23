<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ATestPTest extends Pivot
{

    protected $fillable = ['a_test_id', 'p_test_id', 'a_sort', 'p_sort', 'score', 'frequency_id', 'location'];


    protected static function booted()
    {
        self::created(function (self $atestptest) {
            $atestptest->location = admin()->company->frequency()->id . "_" . $atestptest->a_test_id . "_" . $atestptest->p_test_id;
            $atestptest->save();
        });
    }

    protected static function boot()
    {



        parent::boot();


        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                $builder->where('a_test_p_test.frequency_id', admin()->company->frequency()->id);
            }
        });
    }

    //批次更新
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