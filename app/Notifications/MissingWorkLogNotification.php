<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissingWorkLogNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $date;

    public function __construct(User $user, string $date)
    {
        $this->user = $user;
        $this->date = $date;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingat: Belum mengisi laporan pekerjaan')
            ->greeting("Halo {$this->user->name},")
            ->line("Anda belum mengisi laporan pekerjaan untuk tanggal {$this->date}.")
            ->line('Silakan isi laporan pekerjaan Anda sekarang.')
            ->action('Isi Laporan', url('/work-logs/create'))
            ->line('Terima kasih telah menggunakan sistem kami.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Pengingat: Belum mengisi laporan pekerjaan',
            'message' => "Anda belum mengisi laporan pekerjaan untuk tanggal {$this->date}.",
            'date' => $this->date,
            'action_url' => url('/work-logs/create'),
        ];
    }
}
