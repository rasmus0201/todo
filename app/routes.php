<?php

//Namespacing
use \Slim\Http\Request;
use \Slim\Http\Response;

//Models
use \Todo\Model\TodoList as TodoList;
use \Todo\Model\TodoItem as TodoItem;

//Homepage
$app->get('/', function (Request $request, Response $response, $args = []) {
    //Get all lists with status = public
    $lists = TodoList::select('id', 'url', 'name', 'status')->where('status', 'public')->get();

    //Render view
    return $this->view->render($response, 'home.html.twig', [
        'lists' => $lists
    ]);
})->setName('home');

//Go to Todo list
$app->get('/{url}', function (Request $request, Response $response, $args = []) {
    //Fetch specified list
    $list = TodoList::where('url', $args['url'])->first();

    if (!$list) {
        //Throw 404 error
        throw new \Slim\Exception\NotFoundException($request, $response);
    }

    //Render view
    return $this->view->render($response, 'list.html.twig', [
        'list' => $list,
    ]);
})->setName('list');





/*
 * API ENDPOINTS
 */

//Create new todo list
$app->post('/api/lists', function (Request $request, Response $response, $args = []) {

    //Create new todo list
    $list = new TodoList;
    $list->url = str_random(8); //Random alpanumeric
    $list->name = 'Todo list'; //Standard title
    $list->created_from_ip = $request->getServerParam('REMOTE_ADDR', '0.0.0.0'); //Log IP
    $list->save(); //Save list

    //Return a response
    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'url' => $list->url,
    ], 200);
})->setName('api.lists.create');


//Get todo list
$app->get('/api/lists/{listId}', function (Request $request, Response $response, $args = []) {
    //Get list, with specified columns
    $list = TodoList::select('id', 'name', 'url', 'status')->where('id', $args['listId'])->first();

    //Check if list exist
    if (!$list) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    //Return a response
    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'list' => $list,
    ], 200);
})->setName('api.lists.get');


//Update todo list
$app->post('/api/lists/{listId}', function (Request $request, Response $response, $args = []) {

    //Get list
    $list = TodoList::where('id', $args['listId'])->first();

    //Check for errors
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

    //Check for errors
    if (!$list||empty($name)||empty($status)) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $list->name = $name;
    $list->status = $status;

    //Save updates
    $list->save();

    //Response
    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'list' => $list,
    ], 200);
})->setName('api.lists.update');


//Delete todo list
$app->delete('/api/lists/{listId}', function (Request $request, Response $response, $args = []) {

    //Get list
    $list = TodoList::where('id', $args['listId'])->first();

    //Check if list is valid
    if (!$list) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    //Get list's items
    $items = $list->items()->get();

    //Go through all the list's items and delete them
    foreach ($items as $item) {
        $item->delete();
    }

    //Delete list
    $list->delete();

    //Response
    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
    ], 200);
})->setName('api.lists.delete');


//Get todo list's items
$app->get('/api/lists/{listId}/items', function (Request $request, Response $response, $args = []) {

    //Get list
    $list = TodoList::where('id', $args['listId'])->first();

    //Check if list is valid
    if (!$list) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    //Get items with specified columns
    $items = $list->items()->select('id', 'name', 'status', 'todo_list_id')->get();

    //Response
    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'items' => $items,
    ], 200);
})->setName('api.lists.items');


//Create todo list item
$app->post('/api/lists/{listId}/items', function (Request $request, Response $response, $args = []) {

    //Get list
    $list = TodoList::where('id', $args['listId'])->first();

    //Get name
    $name = $request->getParams()['name'];

    //Error checking
    if (!$list||empty(trim($name))) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    //Create new item
    $item = new TodoItem;
    $item->todo_list_id = $list->id;
    $item->name = $name;
    $item->created_from_ip = $request->getServerParam('REMOTE_ADDR', '0.0.0.0');
    $item->status = 'active'; //Default status

    //Save item
    $item->save();

    //Response
    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'item' => $item, //Send the new item back
    ], 200);
})->setName('api.lists.items.create');


//Update specific todo list's item
$app->post('/api/lists/{listId}/items/{itemId}', function (Request $request, Response $response, $args = []) {
    //Check for errors
    if (!isset($request->getParams()['name'])||!isset($request->getParams()['status'])) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    //Get list
    $list = TodoList::where('id', $args['listId'])->first();

    //Get specific item from relation
    $item = $list->items()->where('id', $args['itemId'])->first();

    $name = trim($request->getParams()['name']);
    $status = trim($request->getParams()['status']);

    //Check for errors
    if (!$list||!$item||empty($name)||empty($status)) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    $item->name = $name;
    $item->status = ($status == 'active') ? 'active' : 'completed';

    //Update item
    $item->save();

    //Response
    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
        'item' => $item, //Send the item back
    ], 200);
})->setName('api.lists.items.update');


//Delete specific todo list's item
$app->delete('/api/lists/{listId}/items/{itemId}', function (Request $request, Response $response, $args = []) {
    //Get list
    $list = TodoList::where('id', $args['listId'])->first();

    //Get specific item from relation
    $item = $list->items()->where('id', $args['itemId'])->first();

    //Check list and item exist
    if (!$list||!$item) {
        return $response->withJson([
            'status' => 'error',
            'msg' => 'Something went wrong'
        ], 422);
    }

    //Delete item
    $item->delete();

    //Response
    return $response->withJson([
        'status' => 'success',
        'msg' => 'Success!',
    ], 200);
})->setName('api.lists.items.delete');
