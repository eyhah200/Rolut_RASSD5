<?php

define('API_KEY','7876903611:AAF2j7qVbehpCUmdtDRMmFAKECo8B-QEETM');
$admin = "831264187";
$botUser = "@Rassd2002001_bot";
$channel = "@Rass20032003";
$whatsapp = "967775920083";
$supportBot = "@Ansta242bot";
$developer = "@Rassd2003";

function bot($method,$datas=[]){
  $url = "https://api.telegram.org/bot".API_KEY."/".$method;
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
  $res = curl_exec($ch);
  curl_close($ch);
  return json_decode($res,true);
}

$update = json_decode(file_get_contents("php://input"));
$message = $update->message;
$text = $message->text;
$chat_id = $message->chat->id;
$from_id = $message->from->id;

mkdir("data");
mkdir("data/$from_id");

function sendAdminFooter(){
  global $whatsapp, $supportBot, $developer;
  return [
    'inline_keyboard' => [
      [['text' => "💬 تواصل واتساب", 'url' => "https://wa.me/$whatsapp"]],
      [['text' => "🛠 الدعم عبر تيلي", 'url' => "https://t.me/$supportBot"]],
      [['text' => "⭐ دعم البوت", 'url' => "https://t.me/$developer"]]
    ]
  ];
}

function checkSub($user_id){
  global $channel;
  $res = bot("getChatMember", ["chat_id" => $channel, "user_id" => $user_id]);
  return (in_array($res['result']['status'], ['member','administrator','creator']));
}

if($text == "/start"){
  if(!checkSub($from_id)){
    bot("sendMessage",[
      "chat_id"=>$chat_id,
      "text"=>"🔒 *يجب الاشتراك في القناة أولاً: $channel*",
      "parse_mode"=>"markdown",
      "reply_markup"=>json_encode([
        "inline_keyboard"=>[
          [["text"=>"✅ تحقق من الاشتراك","callback_data"=>"checkSub"]],
        ]
      ])
    ]);
    return;
  }

  bot("sendMessage",[
    "chat_id"=>$chat_id,
    "text"=>"🎉 مرحباً بك في *بوت روليت راصد*!

يمكنك الآن إنشاء سحب ومشاركته في قناتك.",
    "parse_mode"=>"markdown",
    "reply_markup"=>json_encode([
      "inline_keyboard"=>[
        [["text"=>"🎲 إنشاء روليت","callback_data"=>"create"]],
        [["text"=>"📮 روليت راصد", "url"=>"https://t.me/$botUser"]],
      ]
    ])
  ]);
  bot("sendMessage",[
    "chat_id"=>$chat_id,
    "text"=>"💡 تواصل مع الدعم أو المبرمج.",
    "parse_mode"=>"markdown",
    "reply_markup"=>json_encode(sendAdminFooter())
  ]);
}

?>
