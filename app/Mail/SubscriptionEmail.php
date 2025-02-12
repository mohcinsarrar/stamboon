<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class SubscriptionEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $website_url, $logo, $title, $user_fullname, $content, $invoice, $company_name, $current_year, $contact_email, $footer_color;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */

     private function load_data(){

        $path = resource_path('views/website/website.json');
    
        // Get the file contents
        $json = File::get($path);
    
        // Decode the JSON data
        $data = json_decode($json, true);
    
        return $data;
      }

    public function __construct($title, $user_fullname, $content, $invoice = null)
    {
        $data = $this->load_data();
        $logo = $data['colors']['logo'];
        if (Storage::exists($logo)) {
            // Get the file content
            $fileContent = Storage::get($logo);
    
            // Convert to base64
            $base64Logo = 'data:image/png;base64,' . base64_encode($fileContent);
        } else {
            // Default base64 placeholder or fallback image
            $base64Logo = 'data:image/png;base64,';
        }
        $footer_color = $data['colors']['primary_color'];


        $this->website_url = "www.thestamboom.com";
        $this->logo = $base64Logo;
        $this->title = $title;
        $this->user_fullname = $user_fullname;
        $this->content = $content;
        $this->invoice = $invoice;
        $this->company_name = "thestamboom";
        $this->current_year = Carbon::now()->year;
        $this->contact_email = "admin@thestamboom.com";
        $this->footer_color = $footer_color;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->invoice != null){
            return $this->view('emails.subscription')
            ->subject('subscription')
            ->attach($this->invoice, [
                'as' => 'invoice_thestamboom.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $this->view('emails.subscription')
        ->subject('subscription');
        
    }
}