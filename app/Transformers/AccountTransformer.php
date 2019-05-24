<?php

namespace App\Transformers;

use App\Models\Account;

class AccountTransformer
{
    /**
     * @param Account $account
     * @return array
     */
    public function transform(Account $account)
    {
        return [
            'account_type' => $account->account_type,
            'account_email' => $account->account_email,
            'access_token_expires' => $account->access_token_expires,
            'extend' => $account->extend,
        ];
    }

}
