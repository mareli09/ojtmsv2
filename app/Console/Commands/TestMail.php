<?php

namespace App\Console\Commands;

use App\Mail\ContactFormMail;
use App\Models\CMS;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'ojtms:test-mail {to? : Address to send to (defaults to the CMS contact email)}';

    protected $description = 'Send a real test message through the configured mailer to verify SMTP settings';

    public function handle(): int
    {
        $to = $this->argument('to') ?: CMS::getValue('contact_email') ?: config('mail.from.address');

        $this->line('Mailer .... ' . config('mail.default'));
        $this->line('Host ...... ' . config('mail.mailers.smtp.host') . ':' . config('mail.mailers.smtp.port'));
        $this->line('Username .. ' . config('mail.mailers.smtp.username'));
        $this->line('From ...... ' . config('mail.from.address'));
        $this->line('To ........ ' . $to);
        $this->newLine();

        if (config('mail.default') === 'log') {
            $this->warn('MAIL_MAILER is still "log" - nothing will actually be delivered.');
        }

        try {
            Mail::to($to)->send(new ContactFormMail(
                'OJTMS Test Sender',
                config('mail.from.address'),
                'SMTP configuration test',
                "This is a test message sent from the OJTMS contact form mailer.\n\nIf you are reading this in your inbox, real email delivery is working."
            ));
        } catch (\Throwable $e) {
            $this->error('FAILED: ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->info('Sent. Check the inbox (and the spam folder) for ' . $to . '.');

        return self::SUCCESS;
    }
}
