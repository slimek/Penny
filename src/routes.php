<?

$app->get('/phpinfo', function ($request, $response) {
    
    return $response->write(phpinfo());
}
