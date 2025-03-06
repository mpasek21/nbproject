<?php

class Db {

    private $mysqli; //uchwyt do BD
    private $wynik_select; //wynik

    public function __construct($serwer, $user, $pass, $baza) {
        $this->mysqli = new mysqli($serwer, $user, $pass, $baza);
        if ($this->mysqli->connect_errno) {
            printf("Nie udało sie połączenie z serwerem: %s \n", $mysqli->connect_error);
            exit();
        }
        if ($this->mysqli->set_charset("utf8")) {
            //udało sie zmienić kodowanie
        }
    }

    function __destruct() {
        $this->mysqli->close();
    }

    //parametr $sql – łańcuch zapytania select
    //Wynik funkcji – tablica asocjacyjna z rekordami
    public function select($sql) {
        $wyniki = array();
        $indeks = 0;
        if ($result = $this->mysqli->query($sql)) {
            while ($row = $result->fetch_object()) {
                $wyniki[] = $row;
            }
            $result->close();
        }
        $this->wynik_select = $wyniki;
        return $wyniki;
    }


    public function getMessage($message_id, $zalogowany) {
        foreach ($this->wynik_select as $message):
            if ($message->id_message == $message_id) {//jeśli znaleziono żądaną treść
                $czy_dla_zalogowanych = false;
                if ($message->type == 'private')
                    $czy_dla_zalogowanych = true;
                if (!$czy_dla_zalogowanych || $zalogowany)
                    return $message->message;
                else
                    return 'Musisz być zalogowany by zobaczyć tę treść.';
            }
        endforeach;
    }

    public function insert($sql) {
        if ($this->mysqli->query($sql)) {
            echo 'Użytkownik dodany';
        } else
            echo 'Nie udało się zapisać danych';
    }

    public function addMessage($name,$type,$content){
        $sql = "INSERT INTO messages (`name`,`type`, `message`,`active`)
       VALUES ('" . $name . "','" . $type . "','" . $content . "',0)";
        echo $sql;
        echo "<BR\>";
        return $this->mysqli->query($sql);
        }
       
    public function deleteMessage($id_message) {
        $sql = 'DELETE from messages WHERE id_message=' . $id_message;
        if ($this->mysqli->query($sql))
            return true;
        return false;
    }

}

?>