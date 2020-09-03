<?php

namespace WeStacks\TeleBot\Tests\Feature;

use PHPUnit\Framework\TestCase;
use WeStacks\TeleBot\Bot;
use WeStacks\TeleBot\Exception\TeleBotFileException;
use WeStacks\TeleBot\Objects\Message;

class SendPhotoTest extends TestCase
{
    /**
     * @var Bot
     */
    private $bot;

    protected function setUp(): void
    {
        $this->bot = new Bot(getenv('TELEGRAM_BOT_TOKEN'));
    }

    public function testSendPhotoFromUrl()
    {
        $message = $this->bot->sendPhoto([
            'chat_id' => getenv('TELEGRAM_USER_ID'),
            'photo' => "https://picsum.photos/640"
        ]);
        $this->assertInstanceOf(Message::class, $message);
    }

    public function testSendPhotoFromContents()
    {
        $message = $this->bot->sendPhoto([
            'chat_id' => getenv('TELEGRAM_USER_ID'),
            'photo' => [
                'file' => fopen('https://picsum.photos/640', 'r'),
                'filename' => 'test-image.jpg'
            ]
        ]);
        $this->assertInstanceOf(Message::class, $message);
    }

    public function testSendPhotoFromFile()
    {
        file_put_contents(__DIR__.'/test-image.jpg', fopen('https://picsum.photos/640', 'r'));

        $message = $this->bot->sendPhoto([
            'chat_id' => getenv('TELEGRAM_USER_ID'),
            'photo' => __DIR__.'/test-image.jpg'
        ]);
        $this->assertInstanceOf(Message::class, $message);

        unlink(__DIR__.'/test-image.jpg');
    }

    public function testSendPhotoNull()
    {
        $this->expectException(TeleBotFileException::class);
        $this->bot->sendPhoto([
            'chat_id' => getenv('TELEGRAM_USER_ID'),
            'photo' => null
        ]);
    }
}