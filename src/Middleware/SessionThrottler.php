<?php
namespace Middleware;

// Composer
use bandwidthThrottle\tokenBucket\{Rate, TokenBucket};
use bandwidthThrottle\tokenBucket\storage\SessionStorage;
use Slim\Http\Request;
use Slim\Http\Response;

// 可附加在 Route 上面的 middleware，用於限制同一 session 的 API 回應頻率
// 需要在 index.php 之中呼叫 session_start()。
// 問題：PHP session 似乎是基於 cookies 在運作，因此沒使用 cookies 的機器人就無效～
class SessionThrottler
{
    /** @var  TokenBucket */
    private $bucket;

    /** @var  int 每次呼叫時要消耗掉的 tokens 數量 */
    private $cost;

    /** @var  callable */
    private $handler;

    // maxTries
    //   可容許的最大連續嘗試次數
    // suspendSeconds
    //   超過限度之後，每次呼叫需等待的秒數
    // handler
    //   一旦要求被 throttler 擋下來時，要怎麼處理。比方說：
    //   - 回應一個特定格式的錯誤訊息
    //   - 擲出異常
    public function __construct(int $maxTries, int $suspendSeconds, callable $handler)
    {
        // 說明：
        // cost = 等待秒數，每次呼叫會消耗掉這麼多 tokens，
        // 然後每秒只會回復 1 個 token，所以要等待 cost 秒之後才能再次呼叫。

        $capacity = $maxTries * $suspendSeconds;

        $storage = new SessionStorage("${maxTries}_$suspendSeconds");
        $rate = new Rate(1, Rate::SECOND);
        $this->bucket = new TokenBucket($capacity, $rate, $storage);
        $this->bucket->bootstrap($capacity);

        $this->cost = $suspendSeconds;
        $this->handler = $handler;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        if ($this->bucket->consume($this->cost)) {
            return $next($request, $response);
        } else {
            return ($this->handler)($request, $response);
        }
    }
}
