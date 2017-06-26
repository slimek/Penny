<?php
namespace Middleware;

// Composer
use bandwidthThrottle\tokenBucket\{Rate, TokenBucket};
use bandwidthThrottle\tokenBucket\storage\PDOStorage;
use Slim\Http\Request;
use Slim\Http\Response;

// 可附加在 Route 上面的 middleware，以 SQLite 資料庫來儲存 token bucket
// 需要和 RKA\Middleware\IpAddress 搭配
class SqliteThrottler
{
    /** @var  int - token bucket 的總容量 */
    private $capacity;

    /** @var  int - 每次消耗的 token 數量 */
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
        $this->capacity = $maxTries * $suspendSeconds;
        $this->cost = $suspendSeconds;
        $this->handler = $handler;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $ipAddress = $request->getAttribute('ip_address');

        $pdo = new \PDO('sqlite:../data/penny.sqlite3');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // 說明：
        // cost = 等待秒數，每次呼叫會消耗掉這麼多 tokens，
        // 然後每秒只會回復 1 個 token，所以要等待 cost 秒之後才能再次呼叫。

        $storage = new PDOStorage($ipAddress, $pdo);
        $rate = new Rate(1, Rate::SECOND);
        $bucket = new TokenBucket($this->capacity, $rate, $storage);
        $bucket->bootstrap($this->capacity);

        if ($bucket->consume($this->cost)) {
            return $next($request, $response);
        } else {
            return ($this->handler)($request, $response);
        }
    }
}
