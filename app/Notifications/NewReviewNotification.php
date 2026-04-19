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
        return [
            'type'    => 'review',
            'title'   => 'Ulasan baru untuk ' . $this->product->name,
            'body'    => $this->review->user->name . ' memberikan rating ' . $this->review->rating . ' bintang'
                         . ($this->review->comment ? ': "' . mb_substr($this->review->comment, 0, 80) . '..."' : '.'),
            'url'     => route('products.show', $this->product->slug),
            'rating'  => $this->review->rating,
            'product_id' => $this->product->id,
        ];
    }
}
