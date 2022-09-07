<?php

//require_once 'connect.php';
include 'Telegram.php';
require_once 'users.php';

$telegram = new Telegram('5305513932:AAH8yqkhu6WzEkeLSWRg8HUcNs4qSuRNUww');
$chat_id = $telegram->ChatID();
$text = $telegram->Text();
$firstname = $telegram->FirstName();

$data = $telegram->getData();
$message = $data['message'];


//$admin_chat_id = 967469906;

$page = getPage($chat_id);

if ($text == "/start") {
    chooseLanguage();
} else {
    switch ($page) {
        case "language" :
            switch ($text) {
                case "Русский 🇷🇺":
                    setLanguage($chat_id, 'ru');
                    showMain();
                    break;
                case "O'zbek tili 🇺🇿":
                    setLanguage($chat_id, 'uz');
                    showMain();
                    break;
            }
            break;
        case "main" :
            switch ($text) {
                case "🔖" . GetText("choose_training_center", getLanguage($chat_id)):
                    showDistricts();
                    break;
                case "💎" . GetText("training_center_list", getLanguage($chat_id)):
                    //ToDo
                    break;
                case "🇺🇿🔄🇷🇺" . GetText("change_lang", getLanguage($chat_id)):
                    changeLanguage();
                    break;
            }
            break;
        case "districts":
            switch ($text) {
                case "🔙" . GetText("back", getLanguage($chat_id)):
                case "🔙" . GetText("main_page", getLanguage($chat_id)):
                    showMain();
                    break;
                default:
                    if (in_array(substr($text, 4), getDistricts($chat_id))){
                        showSubjects();
                    }
                    else{
                        $telegram->sendMessage([
                           'chat_id' => $chat_id,
                           'text' => substr($text, 4),
                        ]);
                    }
                    break;
            }
            break;
    }
}
function chooseLanguage()
{
    global $telegram, $chat_id, $firstname;
    $text = "Пожалуйста выберите язык.\nIltimos, tilni tanlang.";

    createUser($chat_id, $firstname);
    setPage($chat_id, 'language');

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
    global $telegram, $chat_id;
    setPage($chat_id, 'main');
    $text = GetText("choose_category", getLanguage($chat_id)) . "👇";
    $option = array(
        array($telegram->buildKeyboardButton("🔖" . GetText("choose_training_center", getLanguage($chat_id))), $telegram->buildKeyboardButton("💎" . GetText("training_center_list", getLanguage($chat_id)))),
        array($telegram->buildKeyboardButton("🇺🇿🔄🇷🇺" . GetText("change_lang", getLanguage($chat_id)))),
    );
    $keyboard = $telegram->buildKeyBoard($option, false, true);

    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function changeLanguage()
{
    global $chat_id;
    if (getLanguage($chat_id) == 'uz')
        setLanguage($chat_id, 'ru');
    else
        setLanguage($chat_id, 'uz');
    showMain();
}

function showDistricts()
{
    global $telegram, $chat_id;

    setPage($chat_id, "districts");
    $text = GetText("choose_districts", getLanguage($chat_id));
    $districts = getDistricts($chat_id);

    sendTextWithKeyboard($districts, $text, "📍");
}

function showSubjects(){
    global $chat_id;
    $text = GetText("choose_subject", getLanguage($chat_id));
    setPage($chat_id, 'subjects');
    $subjects = getSubjects($chat_id);
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

?>