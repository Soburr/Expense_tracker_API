<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
                'type' => 'Transactions',
                    'attributes' => [
                        'type' => $this->type,
                        'amount' => $this->amount,
                        'description' => $this->description,
                        // 'category' => $this->category,
                        'date' => $this->date
                    ]
        ];
    }
}
