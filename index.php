<?php

//require_once 'connect.php';
include 'Telegram.php';
//require_once 'users.php';
require_once 'User.php';

$telegram = new Telegram('5305513932:AAH8yqkhu6WzEkeLSWRg8HUcNs4qSuRNUww');
$chat_id = $telegram->ChatID();
$text = $telegram->Text();
$firstname = $telegram->FirstName();

$data = $telegram->getData();
$message = $data['message'];

$user = new User($chat_id, $firstname);

//$admin_chat_id = 967469906;

$page = $user->getPage();

if ($text == "/start") {
    chooseLanguage();
} else {
    switch ($page) {
        case "language" :
            switch ($text) {
                case "Русский 🇷🇺":
//                    setLanguage($chat_id, 'ru');
                    $user->setLanguage('ru');
                    showMain();
                    break;
                case "O'zbek tili 🇺🇿":
//                    setLanguage($chat_id, 'uz');
                    $user->setLanguage('uz');
                    showMain();
                    break;
            }
            break;
        case "main" :
            switch ($text) {
                case "🔖" . $user->GetText("choose_training_center"):
                    showDistricts();
                    break;
                case "💎" . $user->GetText("training_center_list"):
                    //ToDo
                    break;
                case "🔄" . $user->GetText("change_lang"):
                    chooseLanguage();
                    break;
            }
            break;
        case "districts":
            switch ($text) {
                case "🔙" . $user->GetText("back"):
                case "🔙" . $user->GetText("main_page"):
                    showMain();
                    break;
                default:
                    if (in_array(substr($text, 4), $user->getDistricts())){
                        showSubjects();
                    }
                    else{
                        sendMessage(substr($text, 4));
                    }
                    break;
            }
            break;
        case "subjects":
            switch ($text) {
                case "🔙" . $user->GetText("back"):
                    showDistricts();
                    break;
                case "🔙" . $user->GetText("main_page"):
                    showMain();
                    break;
                default:
                    if (in_array(substr($text, 3), $user->getSubjects())){
                        sendMessage("ishladi");
                    }
                    else{
                        sendMessage(substr($text, 3));
                    }
                    break;
            }
            break;
    }
}
function chooseLanguage()
{
    global $telegram, $chat_id, $user;
    $text = "Пожалуйста выберите язык.\nIltimos, tilni tanlang.";

//    createUser($chat_id, $firstname);
//    setPage($chat_id, 'language');
    $user->setPage('language');

    $option = array(
        array($telegram->buildKeyboardButton("Русский 🇷🇺"), $telegram->buildKeyboardButton("O'zbek tili 🇺🇿"))
    );
    $keyboard = $telegram->buildKeyBoard($option, false, true);

    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function showMain()
{
    global $telegram, $chat_id, $user;
//    setPage($chat_id, 'main');
    $user->setPage('main');
    $text = $user->GetText("choose_category") . "👇";
    $option = array(
        array($telegram->buildKeyboardButton("🔖" . $user->GetText("choose_training_center")), $telegram->buildKeyboardButton("💎" . $user->GetText("training_center_list"))),
        array($telegram->buildKeyboardButton("🔄" . $user->GetText("change_lang"))),
    );
    $keyboard = $telegram->buildKeyBoard($option, false, true);

    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function showDistricts()
{
    global $chat_id, $user;

//    setPage($chat_id, "districts");
    $user->setPage("districts");
    $text = $user->GetText("choose_districts");
    $districts = $user->getDistricts();

    sendTextWithKeyboard($districts, $text, "📍");
}

function showSubjects(){
    global $chat_id, $user;
    $text = $user->GetText("choose_subject");
//    setPage($chat_id, 'subjects');
    $user->setPage('subjects');
//    $subjects = getSubjects($chat_id);
    $subjects = $user->getSubjects();
    sendTextWithKeyboard($subjects, $text, "◻");
}

function sendTextWithKeyboard($buttons, $text, $icon)
{
    global $telegram, $chat_id;
    $option = [];
    for ($i = 0; $i < count($buttons); $i++) {
        $option[] = [$telegram->buildKeyboardButton($icon . $buttons[$i])];
    }
    $option[] = [$telegram->buildKeyboardButton("🔙" . GetText("back", getLanguage($chat_id)))];
    $option[] = [$telegram->buildKeyboardButton("🔙" . GetText("main_page", getLanguage($chat_id)))];
    $keyboard = $telegram->buildKeyBoard($option);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function sendMessage($text){
    global $telegram, $chat_id;
    $telegram->sendMessage([
       'chat_id' => $chat_id,
       'text' => $text,
    ]);
}

?>