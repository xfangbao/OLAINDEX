<?php

namespace App\Listeners;

use App\Events\SettingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SettingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SettingEvent $event
     * @return void
     */
    public function handle(SettingEvent $event)
    {
        \Cache::forget('settings');

        $setting = $event->setting;
        // 刷新缓存
        \Cache::add('settings', $setting, 7200);

        app('log')->info('refresh-settings', $setting);
    }
}
