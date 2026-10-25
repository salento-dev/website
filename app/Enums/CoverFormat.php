<?php

namespace App\Enums;

enum CoverFormat: string
{
    case Website = 'website';
    case Post = 'post';
    case Story = 'story';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Sito (16:9)',
            self::Post => 'Post Instagram, LinkedIn, WhatsApp (4:5)',
            self::Story => 'Storie Instagram e stato WhatsApp (9:16)',
        };
    }

    public function width(): int
    {
        return match ($this) {
            self::Website => 1920,
            self::Post, self::Story => 1080,
        };
    }

    public function height(): int
    {
        return match ($this) {
            self::Website => 1080,
            self::Post => 1350,
            self::Story => 1920,
        };
    }
}
