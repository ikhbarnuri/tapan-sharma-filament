<?php

namespace App\Services;

use App\Models\TextMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use function Symfony\Component\String\u;

class TextMessageService
{
    public static function sendMessage(array $data, Collection $records)
    {
        $textMessages = collect([]);

        $records->map(function ($record) use ($data, $textMessages) {
            $textMessage = self::sendTextMessage($record, $data);

            $textMessages->push($textMessage);
        });

        TextMessage::query()->insert($textMessages->toArray());
    }

    public static function sendTextMessage(User $record, array $data): array
    {
        $message = Str::replace('{name}', $record->name, $data['message']);

        return [
            'message' => $message,
            'sent_by' => auth()->id() ?? null,
            'status' => TextMessage::STATUS['PENDING'],
            'sent_to' => auth()->id(),
            'remarks' => $data['remarks'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
