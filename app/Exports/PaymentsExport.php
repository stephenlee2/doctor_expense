<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromArray;

class PaymentsExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $payments;

    public function __construct(array $payments)
    {
        $this->payments = $payments;
    }

    public function array(): array
    {
        return $this->payments;
    }
}
