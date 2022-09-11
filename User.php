<?php

require_once 'connect.php';

class User
{
    private $chat_id;
    private $firstname;

    function __construct($chat_id)
    {
        $this->chat_id = $chat_id;
    }

    function createUser($chat_id, $firstname)
    {
        global $connect;
        $this->chat_id = $chat_id;
        $this->firstname = $firstname;
        $sql = "DELETE FROM users WHERE chat_id = '$chat_id'";
        $connect->query($sql);
        $sql = "INSERT INTO users(chat_id, name) values('$chat_id','$firstname')";
        $connect->query($sql);
    }

    function setPage($page)
    {
        global $connect;
        $sql = "UPDATE users SET page = '$page' WHERE chat_id = '$this->chat_id'";
        $connect->query($sql);
    }

    function getPage()
    {
        global $connect;
        $sql = "SELECT * FROM users WHERE chat_id = '$this->chat_id'";
        $result = $connect->query($sql);

        $row = $result->fetch_assoc();
        return $row['page'];
    }

    function setLanguage($language)
    {
        global $connect;
        $sql = "UPDATE users SET language = '$language' WHERE chat_id = '$this->chat_id'";
        $connect->query($sql);
    }

    function getLanguage()
    {
        global $connect;
        $sql = "SELECT * FROM users WHERE chat_id = '$this->chat_id'";
        $result = $connect->query($sql);

        $row = $result->fetch_assoc();
        return $row['language'];
    }

    function GetText($keyword)
    {
        global $connect;
        $language = $this->getLanguage();
        $sql = "SELECT * FROM texts WHERE keyword = '{$keyword}'";
        $result = $connect->query($sql);
        $row = $result->fetch_assoc();
        if (isset($row[$language])) {
            return $row[$language];
        }
    }

    function getDistricts()
    {
        global $connect;
        $lang = $this->getLanguage();
        $districtsArray = [];
        $sql = "SELECT * FROM districts";
        $result = $connect->query($sql);

        while ($row = $result->fetch_assoc()) {
            if (isset($row[$lang])) {
                $districtsArray[] = $row[$lang];
            }
        }
        return $districtsArray;
    }

    function setDistrict($text)
    {
        global $connect;
        $language = $this->getLanguage();
        $id = 0;
        $sql = "SELECT keyword FROM districts WHERE `{$language}` = '{$text}'";
        $result = $connect->query($sql);
        $row = $result->fetch_assoc();
        $id = $row['keyword'];
        $sql = "UPDATE users SET `district` = $id WHERE `chat_id` = $this->chat_id";
        $connect->query($sql);
    }

    function getSubjects()
    {
        global $connect;
        $lang = $this->getLanguage();

        $subjectsArray = [];
        $sql = "SELECT * FROM subjects";
        $result = $connect->query($sql);

        while ($row = $result->fetch_assoc()) {
            if (isset($row[$lang])) {
                $subjectsArray[] = $row[$lang];
            }
        }
        return $subjectsArray;
    }

    function setSubject($text)
    {
        global $connect;
        $language = $this->getLanguage();
        $id = 0;
        $sql = "SELECT keyword FROM subjects WHERE `{$language}` = '{$text}'";
        $result = $connect->query($sql);
        $row = $result->fetch_assoc();
        $id = $row['keyword'];
        $sql = "UPDATE users SET `subject` = $id WHERE `chat_id` = $this->chat_id";
        $connect->query($sql);
    }

    function getTrainingCentres()
    {
        global $connect;

        $sql = "select * from users where chat_id=" . $this->chat_id . " limit 1";
        $result = $connect->query($sql)->fetch_assoc();
        $district = $result['district'];
        $subject = $result['subject'];
        $sql = "select * from trainingcentres WHERE district = {$district} AND subjects LIKE '%{$subject}%'";
        $result = $connect->query($sql);
        $centers = [];
        while ($row = $result->fetch_assoc()) {
            $centers[] = $row;
        }
        return $centers;
    }
}