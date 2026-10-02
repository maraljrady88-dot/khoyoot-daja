<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'city',
        'district',
        'address',
        'notes',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'tracking_number',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    const STATUSES = [
        'new' => [
            'label' => 'جديد',
            'bg' => 'bg-amber-50',
            'text' => 'text-amber-800',
            'border' => 'border-amber-200',
        ],
        'processing' => [
            'label' => 'قيد المعالجة',
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-800',
            'border' => 'border-blue-200',
        ],
        'ready' => [
            'label' => 'تم التجهيز',
            'bg' => 'bg-indigo-50',
            'text' => 'text-indigo-800',
            'border' => 'border-indigo-200',
        ],
        'shipped' => [
            'label' => 'تم الشحن',
            'bg' => 'bg-purple-50',
            'text' => 'text-purple-800',
            'border' => 'border-purple-200',
        ],
        'delivered' => [
            'label' => 'تم التسليم',
            'bg' => 'bg-emerald-50',
            'text' => 'text-emerald-800',
            'border' => 'border-emerald-200',
        ],
        'cancelled' => [
            'label' => 'ملغي',
            'bg' => 'bg-red-50',
            'text' => 'text-red-800',
            'border' => 'border-red-200',
        ],
        'refunded' => [
            'label' => 'مسترجع',
            'bg' => 'bg-zinc-100',
            'text' => 'text-zinc-800',
            'border' => 'border-zinc-300',
        ],
    ];

    const PAYMENT_METHODS = [
        'cod' => 'الدفع عند الاستلام',
        'bank_transfer' => 'تحويل بنكي',
        'card' => 'بطاقة مدى / فيزا / ماستركارد',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'DJ-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getStatusInfoAttribute(): array
    {
        return self::STATUSES[$this->status] ?? [
            'label' => $this->status,
            'bg' => 'bg-zinc-100',
            'text' => 'text-zinc-700',
            'border' => 'border-zinc-200',
        ];
    }

    public function getPaymentMethodNameAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }
}
