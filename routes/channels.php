<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('participate', function () {
    return true;
});

Broadcast::channel('StartGame', function () {
    return true;
});

Broadcast::channel('SendQuestion', function(){
    return true;
});

Broadcast::channel('SendAnswer', function() {
    return true;
});

Broadcast::channel('AllResultCame', function(){
    return true;
});

Broadcast::channel('timeout', function(){
    return true;
});

Broadcast::channel('scoreBoard', function(){
    return true;
});
