<?php

declare(strict_types=1);

/*
 * This file is part of Ark Eloquent.
 *
 * (c) ArkX <hello@arkx.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArkX\Eloquent\Models;

use ArkEcosystem\Crypto\Transactions\Deserializer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * This is the transaction model class.
 *
 * @author Brian Faust <hello@brianfaust.me>
 */
class Transaction extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * A transaction belongs to a block.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class, 'block_id');
    }

    /**
     * A transaction belongs to a sender.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'sender_public_key', 'public_key');
    }

    /**
     * A transaction belongs to a recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'recipient_id', 'address');
    }

    /**
     * Scope a query to only include transactions by the sender.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $publicKey
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSendBy($query, $publicKey)
    {
        return $query->where('sender_public_key', $publicKey);
    }

    /**
     * Scope a query to only include transactions by the recipient.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $address
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReceivedBy($query, $address)
    {
        return $query->where('recipient_id', $address);
    }

    /**
     * Get the human readable representation of the timestamp.
     *
     * @return \Illuminate\Support\Carbon
     */
    public function getTimestampAttribute(): Carbon
    {
        return Carbon::parse('2017-03-21T13:00:00.000Z')
            ->addSeconds($this->attributes['timestamp']);
    }

    /**
     * Get the human readable representation of the vendor field.
     *
     * @return string
     */
    public function getSerializedAttribute(): string
    {
        return bin2hex(stream_get_contents($this->attributes['serialized']));
    }

    /**
     * Get the human readable representation of the vendor field.
     *
     * @return string
     */
    public function getVendorFieldAttribute(): string
    {
        return hex2bin(stream_get_contents($this->vendor_field_hex));
    }

    /**
     * Get the human readable representation of the fee.
     *
     * @return float
     */
    public function getFormattedFeeAttribute(): float
    {
        return $this->fee / 1e8;
    }

    /**
     * Get the human readable representation of the amount.
     *
     * @return float
     */
    public function getFormattedAmountAttribute(): float
    {
        return $this->amount / 1e8;
    }

    /**
     * Find a wallet by its address.
     *
     * @param string $value
     *
     * @return Wallet
     */
    public static function findById(string $value): self
    {
        return static::whereId($value)->firstOrFail();
    }

    /**
     * Perform AIP11 compliant deserialisation.
     *
     * @return object
     */
    public function deserialise(): object
    {
        return Deserializer::new($this->serialized)->deserialize();
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return config('ark-eloquent.connection');
    }
}
