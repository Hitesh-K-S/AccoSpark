<?php

namespace App\Services;

use App\Models\AIPersona;

class CheckinAIService
{
    public function respond(string $status): string
    {
        $persona = AIPersona::where('is_active', true)->first();

        $tone = $persona?->tone ?? 'coach';

        return match ($tone) {
            'chill' => $this->chillResponse($status),
            'rival' => $this->rivalResponse($status),
            'chaos' => $this->chaosResponse($status),
            default => $this->coachResponse($status),
        };
    }

    private function coachResponse($status): string
    {
        return match ($status) {
            'completed' => "Nice work. Consistency like this builds momentum.",
            'partial'   => "Partial progress still counts. Tomorrow we adjust.",
            'missed'    => "Thanks for being honest. We'll recalibrate and move forward.",
        };
    }

    private function chillResponse($status): string
    {
        return match ($status) {
            'completed' => "That’s a win. Don’t overthink it.",
            'partial'   => "Some effort is better than none. You’re good.",
            'missed'    => "It happens. We try again tomorrow.",
        };
    }

    private function rivalResponse($status): string
    {
        return match ($status) {
            'completed' => "Alright, point to you. Score’s even.",
            'partial'   => "Half credit. I’m still ahead.",
            'missed'    => "Missed it. Score’s mine today.",
        };
    }

    private function chaosResponse($status): string
    {
        return match ($status) {
            'completed' => "WOW. YOU DID A THING. I’M UNCOMFORTABLY PROUD.",
            'partial'   => "YOU STARTED. THEN PANICKED. CLASSIC.",
            'missed'    => "YOU DODGED REALITY. I SAW THAT.",
        };
    }
}
