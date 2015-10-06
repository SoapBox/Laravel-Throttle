<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 use GrahamCampbell\Throttle\Facades\Throttle;
 use SoapBox\Exceptions\ValidationException;
 use Illuminate\Support\MessageBag;
 use Illuminate\Support\Facades\Lang;
 use Illuminate\Support\Facades\Config;

 Route::filter('throttle', function ($route, $request, $limit = 10, $time = 60, $userId = -1) {
      if ( !Config::get('app.debug') ) {
          $data = [
             'ip' => 'user:' . $userId . ', ip:' . $request->getClientIp(),
             'route' => $request->path(),
          ];

          if (!Throttle::attempt($data, $limit, $time/60)) {
             throw new ValidationException(new MessageBag([Lang::get('app.errors.throttle', ['cooldown' => ceil((float) $time)])]));
          }
      }
 });
