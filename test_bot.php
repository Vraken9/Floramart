<?php
$request = Illuminate\Http\Request::create('/api/chatbot/ask', 'POST', ['message' => 'bunga untuk ibu']);
$response = app()->handle($request);
echo $response->getContent();
