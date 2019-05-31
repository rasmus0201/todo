<?php

use \Slim\Http\Request;
use \Slim\Http\Response;
use \Todo\Model\TodoList as TodoList;
use \Todo\Model\TodoItem as TodoItem;

// Homepage
$app->get('/', function (Request $request, Response $response, $args = []) {
    // Get all lists with status = public
    $lists = TodoList::select('id', 'url', 'name', 'status')->where('status', 'public')->get();

    // Render view
    return $this->view->render($response, 'home.html.twig', [
        'lists' => $lists
    ]);
})->setName('home');

// Go to Todo list
$app->get('/{url}', function (Request $request, Response $response, $args = []) {
    // Fetch specified list
    $list = TodoList::where('url', $args['url'])->first();

    if (!$list) {
        // Throw 404 error
        throw new \Slim\Exception\NotFoundException($request, $response);
    }

    // Render view
    return $this->view->render($response, 'list.html.twig', [
        'list' => $list
    ]);
})->setName('list');





/*
 * API ENDPOINTS
 */

// Create new todo list
$app->post('/api/lists', function (Request $request, Response $response, $args = []) {

    // Create new todo list
    $list = new TodoList;
    $list->url = str_random(8); // Random alpanumeric
    $list->name = 'Todo list'; // Default title
    $list->created_from_ip = $request->getServerParam('REMOTE_ADDR', '0.0.0.0'); // Log IP
    $list->save();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'url' => $list->url
    ], 200);
})->setName('api.lists.create');


// Get todo list
$app->get('/api/lists/{listId}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::select('id', 'name', 'url', 'status')->where('id', $args['listId'])->first();

    if (!$list) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'list' => $list
    ], 200);
})->setName('api.lists.get');


// Update todo list
$app->post('/api/lists/{listId}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();

    if (!isset($request->getParams()['name'])) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $name = trim($request->getParams()['name']);
    $status = (!isset($request->getParams()['status']))
        ? $list->status
        : trim($request->getParams()['status']);

    if (!$list || empty($name) || empty($status)) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $list->name = $name;
    $list->status = $status;

    $list->save();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'list' => $list
    ], 200);
})->setName('api.lists.update');


// Delete todo list
$app->delete('/api/lists/{listId}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();

    if (!$list) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $items = $list->items()->get();

    // Go through all the list's items and delete them
    foreach ($items as $item) {
        $item->delete();
    }

    // Delete list
    $list->delete();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!'
    ], 200);
})->setName('api.lists.delete');


// Get todo list's items
$app->get('/api/lists/{listId}/items', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();

    if (!$list) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $items = $list->items()->select('id', 'name', 'status', 'todo_list_id')->get();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'items' => $items
    ], 200);
})->setName('api.lists.items');


// Create todo list item
$app->post('/api/lists/{listId}/items', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();

    $name = $request->getParams()['name'];

    if (!$list || empty(trim($name))) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $item = new TodoItem;
    $item->todo_list_id = $list->id;
    $item->name = $name;
    $item->created_from_ip = $request->getServerParam('REMOTE_ADDR', '0.0.0.0');
    $item->status = 'active'; // Default status

    $item->save();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'item' => $item
    ], 200);
})->setName('api.lists.items.create');


// Update specific todo list's item
$app->post('/api/lists/{listId}/items/{itemId}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();

    if (!$list || !isset($request->getParams()['name']) || !isset($request->getParams()['status'])) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $item = $list->items()->where('id', $args['itemId'])->first();

    $name = trim($request->getParams()['name']);
    $status = trim($request->getParams()['status']);

    if (!$item || empty($name) || empty($status)) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $item->name = $name;
    $item->status = ($status === 'active') ? 'active' : 'completed';

    $item->save();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'item' => $item
    ], 200);
})->setName('api.lists.items.update');


// Delete specific todo list's item
$app->delete('/api/lists/{listId}/items/{itemId}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();

    if (!$list) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $item = $list->items()->where('id', $args['itemId'])->first();

    if (!$item) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $item->delete();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!'
    ], 200);
})->setName('api.lists.items.delete');
