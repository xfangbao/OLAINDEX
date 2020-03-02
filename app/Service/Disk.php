<?php
/**
 * This file is part of the wangningkai/OLAINDEX.
 * (c) wangningkai <i@ningkai.wang>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

class Disk
{
    public static function authorize()
    {
        // todo:兼容多账号
        $account = array_collapse([setting('account_client'), setting('account')]);
        return AuthorizeService::init()->bind($account);
    }

    public static function connect()
    {
        try {
            refresh_token();
        } catch (\ErrorException $e) {
            throw new $e('请求密钥失效');
        }

        // todo:兼容多账号
        $account = array_collapse([setting('account_client'), setting('account')]);
        return OneDrive::init()->bind($account);
    }


}
