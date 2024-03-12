<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use App\Payments;
use App\Filter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redis;

class ChatController extends Controller
{
    const CHAT_CHANNEL = 'chat.message';
    const NEW_MSG_CHANNEL = 'new.msg';
    const CLEAR = 'chat.clear';
    const DELETE_MSG_CHANNEL = 'del.msg';
    const BAN_CHANNEL = 'ban.msg';

    public function __construct()
    {
        parent::__construct();
        $this->redis = Redis::connection();
    }

    public static function chat()
    {
        $redis = Redis::connection();

        $value = $redis->lrange(self::CHAT_CHANNEL, 0, -1);
        $i = 0;
        $returnValue = NULL;
        $value = array_reverse($value);

        foreach ($value as $key => $newchat[$i]) {
            if ($i > 20) {
                break;
            }
            $value2[$i] = json_decode($newchat[$i], true);

            $value2[$i]['username'] = htmlspecialchars($value2[$i]['username']);

            $returnValue[$i] = [
                'unique_id' => $value2[$i]['unique_id'],
                'avatar' => $value2[$i]['avatar'],
                'time' => $value2[$i]['time'],
                'time2' => $value2[$i]['time2'],
                'ban' => self::checkBan($value2[$i]['unique_id']),
                'messages' => $value2[$i]['messages'],
                'username' => $value2[$i]['username'],
                'youtuber' => $value2[$i]['youtuber'],
                'moder' => $value2[$i]['moder'],
                'admin' => $value2[$i]['admin']];

            $i++;

        }

        if (!is_null($returnValue)) return array_reverse($returnValue);
    }

    private static function checkBan($id)
    {
        $user = User::where('unique_id', $id)->first();
        $ban = 0;
        if (!is_null($user['banchat'])) $ban = 1;
        return $ban;
    }

    public function __destruct()
    {
        $this->redis->disconnect();
    }

    public function ban(Request $r)
    {
        $user = User::where('unique_id', $r->get('id'))->first();
        if (is_null($user)) return response()->json(['success' => false, 'msg' => 'Usuário não encontrado!', 'type' => 'error']);
        if (!is_numeric($r->get('time')) || $r->get('time') <= 0) return response()->json(['success' => false, 'msg' => 'Você digitou um valor inválido para o campo "Hora"!', 'type' => 'error']);
        if (!is_null($user->banchat)) return response()->json(['success' => false, 'msg' => 'O usuário já está bloqueado!', 'type' => 'info']);
        if ($user->unique_id == $this->user->unique_id) return response()->json(['success' => false, 'msg' => 'Você não pode se bloquear!', 'type' => 'info']);

        $user->banchat = Carbon::now()->addMinutes(floatval($r->get('time')))->getTimestamp();
        $user->banchat_reason = htmlspecialchars($r->get('reason'));
        $user->save();

        $banusername = preg_replace('#(.*)\s+(.).*#usi', '$1 $2.', htmlspecialchars($user->username));

        $time = date('H:i', time());
        $moder = $this->user->is_moder;
        $youtuber = $this->user->is_youtuber;
        $unique_id = $this->user->unique_id;
        $avatar = $this->user->avatar;
        $ban = 0;
        if (!is_null($this->user->banchat)) $ban = 1;
        $username = preg_replace('#(.*)\s+(.).*#usi', '$1 $2.', htmlspecialchars($this->user->username));
        $admin = 0;
        if ($this->user->is_admin) {
            $avatar = '/img/no_avatar.jpg';
            $unique_id = null;
            $admin = 1;
        }

        $returnValue = ['unique_id' => $unique_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<span style="color: #4986f5;">Пользователь "' . $user->username . '" заблокирован в Chatе на ' . floatval($r->get('time')) . ' мин.' . ($r->get('reason') ? ' Причина: ' . htmlspecialchars($r->get('reason')) . '.' : '') . '</span>', 'username' => $username, 'ban' => $ban, 'admin' => $admin, 'moder' => $moder, 'youtuber' => $youtuber];
        $returnBan = ['unique_id' => $user->unique_id, 'username' => $banusername, 'ban' => 1];
        $this->redis->publish(self::BAN_CHANNEL, json_encode($returnBan));
        $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
        $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));

        return response()->json(['success' => true, 'msg' => 'O membro ' . $user->username . ' foi bloqueado no chat por ' . $r->get('time') . ' minutos!', 'type' => 'success']);
    }

    public function unban(Request $r)
    {
        $user = User::where('unique_id', $r->get('id'))->first();
        if (is_null($user)) return response()->json(['success' => false, 'msg' => 'Usuário não encontrado!', 'type' => 'error']);
        if (is_null($user->banchat)) return response()->json(['success' => false, 'msg' => 'O usuário não está bloqueado!', 'type' => 'info']);
        if ($user->unique_id == $this->user->unique_id) return response()->json(['success' => false, 'msg' => 'Você não pode desbloquear a si mesmo!', 'type' => 'info']);

        $user->banchat = null;
        $user->banchat_reason = null;
        $user->save();

        $banusername = preg_replace('#(.*)\s+(.).*#usi', '$1 $2.', htmlspecialchars($user->username));

        $time = date('H:i', time());
        $moder = $this->user->is_moder;
        $youtuber = $this->user->is_youtuber;
        $unique_id = $this->user->unique_id;
        $avatar = $this->user->avatar;
        $ban = 0;
        if (!is_null($this->user->banchat)) $ban = 1;
        $username = preg_replace('#(.*)\s+(.).*#usi', '$1 $2.', htmlspecialchars($this->user->username));
        $admin = 0;
        if ($this->user->is_admin) {
            $avatar = '/img/no_avatar.jpg';
            $unique_id = null;
            $admin = 1;
        }

        $returnValue = ['unique_id' => $unique_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<span style="color: #4986f5;">O membro "' . $user->username . '" foi desbloqueado no chat.</span>', 'username' => $username, 'ban' => $ban, 'admin' => $admin, 'moder' => $moder, 'youtuber' => $youtuber];
        $returnBan = ['unique_id' => $user->unique_id, 'username' => $banusername, 'ban' => 0];
        $this->redis->publish(self::BAN_CHANNEL, json_encode($returnBan));
        $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
        $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));

        return response()->json(['success' => true, 'msg' => 'O membro ' . $user->username . ' foi desbloqueado no Chat!', 'type' => 'success']);
    }

    public function clear()
    {
        $this->redis->del(self::CHAT_CHANNEL);
        $this->redis->publish(self::CLEAR, 1);
        return response()->json(['success' => true, 'msg' => 'Mensagens do bate-papo foram apagadas!', 'type' => 'success']);
    }

    public function unBanFromUser()
    {
        $users = User::where('banchat', '!=', NULL)->orderBy('banchat', 'asc')->get();
        if ($users == '[]') return response()->json(['msg' => 'Usuários não encontrados!', 'status' => 'error']);
        foreach ($users as $usr) {
            $nowtime = time();
            if ($usr->banchat <= $nowtime) {
                User::where('unique_id', $usr->unique_id)->update(['banchat' => null, 'banchat_reason' => null]);
                $returnBan = ['unique_id' => $usr->unique_id, 'username' => $usr->username, 'ban' => 0];
                $this->redis->publish(self::BAN_CHANNEL, json_encode($returnBan));
            }
        }
        return response()->json(['msg' => 'Os usuários foram desbloqueados', 'status' => 'success']);
    }

    public function add_message(Request $request)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['message' => 'Você está enviando muitas mensagens com frequência!', 'status' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 5);
        $val = \Validator::make($request->all(), [
            'messages' => 'required|string|max:255'
        ], [
            'required' => 'A mensagem não pode estar vazia!',
            'string' => 'A mensagem deve ser uma string!',
            'max' => 'O tamanho máximo da mensagem é de 255 caracteres.',
        ]);
        $error = $val->errors();

        if ($val->fails()) {
            return response()->json(['message' => $error->first('messages'), 'status' => 'error']);
        }

        if ($this->user->is_admin) $messages = strip_tags($request->get('messages'), '<img>');
        else {
            if (substr_count(strtolower($request->get('messages')), '<img class="s')) $messages = $request->get('messages');
            else $messages = html_entity_decode(strip_tags($request->get('messages'), '<img>'));
        }

        $dep = Payments::where('user_id', $this->user->id)->where('status', 1)->sum('sum');
        if (!$this->user->is_admin && !$this->user->is_moder && !$this->user->is_youtuber) {
            if ($this->settings->chat_dep != 0)
                if ($dep < $this->settings->chat_dep) {
                return response()->json(['message' => 'Para escrever no Chat, você precisa pagar R$ ' . $this->settings->chat_dep . '!', 'status' => 'error']);
            }
        }

        $nowtime = time();
        $banchat = $this->user->banchat;
        if ($banchat >= $nowtime) {
            return response()->json(['message' => 'Você está bloqueado até ' . date("d.m.Y H:i:s", $banchat), 'status' => 'error']);
        }

        $words = Filter::get();
        foreach ($words as $value) {
            if (substr_count(mb_strtolower($messages), $value->word)) $messages = mb_strtolower($messages);
            $messages = str_ireplace($value->word, $this->settings->censore_replace, $messages);
        }

        $time = date('H:i', time());
        $moder = $this->user->is_moder;
        $youtuber = $this->user->is_youtuber;
        $admin = 0;
        $ban = $this->user->banchat;
        $unique_id = $this->user->unique_id;
        $username = preg_replace('#(.*)\s+(.).*#usi', '$1 $2.', htmlspecialchars($this->user->username));
        $avatar = $this->user->avatar;
        if ($this->user->is_admin) {
            if ($request->get('optional')) {
                $admin = 1;
            }
        }
        if ($admin) {
            $avatar = '/img/no_avatar.jpg';
            $unique_id = '';
        }

        function object_to_array($data)
        {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = object_to_array($value);
                }
                return $result;
            }
            return $data;
        }

        if (substr_count(strtolower($messages), '$bal')) {
            if (\Cache::has('bal.' . $request->get('balType') . '.user.' . $this->user->id)) return response()->json(['message' => 'Você realizou esta ação com muita frequência!', 'status' => 'error']);
            // \Cache::put('bal.'.$request->get('balType').'.user.' . $this->user->id, '', 300);
            $returnValue = ['unique_id' => $unique_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<div class="chat-transaction flex-row align-center"><div class="chat-transaction__icon"><svg class="icon"><use xlink:href="/img/symbols.svg#icon-bank-' . $request->get('balType') . '"></use></svg></div><div class="flex-column flex-column_align-start"><div class="chat-transaction__status">' . ($request->get('balType') == 'balance' ? 'Meu saldo atual' : 'Meu bônus atual') . '</div><span class="chat-message-transaction-link">' . ($request->get('balType') == 'balance' ? $this->user->balance : $this->user->bonus) . ' <svg class="icon icon-coin balance ' . $request->get('balType') . '"><use xlink:href="/img/symbols.svg#icon-coin"></use></svg></span></div></div>', 'username' => $username, 'ban' => $ban, 'admin' => $admin, 'moder' => $moder, 'youtuber' => $youtuber];
            $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
            $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
            return response()->json(['message' => 'Você mostrou seu saldo no Chat!', 'status' => 'success']);
        }

        if (preg_match("/href|url|http|https|www|.ru|.com|.net|.info|csgo|winner|ru|xyz|com|net|info|.org/i", $messages)) {
            return response()->json(['message' => 'Links são proibidos!', 'status' => 'error']);
        }
        $messages = htmlspecialchars($messages);
        $returnValue = ['unique_id' => $unique_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => $messages, 'username' => $username, 'ban' => $ban, 'admin' => $admin, 'moder' => $this->user->is_moder, 'youtuber' => $this->user->is_youtuber];
        $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
        $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
        return response()->json(['message' => 'Sua mensagem foi enviada!', 'status' => 'success']);
    }

    public function delete_message(Request $request)
    {
        $value = $this->redis->lrange(self::CHAT_CHANNEL, 0, -1);
        $i = 0;
        $json = json_encode($value);
        $json = json_decode($json);
        foreach ($json as $newchat) {
            $val = json_decode($newchat);

            if ($val->time2 == $request->get('messages')) {
                $this->redis->lrem(self::CHAT_CHANNEL, 1, json_encode($val));
                $this->redis->publish(self::DELETE_MSG_CHANNEL, json_encode($val));
            }
            $i++;
        }
        return response()->json(['message' => 'Mensagem Deletada!', 'status' => 'success']);
    }
}
