<?php

namespace App\Services;

/**
 * SmallTalkService (scaffold)
 * - Handles greetings, thanks, identity, and generic polite replies.
 */
class SmallTalkService
{
    public function reply(string $query): string
    {
        $l = mb_strtolower(trim($query ?? ''), 'UTF-8');
        if ($l === '') return 'Hai! Ada data yang bisa saya bantu?';

        if (preg_match('/\b(hai|halo|hello|ass?alam|selamat (pagi|siang|sore|malam))\b/u', $l)) {
            return 'Halo! Ada data yang bisa saya bantu?';
        }
        if (preg_match('/\b(terima kasih|makasih|thanks|thank you)\b/u', $l)) {
            return 'Sama-sama!';
        }
        if (preg_match('/\b(kamu siapa|siapa kamu|who are you)\b/u', $l)) {
            return 'Saya asisten data Pusdatin Kementan. Saya bisa bantu menampilkan data Lahan, Benih & Pupuk, atau Iklim & OPT DPI. Apa data yang ingin Anda cari?';
        }
        if (preg_match('/\b(maaf|sorry)\b/u', $l)) {
            return 'Tidak apa-apa. Apa yang ingin Anda cari?';
        }
        return 'Baik, bagaimana saya bisa bantu?';
    }
}
