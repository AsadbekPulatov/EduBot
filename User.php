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
        $sql = "SELECT id FROM districts WHERE `{$language}` = '{$text}'";
        $result = $connect->query($sql);
        $row = $result->fetch_assoc();
        $id = (int)$row['id'];
        $sql = "UPDATE users SET `district_id` = $id WHERE `chat_id` = $this->chat_id";
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
        $sql = "SELECT id FROM subjects WHERE `{$language}` = '{$text}'";
        $result = $connect->query($sql);
        $row = $result->fetch_assoc();
        $id = (int)$row['id'];
        $sql = "UPDATE users SET `subject_id` = $id WHERE `chat_id` = $this->chat_id";
        $connect->query($sql);
    }

    function getTrainingCentres()
    {
        global $connect;
        $sql = "select * from users where chat_id=" . $this->chat_id . " limit 1";
        $result = $connect->query($sql)->fetch_assoc();
        $district_id = $result['district_id'];
        $subject_id = $result['subject_id'];
        $sql = "select keyword from subjects where id=" . $subject_id . " limit 1";
        $result = $connect->query($sql)->fetch_assoc();
        $subject = $result['keyword'];
        $sql = "select keyword from districts where id=" . $district_id . " limit 1";
        $result = $connect->query($sql)->fetch_assoc();
        $district = $result['keyword'];
        $sql = "select * from trainingcentres WHERE district = '{$district}' AND subjects LIKE '%{$subject}%'";
        $result = $connect->query($sql);
        $centers = [];
        while ($row = $result->fetch_assoc()) {
            $centers[] = $row;
        }
        return $centers;
    }

    function getAllTrainingCentres()
    {
        global $connect;
        $sql = "SELECT * FROM trainingcentres";
        $result = $connect->query($sql);
        $center = [];
        while ($row = $result->fetch_assoc()) {
            $center[] = $row;
        }
        return $center;
    }

    function getInfo($id): string
    {
        global $connect;
        $lang = $this->getLanguage();
        $sql = "select * from trainingcentres where id=" . $id . " limit 1";
        $result = $connect->query($sql)->fetch_assoc();
//        $info = $result[$lang];
//        $districts = $result['district_id'];
//        $subjects = $result['subjects'];
        $xabar = "📜 " . $this->GetText('x_m_nomi') . "\n";
        $xabar .= "🏢 " . $result['name'] . "\n \n";
//        $info = explode(';;', $info);
//        $xabar .= "💭 " . $this->GetText('x_q_izoh') . "\n";
//        $xabar .= $info[0] . "\n \n";
//        $xabar .= "📚 " . $this->GetText('x_fanlar') . "\n";
//        $subjects = explode(',', $subjects);
//        foreach ($subjects as $subject) {
//            $sql="select * from subjects where keyword='$subject' limit 1";
//            $d=$connect->query($sql)->fetch_assoc();
//            $xabar .= "🔷 " . $d[$lang] . " \n";
//        }
//        $xabar .= "\n";
//        $xabar .= "📍 " . $this->GetText('x_manzil') . " \n";
//        $xabar .= $info[1] . " \n \n";
//        $xabar .= "🗺 " . $this->GetText('x_filial') . "\n";
//        $districts = explode(',',$districts);
//        foreach ($districts as $district) {
//            $sql="select * from districts where id='$district' limit 1";
//            $d=$connect->query($sql)->fetch_assoc();
//
//            $xabar .= "🌆 " . $d[$lang] . " \n";
//        }
//        $xabar .= "\n";
//        $xabar .= "☎️ " . $this->GetText('x_telefon') . " \n";
//        $xabar .= $info[2] . " \n \n";
//        $xabar .= "👨‍💻 " . $this->GetText('x_telegram') . "  \n";
//        $xabar .= $info[3] . " \n \n \n ";
//        $xabar .= "@XorazmOquvMarkazlariBot";
        return $xabar;
    }
}