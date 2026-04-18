<?php
class ChatHistory_m extends connectDB
{
    private function esc($value)
    {
        return mysqli_real_escape_string($this->con, (string)$value);
    }

    function getOrCreateConversation($ma_user = '', $guest_token = '')
    {
        $ma_user = trim((string)$ma_user);
        $guest_token = trim((string)$guest_token);

        if ($ma_user !== '') {
            $userEsc = $this->esc($ma_user);
            $sqlFind = "SELECT id FROM chat_conversations WHERE ma_user = '$userEsc' AND status = 'active' ORDER BY id DESC LIMIT 1";
            $resultFind = mysqli_query($this->con, $sqlFind);
            if ($resultFind && mysqli_num_rows($resultFind) > 0) {
                $row = mysqli_fetch_assoc($resultFind);
                return (int)($row['id'] ?? 0);
            }
        } else if ($guest_token !== '') {
            $guestEsc = $this->esc($guest_token);
            $sqlFind = "SELECT id FROM chat_conversations WHERE guest_token = '$guestEsc' AND status = 'active' ORDER BY id DESC LIMIT 1";
            $resultFind = mysqli_query($this->con, $sqlFind);
            if ($resultFind && mysqli_num_rows($resultFind) > 0) {
                $row = mysqli_fetch_assoc($resultFind);
                return (int)($row['id'] ?? 0);
            }
        }

        $conversationCode = 'CV' . bin2hex(random_bytes(10));
        $conversationCodeEsc = $this->esc($conversationCode);

        if ($ma_user !== '') {
            $userEsc = $this->esc($ma_user);
            $guestValue = $guest_token !== '' ? "'" . $this->esc($guest_token) . "'" : "NULL";
            $sqlInsert = "INSERT INTO chat_conversations (conversation_code, ma_user, guest_token, status, created_at, updated_at)
                          VALUES ('$conversationCodeEsc', '$userEsc', $guestValue, 'active', NOW(), NOW())";
        } else {
            $guestValue = $guest_token !== '' ? "'" . $this->esc($guest_token) . "'" : "NULL";
            $sqlInsert = "INSERT INTO chat_conversations (conversation_code, ma_user, guest_token, status, created_at, updated_at)
                          VALUES ('$conversationCodeEsc', NULL, $guestValue, 'active', NOW(), NOW())";
        }

        if (!mysqli_query($this->con, $sqlInsert)) {
            return 0;
        }

        return (int)mysqli_insert_id($this->con);
    }

    function saveMessage($conversation_id, $ma_user, $sender, $message, $intent = '')
    {
        $conversation_id = (int)$conversation_id;
        if ($conversation_id <= 0) {
            return false;
        }

        $sender = ($sender === 'bot') ? 'bot' : 'user';
        $message = trim((string)$message);
        if ($message === '') {
            return false;
        }

        $ma_user = trim((string)$ma_user);
        $messageEsc = $this->esc($message);
        $intentEsc = $this->esc((string)$intent);
        $userValue = $ma_user !== '' ? "'" . $this->esc($ma_user) . "'" : "NULL";

        $sql = "INSERT INTO chat_messages (conversation_id, ma_user, sender, message, intent, created_at)
                VALUES ($conversation_id, $userValue, '$sender', '$messageEsc', '$intentEsc', NOW())";

        if (!mysqli_query($this->con, $sql)) {
            return false;
        }

        mysqli_query($this->con, "UPDATE chat_conversations SET updated_at = NOW() WHERE id = $conversation_id");
        return true;
    }

    function getHistory($ma_user = '', $guest_token = '', $limit = 100)
    {
        $limit = max(1, min(300, (int)$limit));
        $ma_user = trim((string)$ma_user);
        $guest_token = trim((string)$guest_token);

        if ($ma_user !== '') {
            $userEsc = $this->esc($ma_user);
            $sqlConv = "SELECT id FROM chat_conversations WHERE ma_user = '$userEsc' AND status = 'active' ORDER BY id DESC LIMIT 1";
        } else if ($guest_token !== '') {
            $guestEsc = $this->esc($guest_token);
            $sqlConv = "SELECT id FROM chat_conversations WHERE guest_token = '$guestEsc' AND status = 'active' ORDER BY id DESC LIMIT 1";
        } else {
            return ['conversation_id' => 0, 'items' => []];
        }

        $resultConv = mysqli_query($this->con, $sqlConv);
        if (!$resultConv || mysqli_num_rows($resultConv) === 0) {
            return ['conversation_id' => 0, 'items' => []];
        }

        $convRow = mysqli_fetch_assoc($resultConv);
        $conversationId = (int)($convRow['id'] ?? 0);
        if ($conversationId <= 0) {
            return ['conversation_id' => 0, 'items' => []];
        }

        $sqlItems = "SELECT id, sender, message, intent, created_at
                    FROM chat_messages
                    WHERE conversation_id = $conversationId
                    ORDER BY id ASC
                    LIMIT $limit";
        $resultItems = mysqli_query($this->con, $sqlItems);

        $items = [];
        if ($resultItems) {
            while ($row = mysqli_fetch_assoc($resultItems)) {
                $items[] = [
                    'id' => (int)($row['id'] ?? 0),
                    'sender' => $row['sender'] ?? 'bot',
                    'message' => $row['message'] ?? '',
                    'intent' => $row['intent'] ?? '',
                    'created_at' => $row['created_at'] ?? ''
                ];
            }
        }

        return [
            'conversation_id' => $conversationId,
            'items' => $items
        ];
    }
}
