<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\ShortUrlsTracking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class ShortUrlController extends Controller
{
    public function generate(Request $request)
    {
        $shortUrl = ShortUrl::ShortUrlByBaseUrl($request->url)->first();

        if ($shortUrl) {
            return response($shortUrl, 200);
        }

        $shortUrl = new ShortUrl();
        $shortUrl->code = $shortUrl->generateCode();
        $shortUrl->url = $request->url;

        $shortUrl->save();

        return response($shortUrl, 200);

    }

    public function redirect(Request $request, $code)
    {
        $shortUrl = ShortUrl::ShortUrlByCode($code)->first();
        if ($shortUrl === null) {
            abort(404);
        }

        $userAgent = $request->header('User-Agent');
        $userIp = $request->ip();

        $tracking = new ShortUrlsTracking();
        $tracking->code = $code;
        $tracking->url = $shortUrl->url;
        $tracking->hash = md5($userAgent . $userIp);
        $tracking->save();

        return redirect($shortUrl->url);
    }
}
