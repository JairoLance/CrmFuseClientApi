<?php
// Routes

	
$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->group('/taski', function () use ($app) {
  // Version group
     $this->get('/list', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
                   ->write(
                     json_encode($this->model->users->getAll(0, 0))
                   );
    });
});
