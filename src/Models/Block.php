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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * This is the block model class.
 *
 * @author Brian Faust <hello@brianfaust.me>
 */
class Block extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * A block has many transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'blockId', 'id');
    }

    /**
     * A block belongs to a delegate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function delegate(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'generatorPublicKey', 'publicKey');
    }

    /**
     * A block has one previous block.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function previous(): HasOne
    {
        return $this->hasOne(self::class, 'previousBlock', 'id');
    }

    /**
     * [getTimestampAttribute description].
     *
     * @return \Illuminate\Support\Carbon
     */
    public function getTimestampAttribute(): Carbon
    {
        return Carbon::createFromTimestamp($this->attributes['timestamp']);
    }

    /**
     * Get the human readable representation of the total.
     *
     * @return float
     */
    public function getFormattedTotalAttribute(): float
    {
        return $this->totalAmount / 10 ** 8;
    }

    /**
     * Get the human readable representation of the fee.
     *
     * @return float
     */
    public function getFormattedFeeAttribute(): float
    {
        return $this->totalFee / 10 ** 8;
    }

    /**
     * Get the human readable representation of the reward.
     *
     * @return float
     */
    public function getFormattedRewardAttribute(): float
    {
        return $this->reward / 10 ** 8;
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
