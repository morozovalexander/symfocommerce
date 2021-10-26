<?php

namespace App\Service;

use Swift_Mailer;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Templating;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EmailNotifier
{
    /** @var Swift_Mailer */
    private $mailer;

    /** @var RouterInterface */
    private $router;

    /** @var Templating */
    private $templating;

    /** @var string */
    public const FROM_EMAIL = 'server@website.com';

    /**
     * EmailNotifier constructor.
     * @param Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param Templating $templating
     */
    public function __construct(Swift_Mailer $mailer, RouterInterface $router, Templating $templating)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
    }

    /**
     * @param array $parametersArray
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function handleNotification(array $parametersArray): void
    {
        $event = $parametersArray['event'];
        if ($event === 'new_order') {
            $this->sendNewOrderNotification($parametersArray);
        }
    }

    /**
     * @param $parametersArray array
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function sendNewOrderNotification(array $parametersArray): void
    {
        $orderId = $parametersArray['order_id'];
        $to = $parametersArray['admin_email'];
        $subject = 'new order notification';

        //url generation
        $url = $this->router->generate(
            'admin_order_show',
            ['id' => $orderId],
            true
        );

        $message = (new \Swift_Message($subject))
            ->setFrom(self::FROM_EMAIL)
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                    'mail/order_notification_email.txt.twig', [
                        'subject' => $subject,
                        'url' => $url
                    ]
                ));

        $this->mailer->send($message);
    }
}
