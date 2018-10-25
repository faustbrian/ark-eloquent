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
        return $this->belongsTo(Block::class, 'blockId');
    }

    /**
     * A transaction belongs to a sender.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'senderPublicKey', 'publicKey');
    }

    /**
     * A transaction belongs to a recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'recipientId', 'address');
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
     * Get the human readable representation of the vendor field.
     *
     * @return string
     */
    public function getVendorFieldAttribute(): string
    {
        return hex2bin($this->vendorFieldHex);
    }

    /**
     * Get the human readable representation of the fee.
     *
     * @return float
     */
    public function getFormattedFeeAttribute(): float
    {
        return $this->fee / 10 ** 8;
    }

    /**
     * Get the human readable representation of the amount.
     *
     * @return float
     */
    public function getFormattedAmountAttribute(): float
    {
        return $this->amount / 10 ** 8;
    }

    /**
     * Determine if the transaction is a transfer.
     *
     * @return bool
     */
    public function getIsTransferAttribute(): bool
    {
        return 0 === (int) $this->type;
    }

    /**
     * Determine if the transaction is a second signature.
     *
     * @return bool
     */
    public function getIsSecondSignatureAttribute(): bool
    {
        return 1 === (int) $this->type;
    }

    /**
     * Determine if the transaction is a delegate registration.
     *
     * @return bool
     */
    public function getIsDelegateRegistrationAttribute(): bool
    {
        return 2 === (int) $this->type;
    }

    /**
     * Determine if the transaction is a vote.
     *
     * @return bool
     */
    public function getIsVoteAttribute(): bool
    {
        return 3 === (int) $this->type;
    }

    /**
     * Determine if the transaction is a multi signature.
     *
     * @return bool
     */
    public function getIsMultiSignatureAttribute(): bool
    {
        return 4 === (int) $this->type;
    }

    /**
     * Determine if the transaction is a ipfs.
     *
     * @return bool
     */
    public function getIsIpfsAttribute(): bool
    {
        return 5 === (int) $this->type;
    }

    /**
     * Determine if the transaction is a timelock transfer.
     *
     * @return bool
     */
    public function getIsTimelockTransferAttribute(): bool
    {
        return 6 === (int) $this->type;
    }

    /**
     * Determine if the transaction is a multi payment.
     *
     * @return bool
     */
    public function getIsMultiPaymentAttribute(): bool
    {
        return 7 === (int) $this->type;
    }

    /**
     * Determine if the transaction is a delegate resignation.
     *
     * @return bool
     */
    public function getIsDelegateResignationAttribute(): bool
    {
        return 8 === (int) $this->type;
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
