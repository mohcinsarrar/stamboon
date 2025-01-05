<?php

namespace App\Listeners;

use Laravel\Cashier\Events\OrderInvoiceAvailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SubscriptionEmail;
use Illuminate\Support\Facades\Mail;

class OrderInvoiceAvailableListener
{
    /**
     * Handle the event.
     *
     * @param  \Laravel\Cashier\Events\OrderInvoiceAvailable  $event
     * @return void
     */
    public function handle(OrderInvoiceAvailable $event)
    {
        // Get the invoice for the order
        $invoice = $event->order->invoice();

        // You can now perform actions like viewing, generating a PDF, or downloading it.

        // To get a Blade view of the invoice
        $view = $invoice->view();

        // To generate a PDF of the Blade view
        $pdf = $invoice->pdf();

        $title = "Your order to plan "."base product"." paid with success";
        $user_fullname = "sarrar mohcin";
        $content = "Thank you for signing up. We're excited to have you on board! <br> you subscription active until ". "2024";
        Mail::to("mohcin.sarrar2@gmail.com")->send(new SubscriptionEmail($title, $user_fullname, $content));

        // To download the invoice as a PDF
        return $invoice;
    }
}
