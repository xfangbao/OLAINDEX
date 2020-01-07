<?php
/**
 * This file is part of the wangningkai/OLAINDEX.
 * (c) wangningkai <i@ningkai.wang>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;


use App\Models\Account;

class Disk
{
    public static function authorize()
    {
        $account = setting('account');
        return AuthorizeService::init()->bind($account);
    }

    public static function connect()
    {
        $account = setting('account');
        return OneDrive::init()->bind($account);
    }


}
