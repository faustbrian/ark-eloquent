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
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * This is the wallet model class.
 *
 * @author Brian Faust <hello@brianfaust.me>
 */
class Wallet extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * A wallet has many sent transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sentTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'senderPublicKey', 'publicKey');
    }

    /**
     * A wallet has many received transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'recipientId', 'address');
    }

    /**
     * A wallet has many blocks if it is a delegate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'generatorPublicKey', 'publicKey');
    }

    /**
     * [findByAddress description].
     *
     * @param string $value [description]
     *
     * @return [type] [description]
     */
    public static function findByAddress(string $value): self
    {
        return static::whereAddress($value)->firstOrFail();
    }

    /**
     * [findByPublicKey description].
     *
     * @param string $value [description]
     *
     * @return [type] [description]
     */
    public static function findByPublicKey(string $value): self
    {
        return static::wherePublicKey($value)->firstOrFail();
    }

    /**
     * [findByUsername description].
     *
     * @param string $value [description]
     *
     * @return [type] [description]
     */
    public static function findByUsername(string $value): self
    {
        return static::whereUsername($value)->firstOrFail();
    }

    /**
     * [findByVote description].
     *
     * @param string $value [description]
     *
     * @return [type] [description]
     */
    public static function findByVote(string $value): self
    {
        return static::whereVote($value)->firstOrFail();
    }

    /**
     * Get the human readable representation of the balance.
     *
     * @return float
     */
    public function getFormattedBalanceAttribute(): float
    {
        return $this->balance / 10 ** 8;
    }

    /**
     * Get the human readable representation of the vote balance.
     *
     * @return float
     */
    public function getFormattedVoteBalanceAttribute(): float
    {
        return $this->votebalance / 10 ** 8;
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
