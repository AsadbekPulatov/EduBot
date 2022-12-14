<?php
//ishladi
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

$user = new User($chat_id);

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
                case $user->GetText("choose_training_center"):
                    showDistricts();
                    break;
                case $user->GetText("training_center_list"):
                    $user->setPage("center");
                    showAllTrainingCentres();
                    break;
                case $user->GetText("change_lang"):
                    chooseLanguage();
                    break;
            }
            break;
        case "districts":
            switch ($text) {
                case $user->GetText("back"):
                case $user->GetText("main_page"):
                    showMain();
                    break;
                default:
                    if (in_array(substr($text, 4), $user->getDistricts())) {
                        $user->setDistrict(substr($text, 4));
                        showSubjects();
                    } else {
                        sendMessage(substr($text, 4));
                    }
                    break;
            }
            break;
        case "subjects":
            switch ($text) {
                case $user->GetText("back"):
                    showDistricts();
                    break;
                case $user->GetText("main_page"):
                    showMain();
                    break;
                default:
                    if (in_array(substr($text, 3), $user->getSubjects())) {
                        $user->setSubject(substr($text, 3));
                        $user->setPage("center");
                        showTrainingCentres();
                    } else {
                        sendMessage(substr($text, 3));
                    }
                    break;
            }
            break;
        case "center":
            $callback_query = $telegram->Callback_Query();
            if ($callback_query !== null && $callback_query != '') {
                $callback_data = $telegram->Callback_Data();
                $chatID = $telegram->Callback_ChatID();
                $mtext = $user->getInfo($callback_data);
                $content = array('chat_id' => $chatID, 'text' => $mtext);
                $telegram->sendMessage($content);
                $content = ['callback_query_id' => $telegram->Callback_ID(), 'text' => '', 'show_alert' => false];
                $telegram->answerCallbackQuery($content);
            }
            break;
        default:
            sendMessage("{ |" . $page . "| }");
            break;
    }
}
function chooseLanguage()
{
    global $telegram, $chat_id, $user, $firstname;
    $text = "Пожалуйста выберите язык.\nIltimos, tilni tanlang.";

    $user->createUser($chat_id, $firstname);
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
    $user->setPage('main');
    $text = $user->GetText("choose_category");
    $option = array(
        array($telegram->buildKeyboardButton($user->GetText("choose_training_center")), $telegram->buildKeyboardButton($user->GetText("training_center_list"))),
        array($telegram->buildKeyboardButton($user->GetText("change_lang"))),
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
    global $user;
    $user->setPage("districts");
    $text = $user->GetText("choose_districts");
    $districts = $user->getDistricts();
    sendTextWithKeyboard($districts, $text, "📍");
}

function showSubjects()
{
    global $user;
    $text = $user->GetText("choose_subject");
    $user->setPage('subjects');
    $subjects = $user->getSubjects();
    sendTextWithKeyboard($subjects, $text, "◻");
}

function sendTextWithKeyboard($buttons, $text, $icon)
{
    global $telegram, $chat_id, $user;
    $option = [];
    for ($i = 0; $i < count($buttons); $i += 2) {
        if ($i + 2 <= count($buttons)) {
            $option[] = [
                $telegram->buildKeyboardButton($icon . $buttons[$i]),
                $telegram->buildKeyboardButton($icon . $buttons[$i + 1])
            ];
        }
    }
    if (count($buttons) % 2 == 1) {
        $option[] = [$telegram->buildKeyboardButton($icon . $buttons[count($buttons) - 1])];
    }
    $option[] = [
        $telegram->buildKeyboardButton($user->GetText("back")),
        $telegram->buildKeyboardButton($user->GetText("main_page"))
    ];
    $keyboard = $telegram->buildKeyBoard($option);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function sendMessage($text)
{
    global $telegram, $chat_id;
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $text,
    ]);
}

function showTrainingCentres()
{
    global $user, $telegram, $chat_id;

    $text = $user->GetText('choose_tc_text');

    $TrainingCentres = $user->getTrainingCentres();
    if (!$TrainingCentres) {
        $content = [
            'chat_id' => $chat_id,
            'text' => $user->GetText('no_markaz'),
        ];
        $telegram->sendMessage($content);
    } else {
        $option = [];
        foreach ($TrainingCentres as $item) {
            $option[] = [$telegram->buildInlineKeyboardButton("☑" . $item['name'] . "☑", "", $item['id'])];
        }
        $keyboard = $telegram->buildInlineKeyBoard($option);

        $content = [
            'chat_id' => $chat_id,
            'reply_markup' => $keyboard,
            'text' => $text,
        ];
        $telegram->sendMessage($content);
    }
}

function showAllTrainingCentres()
{
    global $user, $telegram, $chat_id;
    $text = $user->GetText("training_center_list");
    $TrainingCentres = $user->getAllTrainingCentres();

    $option = [];
    foreach ($TrainingCentres as $item) {
        $option[] = [$telegram->buildInlineKeyboardButton("☑" . $item['name'] . "☑", "", $item['id'])];
    }
    $keyboard = $telegram->buildInlineKeyBoard($option);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

?>