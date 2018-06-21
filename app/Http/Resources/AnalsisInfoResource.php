<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AnalsisInfoResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_analysis_id' => $this->product_analysis_id,
            'day_flow' => $this->day_flow,
            'day_hit_count' => $this->day_hit_count,
            'conversion_rate' => $this->conversion_rate,
            'm_ranking' => $this->m_ranking,
            'created_at' => $this->created_at
                                    ->toDateTimeString(),
            'updated_at' => $this->updated_at
                                    ->toDateTimeString(),
        ];
    }
}
