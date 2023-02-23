<?php

namespace App\Services;

use App\Models\CompanyRankDetail;

class CompanyRankDetailService
{
    public function upsertCompanyRankDetails(int $frequencyId, int $rankId, array $grades)
    {
        foreach ($grades as $grade) {
            CompanyRankDetail::updateOrCreate(
                [
                    'frequency_id' => $frequencyId,
                    'rank_id' => $rankId,
                    'grade_id' => $grade['id'],
                ]
            );
        }
    }
}
