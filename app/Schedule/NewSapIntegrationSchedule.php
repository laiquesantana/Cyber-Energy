<?php

namespace App\Schedule;

use App\Jobs\IncomingPaymentIntegration;
use Saas\Project\Dependencies\Adapters\Logger\MonologLogAdapter;
use Saas\Project\Modules\Invoices\Generics\Entities\ConfigInvoice;

class NewSapIntegrationSchedule
{
    public function scheduler(ConfigInvoice $configInvoice): void
    {
        $logger = new MonologLogAdapter();
        try {
            $logger->info('[App/Schedule::NewSapIntegrationSchedule] Dispatching invoice to incoming payment.');
            IncomingPaymentIntegration::dispatch($configInvoice);
        } catch (\Exception | \Throwable $exception) {
            $logger->error(
                '[App/Schedule::NewSapIntegrationSchedule] Generic error on execute scheduler.',
                [
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'previous' => [
                        'message' => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
                        'exception' => $exception->getPrevious() ? get_class($exception->getPrevious()) : null
                    ],
                    'data' => [
                        'config_invoice_event' => [
                            'doc_entry' => $configInvoice->getDocEntry(),
                            'doc_entry_dpt_invoice' => $configInvoice->getDocEntryDptInvoice(),
                            'status' => $configInvoice->getStatus(),
                        ]
                    ]
                ]
            );
        }
    }
}