<?php

//Privatize list
//Delete list


//Namespacing - Get Required classes
use \Slim\Http\Request;
use \Slim\Http\Response;

//Models
use \Todo\Model\TodoList as TodoList;
use \Todo\Model\TodoItem as TodoItem;

//Home page
$app->get('/', function (Request $request, Response $response, $args = []) {
    $lists = TodoList::select('id', 'url', 'name')->where('status', 'public')->get();

    return $this->view->render($response, 'home.html.twig', [
        'lists' => $lists
    ]);
})->setName('home');

//Go to Todo list
$app->get('/{url}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('url', $args['url'])->firstOrFail();

    return $this->view->render($response, 'list.html.twig', [
        'list' => $list,
    ]);
})->setName('list');





/*
 * API ENDPOINTS
 */

//Create new todo list
$app->post('/api/lists', function (Request $request, Response $response, $args = []) {
    $list = new TodoList();
    $list->url = str_random(8);
    $list->name = 'Todo list';
    $list->created_from_ip = $request->getServerParam('REMOTE_ADDR', '0.0.0.0');
    $list->save();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'url' => $list->url,
    ], 200);
})->setName('api.lists.create');


//Get todo list
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
        'list' => $list,
    ], 200);
})->setName('api.lists.get');


//Update todo list
$app->post('/api/lists/{listId}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();

    if (!isset($request->getParams()['name'])) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $name = trim($request->getParams()['name']);
    $status = (!isset($request->getParams()['status'])) ? $list->status : trim($request->getParams()['status']);

    if (!$list||empty($name)||empty($status)) {
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
        'list' => $list,
    ], 200);
})->setName('api.lists.update');


//Delete todo list
$app->delete('/api/lists/{listId}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();

    if (!$list) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $items = TodoItem::where('list_id', $list->id)->get();

    foreach ($items as $item) {
        $item->delete();
    }

    $list->delete();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
    ], 200);
})->setName('api.lists.delete');


//Get todo list's items
$app->get('/api/lists/{listId}/items', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();

    if (!$list) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $items = TodoItem::select('id', 'name', 'status')->where('list_id', $list->id)->get();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'items' => $items,
    ], 200);
})->setName('api.lists.items');


//Create specific todo list's item
$app->post('/api/lists/{listId}/items', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();
    $name = $request->getParams()['name'];

    if (!$list||empty(trim($name))) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $item = new TodoItem();
    $item->list_id = $list->id;
    $item->name = $name;
    $item->created_from_ip = $request->getServerParam('REMOTE_ADDR', '0.0.0.0');

    $item->save();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'item' => TodoItem::select('id', 'name', 'status', 'list_id')->where('id', $item->id)->first(),
    ], 200);
})->setName('api.lists.items.create');


//Update specific todo list's item
$app->post('/api/lists/{listId}/items/{itemId}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();
    $item = TodoItem::where('id', $args['itemId'])->first();

    if (!isset($request->getParams()['name'])||!isset($request->getParams()['status'])) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $name = trim($request->getParams()['name']);
    $status = trim($request->getParams()['status']);

    if (!$list||!$item||empty($name)||empty($status)) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $item->name = $name;
    $item->status = ($status == 'active') ? 'active' : 'completed';

    $item->save();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'item' => $item,
    ], 200);
})->setName('api.lists.items.update');


//Delete specific todo list's item
$app->delete('/api/lists/{listId}/items/{itemId}', function (Request $request, Response $response, $args = []) {
    $list = TodoList::where('id', $args['listId'])->first();
    $item = TodoItem::where('id', $args['itemId'])->first();

    if (!$list||!$item) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $item->delete();

    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
    ], 200);
})->setName('api.lists.items.delete');
