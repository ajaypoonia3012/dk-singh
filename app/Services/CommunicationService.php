<?php

namespace App\Services;

use App\Models\CommunicationProvider;
use App\Models\MessageTemplate;
use App\Models\CommunicationLog;
use App\Models\User;

class CommunicationService
{
    public function send(
        string $event,
        User $user,
        array $data = []
    ): bool
    {
        $templates = MessageTemplate::where(
            'event',
            $event
        )
        ->where(
            'is_active',
            true
        )
        ->get();

        foreach ($templates as $template) {

            $provider = CommunicationProvider::where(
                'type',
                $template->channel
            )
            ->where(
                'is_active',
                true
            )
            ->where(
                'is_default',
                true
            )
            ->first();

            if (! $provider) {
                continue;
            }

            $message = $this->replaceVariables(
                $template->message,
                $data
            );

            CommunicationLog::create([

                'user_id' => $user->id,

                'communication_provider_id' =>
                    $provider->id,

                'channel' =>
                    $template->channel,

                'recipient' =>
                    $template->channel === 'email'
                        ? $user->email
                        : $user->whatsapp_number,

                'message' => $message,

                'status' => 'pending',

                'sent_at' => now(),

            ]);
        }

        return true;
    }

    private function replaceVariables(
        string $message,
        array $data
    ): string
    {
        foreach ($data as $key => $value) {

            $message = str_replace(
                '{' . $key . '}',
                $value,
                $message
            );
        }

        return $message;
    }
}