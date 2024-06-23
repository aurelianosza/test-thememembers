<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Console\Command;

class VerifyBoletosPaymentsCommand extends Command
{
    protected $signature = 'payments:verify-boletos';

    protected $description = 'Command to simulate boletos payments.';

    /**
     * Execute the console command.
     */
    public function handle(PaymentService $paymentService)
    {
        // Esta linha simularia um fetch de um serviço, com hashs de boletos pagos com sucesso
        $approvedBoletos = Payment::query()
            ->where("payment_method", Payment::BOLETO)
            ->inRandomOrder()
            ->limit(rand(1, 5))
            ->get();

        $paymentService
            ->aprovesBoleto($approvedBoletos->pluck("payment_hash")->toArray());

    }
}
