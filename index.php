<?php

$telegram_ip_ranges = [
    ['lower' => '149.154.160.0', 'upper' => '149.154.175.255'],
    ['lower' => '91.108.4.0',    'upper' => '91.108.7.255'],
];
$ip_dec = (float) sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
$ok = false;
foreach ($telegram_ip_ranges as $telegram_ip_range) if (!$ok) {
    $lower_dec = (float) sprintf("%u", ip2long($telegram_ip_range['lower']));
    $upper_dec = (float) sprintf("%u", ip2long($telegram_ip_range['upper']));
    if ($ip_dec >= $lower_dec and $ip_dec <= $upper_dec) $ok = true;
}
if (!$ok) die("Are you missing ¿");

error_reporting(0);
include 'jdf.php';
$load = sys_getloadavg();
$token = "7515795720:AAEYy-2x_ESd0p8EySO6b8y-7aKC77JEWaw";//توکن را وارد کنید
define('API_KEY',$token);
date_default_timezone_set('Asia/Tehran');
//-------------------------
function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}
function SendAudio($from_id,$audio,$keyboard,$caption,$sazande,$title){
	bot('SendAudio',[
	'chat_id'=>$from_id,
	'audio'=>$audio,
	'caption'=>$caption,
	'performer'=>$sazande,
	'title'=>$title,
	'reply_markup'=>$keyboard
	]);
	}
function SendDocument($from_id,$document,$keyboard,$caption){
	bot('SendDocument',[
	'chat_id'=>$from_id,
	'document'=>$document,
	'caption'=>$caption,
	'reply_markup'=>$keyboard
	]);
	}
function SendSticker($from_id,$sticker,$keyboard){
	bot('SendSticker',[
	'chat_id'=>$from_id,
	'sticker'=>$sticker,
	'reply_markup'=>$keyboard
	]);
	}
function SendVideo($from_id,$video,$caption,$keyboard,$duration){
	bot('SendVideo',[
	'chat_id'=>$from_id,
	'video'=>$video,
        'caption'=>$caption,
	'duration'=>$duration,
	'reply_markup'=>$keyboard
	]);
	}
function SendVoice($from_id,$voice,$keyboard,$caption){
	bot('SendVoice',[
	'chat_id'=>$from_id,
	'voice'=>$voice,
	'caption'=>$caption,
	'reply_markup'=>$keyboard
	]);
	}

function SendMessage($from_id, $text, $parsmde, $disable_web_page_preview, $keyboard){
	bot('sendMessage', [
	'chat_id' => $from_id,
	'text' => $text,
	'parse_mode' => $parsmde,
	'disable_web_page_preview' => $disable_web_page_preview,
	'reply_markup' => $keyboard
	]);
	}
function Forward($KojaShe,$AzKoja,$KodomMSG){
    bot('ForwardMessage',[
        'chat_id'=>$KojaShe,
        'from_chat_id'=>$AzKoja,
        'message_id'=>$KodomMSG
    ]);
}
function sendphoto($chat_id, $photo, $caption){
	bot('sendphoto',[
	'chat_id'=>$chat_id,
	'photo'=>$photo,
	'caption'=>$caption
	]);
	}
function save($filename,$TXTdata){
	$myfile = fopen($filename, "w") or die("Unable to open file!");
	fwrite($myfile, "$TXTdata");
	fclose($myfile);
	}
	function objectToArrays($object)
    {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        return array_map("objectToArrays", $object);
    }
	function EditMessageText($chat_id,$message_id,$text,$parse_mode,$disable_web_page_preview,$keyboard){
	 bot('editMessagetext',[
    'chat_id'=>$chat_id,
	'message_id'=>$message_id,
    'text'=>$text,
    'parse_mode'=>$parse_mode,
	'disable_web_page_preview'=>$disable_web_page_preview,
    'reply_markup'=>$keyboard
	]);
	}
function rank($id){
    $array = []; $result = []; $ok = null; $i = 1;
    $scan = scandir('referral');
    $scan = array_diff($scan, ['.','..']);
    foreach($scan as $uid){
        $referral = file_get_contents("referral/$uid");
        $array[$uid] = $referral;
    }
    foreach($array as $key => $value){
        $max = max($array);
        $userid = array_search($max, $array);
        $result[$i] = $userid;
        $i += 1;
        unset($array[$userid]);
    }
    foreach($result as $rank => $user){
         if($user == $id){
              return $rank;
         }
    }
}
function BestFind($count=5){
     $res = []; $res2 = [];
     $glob = array_diff(scandir('referral'), ['.','..']);
     foreach($glob as $file){
          $ref = file_get_contents("referral/".$file);
          $res[$file] = $ref;
     }
     for($i = 1; $i <= 5; $i++){
          $max = max($res);
          $sr = array_search($max, $res);
          $res2[] = ['rank'=>$i, 'id'=>$sr, 'referral'=>$max];
          unset($res[$sr]);
     }
     return $res2;
}
function GetStartDay($year,$month,$day){
return mktime(0,0,0,$month,$day+1,$year);
}
function GetViews($channel, $post){
      $embed = file_get_contents("https://t.me/$channel/$post?embed=true");
      preg_match_all('/<span class="tgme_widget_message_views">(.*?)<\/span>/si',$embed,$match);
      $views = $match[1][0];
      return $views;
}
function DeleteFolder($path){
	if($handle=opendir($path)){
		while (false!==($file=readdir($handle))){
			if($file<>"." AND $file<>".."){
				if(is_file($path.'/'.$file)){ 
					@unlink($path.'/'.$file);
				} 
				if(is_dir($path.'/'.$file)) { 
					deletefolder($path.'/'.$file); 
					@rmdir($path.'/'.$file); 
				}
			}
        }
    }
}

function checkMembership($user_id) {
    global $channel1, $channel2;

    $channels = [$channel1, $channel2];

    foreach ($channels as $channel_id) {
        $status = json_decode(file_get_contents("https://api.telegram.org/bot" . API_KEY . "/getChatMember?chat_id=$channel_id&user_id=$user_id"));
        
        if ($status->result->status == "left" || $status->result->status == "kicked") {
            return false; // کاربر عضو نیست
        }
    }

    return true; // کاربر در هر دو کانال عضو است
}

// تابع برای بررسی وضعیت عضویت کاربر در کانال‌ها
function checkAndHandleMembership($user_id, $chat_id) {
    global $channel1, $channel2, $channell, $channelll;  // استفاده از متغیرهای گلوبال

    // ایجاد آرایه‌ای از شناسه کانال‌ها و نام کانال‌ها
    $channels = [
        $channel1 => $channell,   // ارتباط شناسه عددی کانال اول با نام کاربری کانال اول
        $channel2 => $channelll   // ارتباط شناسه عددی کانال دوم با نام کاربری کانال دوم
    ];

    $left_channel = null;
    $is_member = true; // فرض بر این است که کاربر عضو است

    // بررسی عضویت کاربر در هر کانال
    foreach ($channels as $channel_id => $channel_name) {
        // استفاده از متد getChatMember برای بررسی وضعیت عضویت کاربر در کانال
        $status = json_decode(file_get_contents("https://api.telegram.org/bot" . API_KEY . "/getChatMember?chat_id=$channel_id&user_id=$user_id"));

        // اگر کاربر از کانال لفت داده باشد یا اخراج شده باشد
        if ($status->result->status == "left" || $status->result->status == "kicked") {
            $left_channel = $channel_name; // ذخیره نام کانال که کاربر از آن لفت داده
            $is_member = false; // تغییر وضعیت به عدم عضویت
            break; // خارج شدن از حلقه بعد از یافتن کانالی که کاربر از آن لفت داده است
        }
    }

    // اگر کاربر از یک کانال لفت داده باشد یا عضو نیست
    if (!$is_member) {
        $message = $left_channel ? "⚠️ شما از کانال @$left_channel لفت داده‌اید. 👻\n\n⚠️ لطفاً عضو شوید تا بتوانید فعالیت کنید. 🤠" :
                                  "⚠️ شما هنوز در هیچ کدام از کانال‌ها عضو نشده‌اید. 🛑\n\nلطفاً عضو شوید تا بتوانید از خدمات استفاده کنید. 🤠";

        // ارسال پیام به کاربر که از کدام کانال لفت داده یا هنوز عضو نشده است
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => "تایید عضویت"]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ])
        ]);

        return false; // به این معنی است که کاربر هنوز عضو نیست و نمی‌تواند ادامه دهد
    }

    return true; // اگر کاربر در هر دو کانال عضو است
}

// بررسی وضعیت عضویت کاربران و ارسال پیام خروج
function checkLeftUsers() {
    global $channel1, $channel2, $channell, $channelll;

    // دریافت لیست کاربرانی که در سیستم ثبت شده‌اند
    $user_data = json_decode(file_get_contents("user_data.json"), true);
    
    foreach ($user_data as $user_id => $user_info) {
        $left_channel = null;
        
        // بررسی عضویت کاربر در هر دو کانال
        $status1 = json_decode(file_get_contents("https://api.telegram.org/bot" . API_KEY . "/getChatMember?chat_id=$channel1&user_id=$user_id"));
        $status2 = json_decode(file_get_contents("https://api.telegram.org/bot" . API_KEY . "/getChatMember?chat_id=$channel2&user_id=$user_id"));

        // اگر کاربر از کانال اول لفت داده
        if ($status1->result->status == "left" || $status1->result->status == "kicked") {
            $left_channel = $channell;
        }
        // اگر کاربر از کانال دوم لفت داده
        elseif ($status2->result->status == "left" || $status2->result->status == "kicked") {
            $left_channel = $channelll;
        }

        // اگر کاربر از یک کانال لفت داده باشد، پیام ارسال کن
        if ($left_channel !== null) {
            bot('sendMessage', [
                'chat_id' => $user_id,
                'text' => "⚠️ شما از کانال @$left_channel لفت داده‌اید.\n\nلطفاً مجدداً عضو شوید تا بتوانید از خدمات ما استفاده کنید.",
                'parse_mode' => 'html'
            ]);

            // حذف کاربر از لیست، برای جلوگیری از ارسال پیام مجدد
            unset($user_data[$user_id]);
        }
    }

    // ذخیره‌سازی لیست به‌روزشده کاربران
    file_put_contents("user_data.json", json_encode($user_data));
}

// اجرای تابع بررسی ترک کاربران
checkLeftUsers();


function sendcontact($chat_id, $phone_number, $first_name){
bot('sendcontact',[
'chat_id'=>$chat_id,
'phone_number'=>$phone_number,
'first_name'=>$first_name
]);
}


#-----------------------------
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$message_id = $update->message->message_id;
$el = file_get_contents('data/elan.txt');
$chat_id = $update->message->chat->id ?? null;
$message_id = $update->callback_query->message->message_id ?? null;
$from_id = $update->message->from->id ?? null;
$text = $update->message->text ?? null;
$first_name = $update->message->from->first_name ?? null;
$data = $update->callback_query->data ?? null;
mkdir("data/$from_id");
$bot = file_get_contents("data/$from_id/com.txt");
$mmad = file_get_contents("data/$from_id/com.txt");
$ADMIN = array("575030674","5873169405","1725612488");//درجای 000 ایدی عددی ادمین ها را بزارید
//$bot_username = "HFTRaNgebot"; // نام کاربری ربات شما بدون @
$user = file_get_contents("Member.txt"); //ایدی کانال همراه با @
$username = $update->message->from->username;
$first = $update->message->from->first_name;
$file_id = $update->message->document->file_id;
$photo = $update->message->photo;
$photo_id = $photo[count($photo)-1]->file_id;
$musicid = $update->message->audio->file_id;
$voice_id = $update->message->voice->file_id;
$sticker_id = $update->message->sticker->file_id;
$video_id = $update->message->video->file_id;
$music_id = $update->message->audio->file_id;
$caption = $update->message->caption;
$reply = $update->message->reply_to_message->forward_from->id;
$rand1 = "1"; //تعداد سکه برای زیر مجموعه         بایدکمتر از تعداد پایینی باشد یا مساوی
$rand2 = "1"; // //تعداد سکه برای زیر مجموعه
$date = jdate("Y F d");
$time = jdate("H:i:s");
$channel1 = "-1001875693639"; // آیدی عددی کانال اول
$channel2 = "-1002412878710"; // آیدی عددی کانال دوم
$channell = "YamYamProxy"; // آیدی عددی کانال اول
$channelll = "HFTRaNge"; // آیدی عددی کانال دوم
$penlist = file_get_contents("data/pen.txt");
$php08 = file_get_contents("data/$from_id/meti.txt");
$list = file_get_contents("Member.txt");
$kodomadmin = file_get_contents("data/$chat_id/kodomadmin.txt");
$tedad = file_get_contents('data/'.$from_id."/golds.txt");
$member_count = count($member_id) -1;
$tm = $textmessage;
$myfile2 = fopen("data/php08.txt", 'w') or die("Unable to open file!");
$message = file_get_contents("data/php08.txt");
$met = file_get_contents ("data/admin/$from_id/php08.txt");
$f = $met;
@$wait = file_get_contents("data/$from_id/wait.txt");
@$coin = file_get_contents("data/$from_id/golds.txt");
@$Auto = file_get_contents("Auto/$chat_id.txt");
@$adad = file_get_contents('data/'.$from_id."/adad.txt");
@$sof = file_get_contents("data/sofs.txt");
@$on = file_get_contents("on.txt");
@$entshoma = file_get_contents("data/$from_id/entshoma.txt");
@$ali = file_get_contents("data/$chat_id/ali.txt");
$frosh= file_get_contents("data/frosh.txt");
$reza = file_get_contents("data/command.txt");
@$feek = file_get_contents("feek.txt");
@$vag = file_get_contents("vagh.txt");
@$command = file_get_contents("data/$chat_id/com.txt");
$messageid = $update->callback_query->message->message_id;
 $ident = file_get_contents("data/$chat_id/almasid.txt");
$adad1 = file_get_contents('data/'.$from_id."/adad1.txt");
@$ent = file_get_contents("data/$from_id/enteghal.txt");
@$entshoma = file_get_contents("data/$from_id/entshoma.txt");
$forward_chat_username = $update->message->forward_from_chat->username;
#+============
$admin = "575030674";//ایدی عددی ادمین
if(!is_dir("data")){mkdir("data");}
$data = json_decode(file_get_contents("data/$from_id.json"),true);
$step = $data['step'];
$id = $data['id'];
$upload_state_file = 'upload_state.json';
$upload_state = file_exists($upload_state_file) ? json_decode(file_get_contents($upload_state_file), true) : [];
$soof = file_get_contents("data/$from_id/sofs.txt");


$button_remove = json_encode(['KeyboardRemove'=>[
],'remove_keyboard'=>true]);
$button_manage = json_encode(['keyboard'=>[
	[['text'=>"💸افزایش سکه کاربر"],['text'=>"کاهش سکه کاربر 🚮"]],
	[['text'=>"➕ افزایش زیرمجموعه کاربر"],['text'=>"➖ کاهش زیرمجموعه کاربر"]],
	[['text'=>"🔖 آمار فعلی ربات"]],
		[['text'=>"📭پیام همگانی"],['text'=>"📮فوروارد همگانی"]],
			[['text'=>"بلاک کاربر❌"],['text'=>"🎁 سکه همگانی"],['text'=>"انبلاک کاربر✅"]],
			[['text'=>"💤خاموش کردن"],['text'=>"❇️روشن کردن"]],
					[['text'=>"🔙"]],
],'resize_keyboard'=>true]);

$button_manage_custom = json_encode(['keyboard' => [
    [['text' => "💸افزایش سکه کاربر"],['text' => "کاهش سکه کاربر 🚮"]],
    [['text' => "➕ افزایش زیرمجموعه کاربر"],['text' => "➖ کاهش زیرمجموعه کاربر"]],
    [['text' => "🔖 آمار فعلی ربات"]],
    [['text' => "❗️ ارسال برترین ها ❗️"],['text' => "🔍 بررسی اطلاعات کاربر"]],
    [['text' => "📭پیام همگانی"],['text' => "📮فوروارد همگانی"]],
    [['text' => "بلاک کاربر❌"],['text' => "🎁 سکه همگانی"],['text' => "انبلاک کاربر✅"]],
    [['text' => "💤خاموش کردن"],['text' => "❇️روشن کردن"]],
    [['text' => "🔙"],['text' => "پاک کردن نفرات چالش❌"]],
], 'resize_keyboard' => true]);

// دکمه‌ها برای حالت آپلود
$button_upload = json_encode([
    'keyboard' => [
        [['text' => "حالت آپلود"], ['text' => "خروج از حالت آپلود"]]
    ],
    'resize_keyboard' => true,
    'one_time_keyboard' => true
]);

$button_official_admin = json_encode(['keyboard'=>[
	[['text'=>"زیرمجموعه گیری👥️"],['text'=>"برترین ها👑"],['text'=>"مشخصات کاربری📓"]],
	[['text'=>"🔖 آمار فعلی ربات"]],
],'resize_keyboard'=>true]);
$button_official_fa = json_encode(['keyboard'=>[
 [['text'=>"زیرمجموعه گیری👥️"]],
	[['text'=>"برترین ها👑"],['text'=>"مشخصات کاربری📓"]],
	[['text'=>"❓راهنما❗️"]],
	[['text'=>"پشتیبانی"]],
],'resize_keyboard'=>true]);
 $back = json_encode(["keyboard"=>[
[['text'=>"🔙"]],
],'resize_keyboard'=>true,
]);
 $vip = json_encode(["keyboard"=>[
[['text'=>"🔙"]],
],'resize_keyboard'=>true,
]);
$khad = json_encode(['keyboard'=>[
 	[['text'=>"پشتیبانی🤖"]],
		[['text'=>"🔖 آمار فعلی ربات"]],
		[['text'=>"🔙"]],
],'resize_keyboard'=>true]);

$firstt = json_encode([
'keyboard'=>[
    		[['text'=>"🔙"]],
   ],
"resize_keyboard"=>true
]);
$button_saz = json_encode(['keyboard'=>[
	[['text'=>"🔙"]],
],'resize_keyboard'=>true]);
#-------------------------
if(in_array($from_id, explode("\n", $penlist))){
     exit();
}
if($on == "off" && !in_array($from_id,$ADMIN)){
bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"❗️ربات برای چند ساعت آینده خاموش شده است...
🌹 لطفا دقایقی دیگر دوباره امتحان کنید",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_fa
	]);
    exit();
}
if($text == '🔙'){
	   save("data/$from_id/com.txt","none");
	   $data['id'] = "none";
    $data['step'] = "none";
    file_put_contents("data/$from_id.json",json_encode($data,true));
	       bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"برگشتیم به منوی اصلی 🦄",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_fa
	]);
    exit();
}
if(isset($message)){
	$txxt = file_get_contents('Member.txt');
    $pmembersid= explode("\n",$txxt);
	if (!in_array($from_id,$pmembersid)){
        $myfile2 = fopen("Member.txt", "a") or die("Unable to open file!");
        fwrite($myfile2, "$from_id\n");
        fclose($myfile2);
}
}

if (preg_match('/^\/([Ss]tart)(.*)/', $text)) {
    preg_match('/^\/([Ss]tart)(.*)/', $text, $match);
    $referrer_id = isset($match[2]) && is_numeric(trim($match[2])) ? trim($match[2]) : 'none';

    // بررسی اینکه کاربر قبلاً عضو شده یا خیر
    $is_registered = file_exists("members.txt") && in_array($from_id, file("members.txt", FILE_IGNORE_NEW_LINES));

    if ($is_registered && $referrer_id !== 'none') {
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "📛 شما قبلاً در این ربات عضو شده‌اید و نمی‌توانید به عنوان زیرمجموعه جدید ثبت شوید.",
        ]);
    } elseif (!$is_registered) {
        // ثبت کاربر جدید در members.txt
        file_put_contents("members.txt", $from_id . "\n", FILE_APPEND);

        if ($referrer_id !== 'none' && $referrer_id != $from_id) {
            file_put_contents("data/$from_id/referrer.txt", $referrer_id);
        }

        if (checkAndHandleMembership($from_id, $chat_id)) {
            // ذخیره اطلاعات کاربر جدید در user_data.json
            $user_data_file = 'user_data.json';

            // بررسی وجود فایل و بارگذاری اطلاعات یا ایجاد آرایه جدید
            if (file_exists($user_data_file)) {
                $user_data = json_decode(file_get_contents($user_data_file), true);
            } else {
                $user_data = [];
            }

            // اضافه کردن اطلاعات کاربر جدید
            $user_data[$from_id] = [
                'joined_at' => date("Y-m-d H:i:s"),
                'chat_id' => $chat_id
            ];

            // ذخیره اطلاعات به‌روز شده در فایل
            file_put_contents($user_data_file, json_encode($user_data));

            bot('sendmessage', [
                'chat_id' => $chat_id,
                'text' => "🤖سلام $first_name 

✅ ما میخواییم مطمئن بشیم که توی چنل اسپانسر هامون عضو هستی یا نه 

🆔 @$channell 
🆔 @$channelll 

👀 برای تایید عضویت در کانال‌ها، روی 'تایید عضویت' بزنید.",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [['text' => "تایید عضویت"]],
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ])
            ]);
        }
    } else {
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "👋 سلام مجدد! $first_name

ما میخواییم مطمئن بشیم که توی چنل هامون عضو هستی یا نه 🕵

@$channell 🟡
@$channelll 🟡

👀 برای تایید عضویت در کانال‌ها، روی 'تایید عضویت' بزنید.",
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => "تایید عضویت"]],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ])
        ]);
    }
}

// بررسی عضویت با کلیک بر روی دکمه "تایید عضویت"
elseif ($text == "تایید عضویت") {
    if (checkAndHandleMembership($from_id, $chat_id)) {
        $stored_referrer = file_get_contents("data/$from_id/referrer.txt");
        if ($stored_referrer && $stored_referrer !== 'none' && $stored_referrer != $from_id) {
            $existing_referrals = json_decode(file_get_contents("data/$stored_referrer/referrals.txt"), true) ?: [];
            if (!in_array($from_id, $existing_referrals)) {
                $existing_referrals[] = $from_id;
                file_put_contents("data/$stored_referrer/referrals.txt", json_encode($existing_referrals));

                $current_gold = intval(file_get_contents("data/$stored_referrer/golds.txt")) ?: 0;
                file_put_contents("data/$stored_referrer/golds.txt", $current_gold + 1);

                $refers = intval(file_get_contents("referral/$stored_referrer") ?: 0) + 1;
                file_put_contents("referral/$stored_referrer", $refers);

                bot('sendmessage', [
                    'chat_id' => $stored_referrer,
                    'text' => "🎉 دوستت با موفقیت عضو شد!\n🆔 ایدی عددی: [$from_id](tg://user?id=$from_id)\n💎 تعداد سکه: " . ($current_gold + 1) . "\n📊 تعداد زیرمجموعه‌ها: $refers",
                    'parse_mode' => 'Markdown'
                ]);
            }
        }

        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "🤖 سلام $first_name

🎈 خوش اومدی به ربات هفت رنگ🎈

🎁 کلی چالش داریم، همراه با جایزه 

💡حالا باید چکار کنی؟
فقط یک کار!! لینک دعوت بگیری و شروع به کار کنی.

😉 یادت نره فقط به پنج نفر اول جوایز تعلق میگیره


💎 @HFTRaNge 
💎 @YamYamProxy 
💎 @ekhrajiha_tel ",
            'reply_markup' => $button_official_fa,
        ]);
    } else {
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "لطفاً ابتدا در هر دو کانال عضو شوید 🔴

@$channell 🔴
@$channelll 🔴

 سپس دکمه 'تایید عضویت' را بزنید 🔴",
            'parse_mode' => 'html'
        ]);
    }
}




elseif($text == "بخش IP 💻"){
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"به بخش خدمات ربات ویوپنل خوش آمدید🍡
از دکمه های زیر استفاده کنید🎭",'parse_mode'=>'html',
        	'reply_markup'=>$vip
	]);
	}
#========================


if(strpos($text,"'") !== false or strpos($text,'"') !== false or strpos($text,",") !== false or strpos($text,"}") !== false or strpos($text,";") !== false or strpos($text,"{") !== false or strpos($text,"؛") !== false or strpos($text,")") !== false or strpos($text,"(") !== false or strpos($text,"=") !== false or strpos($text,">") !== false or strpos($text,"#") !== false or strpos($text,"[") !== false or strpos($text,"[") !== false or strpos($text,"$") !== false){
file_put_contents("data/$from_id/state.txt","none");
file_put_contents("data/$from_id/step.txt","none");
  bot('sendMessage',[
 'chat_id'=>575030674,
 'text'=>"
مدیریت گرامی 🌹
سیستم ضد هک هوشمند یک فرد که ظاهراً قصد هاک ربات داشته رو دستگیر کرده 🌹
👇🏻 اطلاعات فرد 👇🏻
👤 نام : $first_name
🗣 نمایش پروفایل
🆔 ایدی فرد : @$username
🆔 آیدی عددی فرد : [$from_id](tg://user?id=$from_id)
🚫 کد استفاده شده : 🚫
[   $text   ]
",
 'parse_mode'=>"MarkDown",
  ]);
  exit ();
 }


if ($text == 'پشتیبانی') {
	if (!checkAndHandleMembership($from_id, $chat_id)) {
    // اگر عضو نیست، کد متوقف می‌شود و پیام مربوطه به کاربر ارسال می‌شود
    return;
}
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "لطفاً پیام خود را وارد کنید و ارسال کنید تا ما به شما پاسخ دهیم.",
        'reply_markup' => json_encode([
            'keyboard' => [[['text' => '🔙']]], // دکمه برگشت
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ])
    ]);
    file_put_contents("data/$from_id/step.txt", "support"); // ذخیره مرحله پشتیبانی
    exit();
}

// بررسی اینکه آیا در مرحله پشتیبانی هستیم یا خیر
$step = file_get_contents("data/$from_id/step.txt");

if ($step == 'support' && $text != '🔙') { // در اینجا کاربر پیام پشتیبانی را وارد می‌کند
    $user_message = $text; // پیام کاربر

    // اطلاعات کاربر برای ارسال به ادمین
    $user_info = "👤 نام: $first_name\n";
    $user_info .= "🗣 نمایش پروفایل: @$username\n";
    $user_info .= "🆔 آیدی عددی فرد: [$from_id](tg://user?id=$from_id)\n\n";
    $user_info .= "پیام: $user_message";

    // ارسال پیام کاربر به ادمین
    bot('sendmessage', [
        'chat_id' => 575030674, // آیدی عددی ادمین
        'text' => $user_info,
        'parse_mode' => 'Markdown'
    ]);

    // پیام تأیید به کاربر
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "پیام شما با موفقیت ارسال شد. ما به زودی به شما پاسخ خواهیم داد.",
        'reply_markup' => json_encode([
            'keyboard' => [[['text' => '🔙']]], // دکمه برگشت
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ])
    ]);

    // ریست کردن مرحله پس از ارسال پیام
    file_put_contents("data/$from_id/step.txt", "none");
    exit();
}

if ($text == '/reply' && $from_id == 575030674) { // فقط برای ادمین
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "لطفاً آیدی عددی کاربر را وارد کنید:",
        'reply_markup' => json_encode([
            'remove_keyboard' => true
        ])
    ]);
    file_put_contents("data/$from_id/step.txt", "reply"); // ذخیره مرحله
    exit();
}

// بررسی مرحله پاسخ به کاربر
$step = file_get_contents("data/$from_id/step.txt");

if ($step == 'reply' && is_numeric($text)) {
    $user_id = $text; // آیدی عددی کاربر

    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "لطفاً پیام خود را وارد کنید:",
        'reply_markup' => json_encode([
            'keyboard' => [[['text' => '🔙']]], // دکمه برگشت
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ])
    ]);
    
    // ذخیره آیدی کاربر
    file_put_contents("data/$from_id/user_id.txt", $user_id);
    exit();
}

// دریافت پیام ادمین برای کاربر
if ($step == 'reply') {
    $user_id = file_get_contents("data/$from_id/user_id.txt");

    if ($text != '🔙') { // اگر دکمه برگشت نیست
        // ارسال پیام به کاربر
        bot('sendmessage', [
            'chat_id' => $user_id,
            'text' => $text,
            'parse_mode' => 'Markdown'
        ]);
        
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "پیام شما به کاربر با موفقیت ارسال شد.",
            'reply_markup' => json_encode([
                'keyboard' => [[['text' => '🔙']]], // دکمه برگشت
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ]);
        
        // ریست کردن مرحله
        file_put_contents("data/$from_id/step.txt", "none");
        unlink("data/$from_id/user_id.txt"); // حذف آیدی کاربر
        exit();
    }
}




    elseif ($text == "برترین ها👑") {
    	if (!checkAndHandleMembership($from_id, $chat_id)) {
    // اگر عضو نیست، کد متوقف می‌شود و پیام مربوطه به کاربر ارسال می‌شود
    return;
}
    $bests = BestFind();
    $rank = rank($from_id);
    $str = "";

    foreach ($bests as $value) {
        $str .= "💥 رتبه {$value['rank']}:\n👤 ایدی عددی: <a href='tg://user?id={$value['id']}'>{$value['id']}</a>\n🔻 تعداد زیرمجموعه: {$value['referral']}\n\n";
    }

    $refers = file_get_contents("referral/$from_id");
    $refers = number_format($refers);
    bot('SendMessage', [
        'chat_id' => $chat_id,
        'text' => "برترین ها👑:\n\n$str\n📊 تعداد زیرمجموعه‌های شما تا الان: $refers 🎉\n🌟 رتبه فعلی شما: #$rank
\n ⛓ پایان مسابقه آخر ماه 
جایزه برندگان :
نفر اول🥇: کانفیگ نامحدود یک ماهه 🔸
نفر دوم🥈: کانفینگ ۳۰ گیگ یک ماهه
نفر سوم🥉: کانفیگ ۲۰  گیگ یک ماهه
نفر چهارم4️⃣: کانفیگ ۱۰ گیگ  دو ماهه
نفر پنجم 5️⃣: کانفیگ ۱۰ گیگ یک ماهه 
",
        'parse_mode' => 'html'
    ]);
}

    
  #============

##===========
	elseif($text == "زیرمجموعه گیری👥️"){
		if (!checkAndHandleMembership($from_id, $chat_id)) {
    // اگر عضو نیست، کد متوقف می‌شود و پیام مربوطه به کاربر ارسال می‌شود
    return;
}

	   $caption = "
🎉 آماده‌ای وارد دنیای جذاب هفت‌رنگ بشی؟ 🎉

🔸 منتظر قرعه‌کشی‌های هیجان‌انگیز ما باش و شانس برد خودت رو امتحان کن!
🔸 هر روز پر از سرگرمی، اطلاعات تازه و کلی محتوای متفاوت!

همین الان عضو شو و تجربه‌ای متفاوت رو آغاز کن!
کلیک کن و به خانواده رنگارنگ ما بپیوند!


🤖: https://telegram.me/HFTRaNgebot?start=$chat_id √";
       bot('sendphoto',[
 'chat_id'=>$chat_id,
 'photo'=>new CURLFile('mem.jpg'),
 'caption'=>$caption
 ]);
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "
            کاربر عزیز، شما این بنر بالایی رو به دوستان، کانال و گروه های خود ارسال کنید و در قرعه کشی ما شرکت کنید 🤩

🔺هر یک کاربری که رو لینک شما بزند و وارد ربات شده و استارت را بزند و در کانال ما عضو شود، شما $rand1 عدد سکه دریافت میکنید. 🙂

🔻 استارت و عضویت در چنل و تایید عضویت برای زیرمجموعه اجباری هست 
وگرنه هیچ امتیازی برای شما ثبت نخواهد شد.
",
'reply_to_message_id'=>$bot
        ]);
		
	}
	
	

elseif($text == "👀سفش ویو👁"){

if($feek == "off" && !in_array($from_id,$ADMIN)){
bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"این قسمت به دستور مدیر برای لحظاتی غیر فعال شده است 💕",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_fa
	]);
    exit();
}
if($tedad > 0){
file_put_contents("data/$from_id/com.txt","set");

	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"🚀دوست عزیز جهت افزایش سین پستی را که میخواهید سین بخورد به همین ربات ارسال کنید😎🤟🏼


توجه این سفارش 1سکه از شما کم میکند📌📍",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
	'resize_keyboard'=>true,
	'keyboard'=>[
	[['text'=>"🔙"]],
	[['text'=>""]]
	]
	])
	]);
		}else{
	if(in_array($chat_id,$ADMIN)){
  bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"‼️تعداد سکه های شما کافی نیست، لطفا به بخش حساب کاربری مراجعه کرده و اقدام به افزایش سکه های خود کنید.",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_admin
	]);
	}else{
  bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"‼️تعداد سکه های شما کافی نیست، لطفا به بخش حساب کاربری مراجعه کرده و اقدام به افزایش سکه های خود کنید.",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_fa
	]);
	}
	}
	}
	
		elseif($bot == "set"){
		
			file_put_contents("data/$from_id/com.txt","none");
			
		  if($update->message->forward_from_chat->type == "channel"){
		$newsof = $sof + 1;
		file_put_contents("data/sofs.txt",$newsof);
			  $newgold = $tedad - 1;
			
	file_put_contents("data/$from_id/golds.txt",$newgold);
sleep(1);
	bot('ForwardMessage', [
'chat_id' => "-1001255192301",#ایدی عددی کانال تبلیغات
'from_chat_id' => $chat_id,
'message_id' => $message_id
]);
bot('ForwardMessage', [
'chat_id' => "-1001255192301",#ایدی عددی کانال تبلیغات 2 
'from_chat_id' => $chat_id,
'message_id' => $message_id
]);
if(in_array($chat_id,$ADMIN)){
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"✅ به پستی که فرستادی با موفقیت 100ویو اضافه شد!
⏱🎈ساعت :$time  💠تاریخ : $date
 💡توجه: برای هر پست یک بار میشه ویو زد پس اگه دوباره بفرستی فرقی به تعداد ویو هاش نمیکنه😅😘",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_admin
	]);
}else{
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"✅ به پستی که فرستادی با موفقیت 100ویو اضافه شد!
⏱🎈ساعت :$time  💠تاریخ : $date
 💡توجه: برای هر پست یک بار میشه ویو زد پس اگه دوباره بفرستی فرقی به تعداد ویو هاش نمیکنه😅😘",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_fa
	]);
}
}else{
	if($text != "🔙"){
file_put_contents("data/$from_id/com.txt","none");
if(in_array($chat_id,$ADMIN)){
        bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"🙏توجه ؛ لطفا پست خود را از یک کانال
 فروارد کنید
✅پس دوباره تلاش کنید",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_admin
	]);
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"🔻 به منوی اصلی بازگشتیم",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_admin
	]);
}else{
        bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"🙏توجه ؛ لطفا پست خود را از یک کانال
 فروارد کنید
✅پس دوباره تلاش کنید",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_fa
	]);
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"🔻 به منوی اصلی بازگشتیم",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_official_fa
	]);
}
	}
  }
  }




if($text == "❓راهنما❗️"){
	if (!checkAndHandleMembership($from_id, $chat_id)) {
    // اگر عضو نیست، کد متوقف می‌شود و پیام مربوطه به کاربر ارسال می‌شود
    return;
}
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"
♻️ دوستان عزیز، خوش اومدین به قرعه‌کشی هیجان‌انگیز چنل ما!

ما اینجا کنار شما هستیم تا لحظات شاد و پرهیجانی رو تجربه کنیم و شانس برنده شدن جوایز ویژه رو به شما بدیم. با کمک ربات اد شمار، می‌تونید دوستان بیشتری رو به این چالش دعوت کنید و شانس خودتون رو برای برنده شدن بالا ببرید. همراه با هم قدم به قدم راهنمای استفاده از ربات رو مرور می‌کنیم.


🌟 زیرمجموعه‌گیری؛ دعوت از دوستان دکمه‌ی «زیرمجموعه‌گیری» رو بزنید و لینک اختصاصی خودتون رو دریافت کنید. این لینک رو برای دوستان‌تون بفرستید و بهشون فرصت بدید تا به چالش ما بپیوندند. هر کسی که از طریق لینک شما به جمع ما اضافه بشه، یک زیر مجموعه جدید براتون می‌شه و شانس شما برای برنده شدن بیشتر و بیشتر می‌شه. با دعوت هر دوست، یک قدم نزدیک‌تر به جایزه می‌شید!

🌟 برترین‌ها؛ اوج هیجان دکمه‌ی «برترین‌ها» رو فشار بدید تا لیست افرادی که بیشترین دعوت رو انجام دادند ببینید. این قسمت می‌تونه بهتون انگیزه بده که جایگاه‌تون رو بالا ببرید و به برترین‌ها نزدیک‌تر بشید. شما هم می‌تونید یکی از اون‌ها باشید!

🌟 مشخصات کاربری؛ ببینید چقدر نزدیک شدید! از دکمه‌ی «مشخصات کاربری» استفاده کنید تا وضعیت و امتیازهای خودتون رو بررسی کنید. این بخش مثل آینه‌ایه که به شما نشون می‌ده چقدر پیشرفت کردید و چقدر به برنده شدن نزدیک هستید.

🌟 قرعه‌کشی و اعلام نتایج هر بار که امتیاز جمع می‌کنید، به‌صورت خودکار در قرعه‌کشی شرکت داده می‌شید. پس از هر قرعه‌کشی، نتایج رو توی چنل اعلام می‌کنیم. شاید این بار نام شما به‌عنوان برنده‌ی خوش‌شانس ما اعلام بشه!


 ❗️قوانین و مقررات ❗️
- برای شفافیت و عدالت، اگر موارد غیر واقعی دیده بشه، از قرعه‌کشی حذف می‌شید.
- قرعه‌کشی به‌صورت منظم انجام می‌شه و به برندگان اطلاع‌رسانی می‌کنیم.

🌟 جوایز هر ماه عوض میشن و میتونی هر دفعه شانس خودتو تست کنی 🟡

🌟 پس همین حالا شروع کنید و اولین قدم رو به‌سوی جایزه بردارید! شانس همیشه با شماست!

▪️ @HFTRaNge
▫️ @YamYamProxy
▪️ @ekhrajiha_tel
",'parse_mode'=>'html',
        	'reply_markup'=>json_encode([
	'resize_keyboard'=>true,
	'keyboard'=>[
	[['text'=>"🔙"]],
	[['text'=>""]]
	]
	])
	]);
	}

	

if($text == "vh"){
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"این روزا دزدی زیاد شده! 🕵️‍♂️💰  
قبول داریم که اعتماد سخته؛  
ولی ما اومدیم که واقعی باشیم و سعی کنیم  
دید همرو عوض کنیم.  

🎁 ما میخوایم کلی جوایز مختلف بذاریم که کاربردی باشند:  
مثلاً سری اول کانفیگ 🎉  
و کم‌کم جوایز دیگه‌ای مثل شارژ، پرمیوم و پول نقد هم اضافه می‌کنیم! 💸💳

- نکته مهم: شما نباید کار خاصی بکنید!  
  صرفاً لازمه زیرمجموعه‌گیری کنید،  
  و این حتی هزینه‌ای هم براتون نداره.  
  ما از شما پولی برای شرکت در قرعه‌کشی دریافت نمی‌کنیم،  
  که خودش می‌تونه نشونه‌ای از اعتبار باشه! 🟢  

- سعی کن جز پنج نفر اول باشی تا بتونی جایزه بگیری! 🥇  
  آخر ماه هم بیا جایزه بگیر و برو 🥳🎊  

- کم‌کم روش‌های دیگه هم برای شرکت توی چالش قرار می‌دیم،  
  که فقط زیرمجموعه نباشه و بتونید کامل توی قرعه‌کشی‌هامون شرکت کنید 🟡🎉  

• همراهمون باشید تا بهترین‌ها رو براتون رقم بزنیم 🥰🌟  

▪️ @HFTRaNge ▪️  
▫️ @YamYamProxy ▫️  
▪️ @ekhrajiha_tel ▪️ ",'parse_mode'=>'html',
        	'reply_markup'=>json_encode([
	'resize_keyboard'=>true,
	'keyboard'=>[
	[['text'=>"🔙"]],
	[['text'=>""]]
	]
	])
	]);
	}

	if($text == "💵💳خر💳💵"){
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"ببین دوست 
.
",'parse_mode'=>'html',
        	'reply_markup'=>json_encode([
	'resize_keyboard'=>true,
	'keyboard'=>[
	[['text'=>"🔙"]],
	[['text'=>""]]
	]
	])
	]);
	}

	elseif ($text == "مشخصات کاربری📓") {
		if (!checkAndHandleMembership($from_id, $chat_id)) {
    // اگر عضو نیست، کد متوقف می‌شود و پیام مربوطه به کاربر ارسال می‌شود
    return;
}
    $user_data = json_decode(file_get_contents("users/$from_id.ref"), true);
    $refers = file_get_contents("referral/$from_id") ?: 0;
    $rank = rank($from_id);
    $date = date("Y-m-d");
    $time = date("H:i");

    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "📁نام شما: $first\n🆔ایدی عددی شما :$chat_id\n💎تعداد سکه: $tedad\nتاریخ: $date 🔹 ساعت: $time 🔸\n📊تعداد زیر مجموعه‌های شما تا الان: $refers 🎉\n🌟 رتبه فعلی شما در چالش: #$rank",
        'parse_mode' => 'html',
        'reply_markup' => $button_official_fa
    ]);
}
	

	
	
	
	
	
	elseif($text == " اعتبار چالش 🟢"){
	bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"💡
",'parse_mode'=>'html',
        	'reply_markup'=>$button_official_fa
	]);
	}
	
    

elseif($text == "👤مدیریت" && in_array($chat_id,$ADMIN)){

file_put_contents("data/$from_id/com.txt","none");

        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"ادمین عزیز به پنل مدیریتی ربات خوش آمدید😊",
               'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_manage_custom
	]);
	}

		elseif($text == "🎁 سکه همگانی" && in_array($chat_id,$ADMIN)){
file_put_contents("data/$from_id/com.txt","coin to all");
        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"🔢 لطفا تعداد سکه را بصورت عدد وارد کنید :",
				'reply_to_message_id' => $message_id,
               'parse_mode'=>'html',
			       'reply_markup'=>json_encode([
      'keyboard'=>[
	  [['text'=>'👤مدیریت']],
      ],'resize_keyboard'=>true])
	]);
}

elseif($text == "💤خاموش کردن" && in_array($chat_id,$ADMIN)){
if($on != "off"){
file_put_contents("on.txt","off");
        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"🎭 ربات خاموش شد",
				'reply_to_message_id' => $message_id,
               'parse_mode'=>'html',
	]);
}else{
        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"ربات از قبل خاموش بود...",
				'reply_to_message_id' => $message_id,
               'parse_mode'=>'html',
	]);
}
}

elseif($text == "❇️روشن کردن" && in_array($chat_id,$ADMIN)){
if($on != "on"){
file_put_contents("on.txt","on");
        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"🙃 ربات روشن شد",
				'reply_to_message_id' => $message_id,
               'parse_mode'=>'html',
	]);
}else{
        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"ربات از قبل روشن بود...",
				'reply_to_message_id' => $message_id,
               'parse_mode'=>'html',
	]);
}
}

elseif($bot == "coin to all") {
    if (preg_match('/^([0-9])/',$text)) {
        file_put_contents("data/$from_id/wait.txt", $text);
        file_put_contents("data/$from_id/com.txt", "coin to all 2");
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "⁉️ آیا ارسال $text سکه به تمام کاربران ربات را تایید میکنید ؟

بله یا خیر؟",
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => '👤مدیریت']],
                    [['text' => "خیر"], ['text' => "بله"]],
                ], 'resize_keyboard' => true
            ])
        ]);
    } else {
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "⚠️ ورودی نامعتبر است !
👈🏻 لطفا فقط عدد ارسال کنید :",
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => '👤مدیریت']],
                ], 'resize_keyboard' => true
            ])
        ]);
    }
}
elseif ($bot == "coin to all 2") {
    if ($text == "خیر") {
        unlink("data/$from_id/wait.txt");
        file_put_contents("data/$from_id/com.txt", 'none');
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "✅ با موفقیت لغو شد !",
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'MarkDown',
            'reply_markup' => $button_manage
        ]);
    } elseif ($text == "بله") {
        $Member = explode("\n", $list);
        $count = count($Member) - 2;
        $successful_count = 0; // شمارنده برای تعداد کاربران موفق

        file_put_contents("data/$from_id/com.txt", 'none');
        
        for ($z = 0; $z <= $count; $z++) {
            $user = trim($Member[$z]);
            if (!empty($user)) {
                $id = json_decode(file_get_contents("https://api.telegram.org/bot" . API_KEY . "/getChat?chat_id=" . $user));
                $user2 = $id->result->id;
                
                if ($user2 != null) {
                    $coin = file_get_contents("data/$user/golds.txt") ?: 0;
                    file_put_contents("data/$user/golds.txt", $coin + $wait);
                    $successful_count++; // افزایش شمارنده

                    bot('sendmessage', [
                        'chat_id' => $user,
                        'text' => "🎊 تبریک !!
🎁 از طرف ادمین مقدار $wait سکه هدیه به شما تعلق گرفت ...",
                        'parse_mode' => 'html'
                    ]);
                }
            }
        }
        unlink("data/$from_id/wait.txt");

        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "✅ با موفقیت به تمام اعضا مقدار $wait سکه ارسال شد!\n📊 تعداد کاربران دریافت کننده: $successful_count",
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html',
            'reply_markup' => $button_manage
        ]);
    } else {
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "💢 لطفا فقط از کیبورد زیر انتخاب کنید :",
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => '👤مدیریت']],
                    [['text' => "خیر"], ['text' => "بله"]],
                ], 'resize_keyboard' => true
            ])
        ]);
    }
}


		elseif($text == "💸افزایش سکه کاربر" && in_array($chat_id,$ADMIN)){
			file_put_contents("data/$from_id/com.txt","sendauto");
  bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"ایدی عددی کاربر مورد نظر را ارسال کنید :",'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
	'resize_keyboard'=>true,
	'keyboard'=>[
	[['text'=>"🔙"]],
	[['text'=>""]]
	]
	])
	]);
	}

	elseif($bot == "sendauto" && in_array($chat_id,$ADMIN)){
	if(is_numeric($text)){
	file_put_contents('data/'.$from_id."/adad.txt",$text);

bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"تعداد سکه را وارد کنید...",
    'parse_mode'=>'html',
    'reply_markup'=>$button_manage
  ]);
  
  file_put_contents("data/$from_id/com.txt","sendauto2");
	}else{
		if($text != "🔙"){
	bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"لطفا آیدی عددی را صحیح وارد کنید...",
    'parse_mode'=>'html',
    'reply_markup'=>$button_manage
  ]);
  file_put_contents("data/$from_id/com.txt","none");
		}
	}
	}
	
	elseif($bot == "sendauto2" && in_array($chat_id,$ADMIN)){
	if(is_numeric($text)){
	$teee = file_get_contents('data/'.$adad."/golds.txt");
file_put_contents('data/'.$adad."/golds.txt",$teee+$text);

bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"تعداد $text سکه به کاربر مورد نظر ارسال شد ✅",
    'parse_mode'=>'html',
    'reply_markup'=>$button_manage
  ]);
bot('sendmessage',[
    'chat_id'=>$adad,
    'text'=>"تعداد $text سکه به شما تعلق گرفت✅",
    'parse_mode'=>'html'
  ]);
file_put_contents("data/$from_id/com.txt","none");
	}else{
		if($text != "🔙"){
	bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"لطفا یک عدد لاتین وارد کنید...",
    'parse_mode'=>'html',
    'reply_markup'=>$button_manage
  ]);
  file_put_contents("data/$from_id/com.txt","none");
	}
	}
	}

elseif ($text == "🔖 آمار فعلی ربات") {
    // خواندن و شمارش تعداد کل اعضای ربات
    $user = file_get_contents("Member.txt");
    $member_id = explode("\n", $user);
    $member_count = count($member_id) - 1;

    // خواندن و شمارش تعداد کاربران بن‌شده
    $banned_users = file("banlist.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $banned_count = count($banned_users);

    // شمارش کاربران با حداقل یک زیرمجموعه
    $users_with_subs = 0;
    $total_subs = 0;
    foreach (glob("subcount/*.txt") as $file) {
        $sub_count = (int)file_get_contents($file);
        $total_subs += $sub_count;
        if ($sub_count > 0) {
            $users_with_subs++;
        }
    }
    $average_subs = $users_with_subs > 0 ? round($total_subs / $users_with_subs, 2) : 0;

    // خواندن تعداد پیام‌های همگانی ارسال‌شده
    $broadcast_count = file_exists("broadcast_count.txt") ? (int)file_get_contents("broadcast_count.txt") : 0;

    // زمان و تاریخ
    $time = date("H:i:s");
    $date = date("Y-m-d");

    // محاسبه پینگ سرور
    $load = sys_getloadavg();

    // ایجاد پیام ویرایشی
    $message = bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "🔄 Loading...",
        'reply_to_message_id' => $message_id,
        'parse_mode' => 'html',
    ]);

    // انیمیشن شبیه‌سازی
    $animation_texts = ["🔄 ●", "🔄 ●●", "🔄 ●●●"];
    foreach ($animation_texts as $text) {
        sleep(0.5);
        bot('editmessagetext', [
            'chat_id' => $chat_id,
            'message_id' => $message->result->message_id,
            'text' => $text,
        ]);
    }

    // ارسال آمار نهایی
    bot('editmessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message->result->message_id,
        'text' => "📜 آمار ربات

💑 تعداد اعضای ربات: $member_count
🚫 کاربران بن‌شده: $banned_count
👥 کاربران با حداقل یک زیرمجموعه: $users_with_subs
📈 میانگین زیرمجموعه‌ها: $average_subs
📢 پیام‌های همگانی ارسال شده: $broadcast_count
🚀 پینگ سرور: {$load[0]}
⏱ ساعت: $time
📟 تاریخ: $date",
        'parse_mode' => 'html',
    ]);
}


elseif ($text == "📭پیام همگانی" && in_array($chat_id, $ADMIN)) {
    file_put_contents("data/$from_id/com.txt", "send");

    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "پیام مورد نظر را (متن، عکس، ویدیو، ...) ارسال کنید:",
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'keyboard' => [
                [['text' => '👤مدیریت']],
            ], 'resize_keyboard' => true
        ])
    ]);
}

elseif (file_get_contents("data/$from_id/com.txt") == "send" && in_array($chat_id, $ADMIN)) {
    file_put_contents("data/$from_id/com.txt", "none");

    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "✅ پیام همگانی در حال ارسال به همه کاربران است.",
    ]);

    // شمارنده ارسال‌های موفق
    $sent_count = 0;

    // باز کردن فایل لیست کاربران
    $all_member = fopen("Member.txt", 'r');
    while (!feof($all_member)) {
        $user = trim(fgets($all_member));
        if (!empty($user)) {
            // چک کردن نوع پیام و ارسال متناسب با نوع آن
            if (isset($update->message->photo)) {
                $response = bot('sendPhoto', [
                    'chat_id' => $user,
                    'photo' => end($update->message->photo)->file_id,
                    'caption' => $update->message->caption
                ]);
            } elseif (isset($update->message->video)) {
                $response = bot('sendVideo', [
                    'chat_id' => $user,
                    'video' => $update->message->video->file_id,
                    'caption' => $update->message->caption
                ]);
            } elseif (isset($update->message->audio)) {
                $response = bot('sendAudio', [
                    'chat_id' => $user,
                    'audio' => $update->message->audio->file_id,
                    'caption' => $update->message->caption
                ]);
            } elseif (isset($update->message->document)) {
                $response = bot('sendDocument', [
                    'chat_id' => $user,
                    'document' => $update->message->document->file_id,
                    'caption' => $update->message->caption
                ]);
            } elseif (isset($update->message->sticker)) {
                $response = bot('sendSticker', [
                    'chat_id' => $user,
                    'sticker' => $update->message->sticker->file_id
                ]);
            } elseif (isset($update->message->voice)) {
                $response = bot('sendVoice', [
                    'chat_id' => $user,
                    'voice' => $update->message->voice->file_id,
                    'caption' => $update->message->caption
                ]);
            } else {
                // ارسال به‌صورت متن
                $response = bot('sendMessage', [
                    'chat_id' => $user,
                    'text' => $text,
                    'parse_mode' => "html",
                    'disable_web_page_preview' => true
                ]);
            }

            // افزایش شمارنده در صورت موفقیت ارسال
            if ($response && $response->ok) {
                $sent_count++;
            }
        }
    }
    fclose($all_member);

    // به‌روزرسانی و ذخیره شمارنده پیام‌های همگانی
    $broadcast_count = file_get_contents("broadcast_count.txt") ?: 0;
    $broadcast_count = intval($broadcast_count) + 1;
    file_put_contents("broadcast_count.txt", $broadcast_count);

    // اعلام تعداد موفقیت به ادمین
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "✅ پیام همگانی با موفقیت به $sent_count نفر ارسال شد.\n📊 تعداد کل پیام‌های همگانی ارسال شده: $broadcast_count",
    ]);
}



elseif ($text == "بلاک کاربر❌" && $chat_id == $ADMIN[0]) {
    file_put_contents("data/$from_id/meti.txt", "pen");
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "فقط ایدی عددیشو بفرست تا بلاک بشه از ربات 😡",
        'reply_to_message_id' => $message_id,
    ]);
} elseif ($php08 == 'pen') {
    // اضافه کردن کاربر به لیست بلاک
    $myfile2 = fopen("data/pen.txt", 'a') or die("Unable to open file!");
    fwrite($myfile2, "$text\n");
    fclose($myfile2);
    file_put_contents("data/$from_id/meti.txt", "No");
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "با موفقیت بلاکش کردم 😤\nایدیش هم $text",
        'reply_to_message_id' => $message_id,
        'parse_mode' => "MarkDown",
    ]);
}

elseif ($text == "انبلاک کاربر✅" && $chat_id == $ADMIN[0]) {
    file_put_contents("data/$from_id/meti.txt", "unpen");
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "خوب ی بخشیدی حالا . ایدی عددیشو بدع تا انبلاکش کنم 😕",
        'reply_to_message_id' => $message_id,
        'reply_markup' => $back_keyboard
    ]);
} elseif ($php08 == 'unpen') {
    // خواندن لیست بلاک‌شده‌ها
    $penlist = file_get_contents("data/pen.txt");
    
    // بررسی وجود کاربر در لیست
    if (strpos($penlist, $text) !== false) {
        // حذف کاربر از لیست
        $newlist = str_replace("$text\n", "", $penlist);
        file_put_contents("data/pen.txt", $newlist);
        file_put_contents("data/$from_id/meti.txt", "No");
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "حله انبلاک کردمش\nایدیش هم $text",
            'reply_to_message_id' => $message_id,
        ]);
    } else {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "این کاربر در لیست بلاک نیست.",
            'reply_to_message_id' => $message_id,
        ]);
    }
}

    
elseif ($text == "🔍 بررسی اطلاعات کاربر" && in_array($chat_id, $ADMIN)) {
    file_put_contents("data/$from_id/com.txt", "check_user_info");

    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "🅾️ آیدی عددی کاربر را وارد کنید:",
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'keyboard' => [
                [['text' => '👤مدیریت']],
            ], 'resize_keyboard' => true
        ])
    ]);
}

// دریافت آیدی عددی و نمایش اطلاعات کاربر
elseif (file_get_contents("data/$from_id/com.txt") == "check_user_info" && in_array($chat_id, $ADMIN)) {
    $user_id = trim($text); // آیدی عددی کاربر که ادمین ارسال کرده است

    // بررسی اینکه آیدی عددی معتبر باشد
    if (is_numeric($user_id)) {
        $user_info = json_decode(file_get_contents("https://api.telegram.org/bot" . API_KEY . "/getChat?chat_id=" . $user_id));

        if ($user_info->ok) {
            // دریافت اطلاعات کاربر
            $user_name = $user_info->result->first_name;
            $user_username = isset($user_info->result->username) ? "@" . $user_info->result->username : "ندارد";
            $user_id_link = "[" . $user_id . "](tg://user?id=" . $user_id . ")";

            // دریافت تعداد سکه‌ها
            $user_coins = file_exists("data/$user_id/golds.txt") ? file_get_contents("data/$user_id/golds.txt") : 0;

            // دریافت تعداد زیرمجموعه‌هایی که کاربر خودش آورده است
            $referrals_list = file_exists("data/$user_id/referrals.txt") ? file("data/$user_id/referrals.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
            $referrals_count_self = count($referrals_list);

            // دریافت تعداد زیرمجموعه‌های اضافه‌شده از پنل
            $added_referrals = file_exists("referral/$user_id") ? file_get_contents("referral/$user_id") : 0;

            // محاسبه تعداد کل زیرمجموعه‌ها
            $total_referrals = $referrals_count_self + $added_referrals;

            // ارسال اطلاعات به ادمین
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "🔎 اطلاعات کاربر:\n\n" .
                          "👤 نام: $user_name\n" .
                          "💎 آیدی عددی: $user_id_link\n" .
                          "🔗 آیدی حروفی: $user_username\n" .
                          "💰 تعداد سکه‌ها: " . ($user_coins ?: "۰") . "\n" .
                          "👥 زیرمجموعه‌های آورده‌شده توسط کاربر: " . ($referrals_count_self ?: "۰") . "\n" .
                          "👥 زیرمجموعه‌های اضافه‌شده از پنل: " . ($added_referrals ?: "۰") . "\n" .
                          "🔢 تعداد کل زیرمجموعه‌ها: " . ($total_referrals ?: "۰"),
                'parse_mode' => 'markdown',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [['text' => '👤مدیریت']],
                    ], 'resize_keyboard' => true
                ])
            ]);
        } else {
            // در صورت عدم موفقیت در دریافت اطلاعات
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "❌ کاربر با آیدی عددی $user_id یافت نشد.",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [['text' => '👤مدیریت']],
                    ], 'resize_keyboard' => true
                ])
            ]);
        }
    } else {
        // در صورتی که آیدی عددی نادرست باشد
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "❌ لطفاً یک آیدی عددی معتبر وارد کنید.",
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => '👤مدیریت']],
                ], 'resize_keyboard' => true
            ])
        ]);
    }

    // بازنشانی حالت
    file_put_contents("data/$from_id/com.txt", "none");
}




// مرحله اول: دریافت پیام برای فوروارد همگانی
elseif ($text == "📮فوروارد همگانی" && in_array($chat_id, $ADMIN)) {
    file_put_contents("data/$from_id/com.txt", "awaiting_forward"); // ذخیره وضعیت فرمان

    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "پیام خود را که می‌خواهید به‌صورت همگانی فوروارد شود، ارسال کنید:",
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'keyboard' => [
                [['text' => '👤مدیریت']],
            ], 'resize_keyboard' => true
        ])
    ]);
}

// مرحله دوم: دریافت و فوروارد پیام همگانی
elseif (file_get_contents("data/$from_id/com.txt") == "awaiting_forward" && in_array($chat_id, $ADMIN)) {
    // ریست کردن وضعیت فرمان
    file_put_contents("data/$from_id/com.txt", "none");

    // چک کردن اینکه پیام از طرف ادمین یک پیام قابل فوروارد کردن باشد
    if (isset($update->message->message_id)) {
        $message_id_to_forward = $update->message->message_id; // گرفتن message_id پیام ادمین
        $count = 0; // شمارش تعداد فورواردهای موفق

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "✅ پیام شما در حال فوروارد به تمام کاربران است.",
        ]);

        // خواندن لیست اعضا از فایل Member.txt و فوروارد پیام به هر کاربر
        $all_member = fopen("Member.txt", "r");
        while (!feof($all_member)) {
            $user = trim(fgets($all_member)); // حذف فاصله‌ها و کاراکترهای اضافی
            if (!empty($user)) {
                $result = bot('ForwardMessage', [
                    'chat_id' => $user,
                    'from_chat_id' => $chat_id,
                    'message_id' => $message_id_to_forward
                ]);

                // بررسی نتیجه فوروارد برای شمارش موفقیت‌ها
                if ($result->ok) {
                    $count++;
                }
            }
        }
        fclose($all_member);

        // ارسال پیام به ادمین با تعداد فورواردهای موفق
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "✅ پیام شما با موفقیت به $count نفر فوروارد شد.",
        ]);
    } else {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "❌ پیام قابل فورواردی یافت نشد. لطفاً مجدداً تلاش کنید.",
        ]);
    }
}



elseif($text == 'پاک کردن نفرات چالش❌' && in_array($chat_id,$ADMIN)){
Deletefolder("referral");
bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"نفرات برتر و تمام آمار زیرمجموعه برای تمام کاربران ربات پاک شد
چالش بعدی رو شروع کنیم 🦄
",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_manage_custom
]);}
//.............................................................................................................//
elseif($text == "کاهش سکه کاربر 🚮" && in_array($chat_id,$ADMIN)){
			file_put_contents("data/$from_id/com.txt","remove");
  bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"ایدی عددی کاربر مورد نظر را ارسال کنید :",'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
	'resize_keyboard'=>true,
	'keyboard'=>[
	[['text'=>"🔙"]],
	[['text'=>""]]
	]
	])
	]);
	}

	elseif($bot == "remove" && in_array($chat_id,$ADMIN)){
	if(is_numeric($text)){
	file_put_contents('data/'.$from_id."/adad1.txt",$text);

bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"تعداد سکه را وارد کنید...",
    'parse_mode'=>'html',
    'reply_markup'=>$button_manage
  ]);
  
  file_put_contents("data/$from_id/com.txt","remove2");
	}else{
		if($text != "🔙"){
	bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"لطفا آیدی عددی را صحیح وارد کنید...",
    'parse_mode'=>'html',
    'reply_markup'=>$button_manage
  ]);
  file_put_contents("data/$from_id/com.txt","none");
		}
	}
	}
	
	elseif($bot == "remove2" && in_array($chat_id,$ADMIN)){
	if(is_numeric($text)){
	$teee = file_get_contents('data/'.$adad1."/golds.txt");
file_put_contents('data/'.$adad1."/golds.txt",$teee-$text);

bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"از سکه های کاربر به تعداد $text کم شد",
    'parse_mode'=>'html',
    'reply_markup'=>$button_manage
  ]);
bot('sendmessage',[
    'chat_id'=>$adad1,
    'text'=>"به دلیل زیر پا گزاشتن قوانین و گرفتن زیر مجموعه فیک از شما به تعداد 
$text
سکه کم میشود😒
  ",
    'parse_mode'=>'html'
  ]);
file_put_contents("data/$from_id/com.txt","none");
	}else{
		if($text != "🔙"){
	bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"لطفا یک عدد لاتین وارد کنید...",
    'parse_mode'=>'html',
    'reply_markup'=>$button_manage
  ]);
  file_put_contents("data/$from_id/com.txt","none");
	}
	}
	}
//.............................................................................................................//
elseif($text == "❗️ ارسال برترین ها ❗️" && in_array($chat_id,$ADMIN)){
	 $bests = BestFind();
        $rank = rank($from_id);
        $str = "";
        foreach($bests as $value){
             $str .= "💥 رتبه {$value['rank']}:\n👤 ایدی عددی: <a href='tg://user?id={$value['id']}'>{$value['id']}</a>\n🔻 تعداد زیرمجموعه: {$value['referral']}\n\n";
        }
        $gsd = getStartDay(date('Y'),date('m'),date('d'));
        $gsd -= time();
        $h = floor($gsd/3600); $gsd %= 3600;
        $i = floor($gsd/60); $gsd %= 60;
     $endt = "$h:$i:$gsd";
      $refers = file_get_contents("referral/$from_id");
       $refers = number_format($refers);
    include 'getGift.php';
		bot('SendMessage',[
		'chat_id'=>$channel,
		'text'=>"برترین ها👑: \n\n$str\n💣 $endt تا پایان مسابقه ی امروز 💣
⛓ مسابقه ما به پایان رسیده است:
جایزه برندگان :
نفر اول🥇: کانفیگ نامحدود یک ماهه 🔸
نفر دوم🥈: کانفینگ ۳۰ گیگ یک ماهه
نفر سوم🥉: کانفیگ ۲۰  گیگ یک ماهه
نفر چهارم4️⃣: کانفیگ ۱۰ گیگ  دو ماهه
نفر پنجم 5️⃣: کانفیگ ۱۰ گیگ یک ماهه 

نفرات برتر به زودی جایزه خود را دریافت میکنند🟢
برای شرکت در چالش بعدی چنل رو داشته باشید
به زودی چالش های بعدی اجرا خواهند شد🥳

▪️ @HFTRaNge
▫️ @YamYamProxy
▪️ @ekhrajiha_tel

",
         'parse_mode'=>'html'
		]);
		bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"ارسال شد",
        'parse_mode'=>'MarkDown',
        	'reply_markup'=>$button_manage_custom
]);}
#=====================

// مرحله اول: دریافت ID عددی کاربر برای اضافه کردن زیرمجموعه
elseif ($text == "➕ افزایش زیرمجموعه کاربر" && in_array($chat_id, $ADMIN)) {
    file_put_contents("data/$from_id/com.txt", "add_referral_id"); // ذخیره وضعیت
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "🅾️ آیدی عددی کاربر را وارد کنید:",
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'keyboard' => [
                [['text' => '🔙']],
            ], 'resize_keyboard' => true
        ])
    ]);
}

// مرحله دوم: دریافت تعداد زیرمجموعه‌ها برای اضافه کردن
elseif (file_get_contents("data/$from_id/com.txt") == "add_referral_id" && is_numeric($text)) {
    file_put_contents("data/$from_id/target_id.txt", $text); // ذخیره ID کاربر
    file_put_contents("data/$from_id/com.txt", "add_referral_count");
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "🔢 تعداد زیرمجموعه‌هایی که می‌خواهید اضافه کنید را وارد کنید:",
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'keyboard' => [
                [['text' => '🔙']],
            ], 'resize_keyboard' => true
        ])
    ]);
} elseif (file_get_contents("data/$from_id/com.txt") == "add_referral_id" && !is_numeric($text)) {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "❌ لطفاً یک آیدی عددی معتبر وارد کنید.",
        'parse_mode' => 'html',
    ]);
}

// مرحله نهایی: اضافه کردن تعداد زیرمجموعه به کاربر
elseif (file_get_contents("data/$from_id/com.txt") == "add_referral_count" && is_numeric($text)) {
    $target_id = file_get_contents("data/$from_id/target_id.txt"); // گرفتن ID کاربر
    $referral_count = (int)$text;

    // بررسی و گرفتن زیرمجموعه‌های فعلی
    $current_referrals = (int)file_get_contents("referral/$target_id") ?: 0;
    $new_referrals = $current_referrals + $referral_count; // محاسبه تعداد جدید

    // ذخیره تعداد جدید زیرمجموعه‌ها در پوشه referral
    file_put_contents("referral/$target_id", $new_referrals);

    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "✅ تعداد $referral_count زیرمجموعه به کاربر با آیدی عددی $target_id اضافه شد. \n\n📊 تعداد جدید زیرمجموعه‌ها: $new_referrals",
        'parse_mode' => 'html',
    ]);

    // ریست کردن وضعیت
    file_put_contents("data/$from_id/com.txt", "none");
    unlink("data/$from_id/target_id.txt"); // حذف ID ذخیره‌شده برای جلوگیری از تداخل
} elseif (file_get_contents("data/$from_id/com.txt") == "add_referral_count" && !is_numeric($text)) {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "❌ لطفاً یک عدد معتبر برای تعداد زیرمجموعه‌ها وارد کنید.",
        'parse_mode' => 'html',
    ]);
}



// مرحله اول: دریافت ID عددی کاربر برای کاهش زیرمجموعه
elseif ($text == "➖ کاهش زیرمجموعه کاربر" && in_array($chat_id, $ADMIN)) {
    file_put_contents("data/$from_id/com.txt", "reduce_referral_id"); // ذخیره وضعیت
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "🅾️ آیدی عددی کاربر را وارد کنید:",
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'keyboard' => [
                [['text' => '🔙']],
            ], 'resize_keyboard' => true
        ])
    ]);
}

// مرحله دوم: دریافت تعداد زیرمجموعه‌ها برای کاهش
elseif (file_get_contents("data/$from_id/com.txt") == "reduce_referral_id" && is_numeric($text)) {
    file_put_contents("data/$from_id/target_id.txt", $text); // ذخیره ID کاربر
    file_put_contents("data/$from_id/com.txt", "reduce_referral_count");
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "🔢 تعداد زیرمجموعه‌هایی که می‌خواهید کاهش دهید را وارد کنید:",
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'keyboard' => [
                [['text' => '🔙']],
            ], 'resize_keyboard' => true
        ])
    ]);
} elseif (file_get_contents("data/$from_id/com.txt") == "reduce_referral_id" && !is_numeric($text)) {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "❌ لطفاً یک آیدی عددی معتبر وارد کنید.",
        'parse_mode' => 'html',
    ]);
}

// مرحله نهایی: کاهش تعداد زیرمجموعه کاربر
elseif (file_get_contents("data/$from_id/com.txt") == "reduce_referral_count" && is_numeric($text)) {
    $target_id = file_get_contents("data/$from_id/target_id.txt"); // گرفتن ID کاربر
    $reduce_count = (int)$text;

    // بررسی و گرفتن زیرمجموعه‌های فعلی
    $current_referrals = (int)file_get_contents("referral/$target_id") ?: 0;

    // اگر تعداد زیرمجموعه‌ها کافی نیست، به ۰ محدود می‌کنیم
    if ($reduce_count > $current_referrals) {
        $new_referrals = 0;
    } else {
        $new_referrals = $current_referrals - $reduce_count;
    }

    // ذخیره تعداد جدید زیرمجموعه‌ها در پوشه referral
    file_put_contents("referral/$target_id", $new_referrals);

    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "✅ تعداد $reduce_count زیرمجموعه از کاربر با آیدی عددی $target_id کاهش یافت. \n\n📊 تعداد جدید زیرمجموعه‌ها: $new_referrals",
        'parse_mode' => 'html',
    ]);

    // ریست کردن وضعیت
    file_put_contents("data/$from_id/com.txt", "none");
    unlink("data/$from_id/target_id.txt"); // حذف ID ذخیره‌شده برای جلوگیری از تداخل
} elseif (file_get_contents("data/$from_id/com.txt") == "reduce_referral_count" && !is_numeric($text)) {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "❌ لطفاً یک عدد معتبر برای تعداد زیرمجموعه‌ها وارد کنید.",
        'parse_mode' => 'html',
    ]);
}



//..................................................................................//
elseif($text == "خاموش کردن سین فیک" && in_array($chat_id,$ADMIN)){
if($feek != "off"){
file_put_contents("feek.txt","off");
        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"🎭 ربات خاموش شد",
				'reply_to_message_id' => $message_id,
               'parse_mode'=>'html',
	]);
}else{
        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"ربات از قبل خاموش بود...",
				'reply_to_message_id' => $message_id,
               'parse_mode'=>'html',
	]);
}
}

elseif($text == "روشن کردن سین فیک" && in_array($chat_id,$ADMIN)){
if($feek != "on"){
file_put_contents("feek.txt","on");
        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"🙃 ربات روشن شد",
				'reply_to_message_id' => $message_id,
               'parse_mode'=>'html',
	]);
}else{
        bot('sendmessage', [
                'chat_id' =>$chat_id,
                'text' =>"ربات از قبل روشن بود...",
				'reply_to_message_id' => $message_id,
               'parse_mode'=>'html',
	]);
}}
	


set_time_limit(-100000000);
flush();
 
?>
