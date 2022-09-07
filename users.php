<?php

require_once 'connect.php';

function createUser($chat_id, $name)
{
    global $connect;
    $sql = "DELETE FROM users WHERE chat_id = '$chat_id'";
    $connect->query($sql);
    $sql = "INSERT INTO users(chat_id, name) values('$chat_id','$name')";
    $connect->query($sql);
}

function setPage($chat_id, $page)
{
    global $connect;
    $sql = "UPDATE users SET page = '$page' WHERE chat_id = '$chat_id'";
    $connect->query($sql);
}

function getPage($chat_id)
{
    global $connect;
    $sql = "SELECT * FROM users WHERE chat_id = '$chat_id'";
    $result = $connect->query($sql);

    $row = $result->fetch_assoc();
    return $row['page'];
}

function setLanguage($chat_id, $language)
{
    global $connect;
    $sql = "UPDATE users SET language = '$language' WHERE chat_id = '$chat_id'";
    $connect->query($sql);
}

function getLanguage($chat_id)
{
    global $connect;
    $sql = "SELECT * FROM users WHERE chat_id = '$chat_id'";
    $result = $connect->query($sql);

    $row = $result->fetch_assoc();
    return $row['language'];
}

function GetText($keyword, $language)
{
    global $connect;
    $sql = "SELECT * FROM texts WHERE keyword = '{$keyword}'";
    $result = $connect->query($sql);
    $row = $result->fetch_assoc();
    if (isset($row[$language])) {
        return $row[$language];
    }
}

function getDistricts($chat_id){
    global $connect;
    $lang = getLanguage($chat_id);

    $districtsArray = [];
    $sql = "SELECT * FROM districts";
    $result = $connect->query($sql);

    while ($row = $result->fetch_assoc()){
        if (isset($row[$lang])){
            $districtsArray[] = $row[$lang];
        }
    }
    return $districtsArray;
}

function getSubjects($chat_id){
    global $connect;
    $lang = getLanguage($chat_id);

    $subjectsArray = [];
    $sql = "SELECT * FROM subjects";
    $result = $connect->query($sql);

    while ($row = $result->fetch_assoc()){
        if (isset($row[$lang])){
            $subjectsArray[] = $row[$lang];
        }
    }
    return $subjectsArray;
}