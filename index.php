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
    $text = GetText("choose_category", getLanguage($chat_id));
    $option = array(
        array($telegram->buildKeyboardButton(GetText("choose_training_center", getLanguage($chat_id))), $telegram->buildKeyboardButton(GetText("training_center_list", getLanguage($chat_id)))),
        array($telegram->buildKeyboardButton("Tilni almashtirish")),
    );
    $keyboard = $telegram->buildKeyBoard($option, false, true);

    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

?>