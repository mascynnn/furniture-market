<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Review  $review,
        public Product $product,
    ) {}

    /** Kirim via database channel */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** Data yang disimpan ke kolom `data` di tabel notifications */
    public function toDatabase(object $notifiable): array
    {
        // FIX: hindari trailing "..." pada komentar yang sudah pendek;
        //      gunakan mb_strlen agar aman dengan karakter multibyte (Unicode/Indonesia)
        $commentSnippet = '';
        if (!empty($this->review->comment)) {
            $comment = $this->review->comment;
            $commentSnippet = mb_strlen($comment) > 80
                ? ': "' . mb_substr($comment, 0, 80) . '..."'
                : ': "' . $comment . '"';
        }

        return [
            'type'       => 'review',
            'title'      => 'Ulasan baru untuk ' . $this->product->name,
            'body'       => $this->review->user->name
                            . ' memberikan rating ' . $this->review->rating . ' bintang'
                            . ($commentSnippet ?: '.'),
            'url'        => route('products.show', $this->product->slug),
            'rating'     => $this->review->rating,
            'product_id' => $this->product->id,
        ];
    }
}