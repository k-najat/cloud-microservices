<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumeArticleEvents extends Command
{
    protected $signature   = 'rabbitmq:consume';
    protected $description = 'Listen to article events from RabbitMQ';

    public function handle(): void
    {
        $this->info('Connecting to RabbitMQ...');

        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'rabbitmq'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest'),
            env('RABBITMQ_VHOST', '/')
        );

        $channel = $connection->channel();

        // Listen on both queues
        foreach (['article.created', 'article.updated'] as $queue) {
            $channel->queue_declare($queue, false, true, false, false);

            $channel->basic_consume(
                $queue,
                '',
                false,
                false,
                false,
                false,
                function ($msg) use ($queue) {
                    $data = json_decode($msg->body, true);
                    $this->info("Event received [{$queue}]: " . $data['title']);

                    // Save notification for the user
                    Notification::create([
                        'user_id' => $data['user_id'],
                        'type'    => $data['event'],
                        'message' => $queue === 'article.created'
                            ? "Votre article \"{$data['title']}\" a été créé avec succès."
                            : "Votre article \"{$data['title']}\" a été mis à jour.",
                        'data'    => $data,
                        'read'    => false,
                    ]);

                    $msg->ack();
                }
            );
        }

        $this->info('Waiting for events... (Ctrl+C to stop)');

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
