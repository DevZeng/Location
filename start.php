<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;

// Create a Websocket server
//$global_uid = 0;
//$global_manager = array();
//$global_users = array();
//global $db;
//$db = new Workerman\MySQL\Connection('127.0.0.1', '3306', 'root', '', 'zhongrong');

// 当客户端连上来时分配uid，并保存连接，并通知所有客户端
function handle_connection($connection)
{
    global $connection_id;
    // 为这个连接分配一个uid
    $connection->connection_id = ++$connection_id;
    $connection->send('Hello'.$connection->connection_id);
    echo $connection_id.'Connect';
}

// 当客户端发送消息过来时，转发给所有人
function handle_message($connection, $data)
{
//    $data = stripcslashes($data);
    var_dump($data);
    $data = json_decode($data);
//    echo $data;
//    global $text_worker;
//    global $global_manager;
//    global $global_users;
//    global $db;
//    switch (intval($data->type)){
//        case 1://客服登录
//            $has = false;
//            for ($i=0;$i<count($global_manager);$i++){
//                if ($global_manager[$i]['uid']==$connection->uid){
//                    $has = true;
//                }
//            }
////            var_dump($data);
//            if (!$has){
//                $swap = array();
//                $swap['uid'] = $connection->uid;
//                $swap['name'] = str_replace('"','', $data->name);
//                array_push($global_manager,$swap);
//            }
//            $idArr = array_column($global_manager,'uid');
//            if (!in_array($connection->uid,$idArr)){
//                array_push($global_manager,$swap);
//            }
////                var_dump($global_manager);
//            $message = array();
//            $message['type'] = 1;
//            $message['message'] = '用户列表';
//            $message['data'] = $global_users;
//            $connection->send(json_encode($message));
////                foreach($text_worker->connections as $conn)
////                {
////
//////            $conn->send("user[{$connection->uid}] said: $data");
////                    $conn->send(json_encode($global_manager));
////                }
//            break;
//        case 2://用户登录1
//            $has = false;
//            for ($i=0;$i<count($global_users);$i++){
//                if ($global_users[$i]['uid']==$connection->uid){
//                    $has = true;
//                }
//            }
//            if (!$has){
//                $swap = array();
//                $swap['uid'] = $connection->uid;
//                $swap['name'] = $data->name;
//                $swap['open_id'] = $data->open_id;
//                array_push($global_users,$swap);
//            }
//            $idArr = array_column($global_users,'uid');
//            if (!in_array($connection->uid,$idArr)){
//                array_push($global_users,$swap);
//            }
//            $message = array();
//            $message['type'] = 2;//返回用户登入信息
//            $message['message'] = '连接成功，你前面有'.count($global_users).'个用户在排队，请耐心等待！';
//            $message['data'] = '';
//            $connection->send(json_encode($message));
//            $outMessage = array();
//            $outMessage['type'] = 1;
//            $outMessage['message'] = '接入用户';
////            var_dump($global_users);
//            $outMessage['data'] = &$global_users;
//            foreach ($global_manager as $manager){
//                for ($i=0;$i<count($text_worker->connections);$i++){
//                    if ($manager['uid']==$text_worker->connections[$i]->uid){
//                        $text_worker->connections[$i]->send(json_encode($outMessage));
//                    }
//                }
//            }
////            foreach($text_worker->connections as $conn)
////            {
////
//////            $conn->send("user[{$connection->uid}] said: $data");
////                $conn->send(json_encode($global_users));
////            }
//        case 3://发送消息
//            foreach ($text_worker->connections as $conn){
//                if ($conn->uid == $data->receive){
//                    $insert_id = $db->insert('messages')->cols(array(
//                        'from'=>getName($connection->uid),
//                        'message'=>$data->message,
//                        'receive'=>getName($data->receive),
//                        'type'=>$data->message_type,
//                        'time'=>time()))->query();
//                    $message = array();
//                    $message['type'] = 3;
//                    $message['message'] = $data->message;
////                    $message['from']
//                    $conn->send(json_encode($message));
//                }
//            }
//            break;
//        case 4://接入用户
//            $connent_user = getUser($data->receive);
//            foreach ($global_users as $user){
//                if ($user['uid']==$data->receive) {
//                    unset($user);
//                }
//            }
//            $global_users = array_values($global_users);
//            $message = array();
//            $message['type'] = 1;
//            $message['message'] = '接入用户';
//            $message['data'] = $global_users;
//            $other_message = array();
//            $other_message['type'] = 3;
//            $other_message['message'] = '连接成功！';
//            $other_message['data'] = $connent_user;
//
//            foreach ($global_manager as $manager){
//                for ($i=0;$i<count($text_worker->connections);$i++){
//                    if ($manager['uid']==$text_worker->connections[$i]->uid){
//                        $text_worker->connections[$i]->send(json_encode($message));
//                    }
//                }
//            }
//            $receiveMessage = array();
//            $receiveMessage['type'] = 4;
//            $receiveMessage['message'] = getName($connection->uid).'接入对话';
//            $receiveMessage['data'] = getUser($connection->uid);
////            var_dump($receiveMessage);
//            foreach ($text_worker->connections as $conn){
//                if ($conn->uid == $data->receive){
//                    $conn->send(json_encode($receiveMessage));
//                }
//            }
////            foreach ($global_users as $user){
////                if ($user['uid'] == $data->receive){
////
////                }
////            }
//            $connection->send(json_encode($other_message));
//            break;
//        case 5:
//            $message = array();
//            $message['type'] = 5;
//            $message['message'] = '断开连接！';
//            foreach ($text_worker->connections as $conn){
//                if ($conn->uid == $data->receive){
//                    $conn->send(json_encode($message));
//                }
//            }
//            break;
//    }


//    foreach($text_worker->connections as $conn)
//    {
//        $conn->send("user[{$connection->uid}] said: $data");
//    }
}

// 当客户端断开时，广播给所有客户端
function handle_close($connection)
{
//    global $text_worker;
//    global $global_manager;
//    global $global_users;
//    for ($i=0;$i<count($global_manager);$i++){
//        if ($global_manager[$i]['uid']==$connection->uid){
//            unset($global_manager[$i]);
//            $global_manager = array_values($global_manager);
//        }
//    }
//    for ($i=0;$i<count($global_users);$i++){
//        if ($global_users[$i]['uid']==$connection->uid){
//            unset($global_users[$i]);
//            $global_users = array_values($global_users);
//        }
//        $message = array();
//        $message['type'] = 1;
//        $message['message'] = '用户断开连接';
//        $message['data'] = $global_users;
//        $outMessage['type'] = 6;
//        $outMessage['message'] = '用户断开连接！';
//        $outMessage['data'] = getUser($connection->uid);
//        foreach ($global_manager as $manager){
//            for ($i=0;$i<count($text_worker->connections);$i++){
//                if ($manager['uid']==$text_worker->connections[$i]->uid){
//                    $text_worker->connections[$i]->send(json_encode($message));
//                    $text_worker->connections[$i]->send(json_encode($outMessage));
//                }
//            }
//        }
//    }

}
//
function getName($uid)
{
    global $global_manager;
    global $global_users;
    foreach ($global_users as $user){
        if ($user['uid']==$uid){
            return $user['open_id'];
        }
    }
    foreach ($global_manager as $manager){
        if ($manager['uid']==$uid){
            return $manager['name'];
        }
    }
}
function getUser($uid)
{
    global $global_manager;
    global $global_users;
    foreach ($global_users as $user){
        if ($user['uid']==$uid){
            return $user;
        }
    }
    foreach ($global_manager as $manager){
        if ($manager['uid']==$uid){
            return $manager;
        }
    }
}
// 创建一个文本协议的Worker监听2347接口
$text_worker = new Worker("tcp://0.0.0.0:8989");

// 只启动1个进程，这样方便客户端之间传输数据
$text_worker->count = 1;

$text_worker->onConnect = 'handle_connection';
$text_worker->onMessage = 'handle_message';
$text_worker->onClose = 'handle_close';

Worker::runAll();

