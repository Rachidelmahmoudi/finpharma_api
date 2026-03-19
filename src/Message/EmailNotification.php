<?php

namespace App\Message;

class EmailNotification
{
    public function __construct(
        private string $from,
        private string $to,
        private string $subject,
        private string $content,
    ) {
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}