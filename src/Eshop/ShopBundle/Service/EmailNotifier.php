<?php

namespace Eshop\ShopBundle\Service;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Routing\Router;
use Twig\Error\Error;

/**
 * Class EmailNotifier
 * @package Eshop\ShopBundle\Service
 */
class EmailNotifier
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var Router */
    private $router;

    /** @var TwigEngine */
    private $templating;

    /** @var string */
    public const FROM_EMAIL = 'server@website.com';

    /**
     * EmailNotifier constructor.
     * @param \Swift_Mailer $mailer
     * @param Router $router
     * @param TwigEngine $templating
     */
    public function __construct(\Swift_Mailer $mailer, Router $router, TwigEngine $templating)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
    }

    /**
     * @param array $parametersArray
     * @throws Error
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
     * @throws Error
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

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(self::FROM_EMAIL)
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                    'shop/mail/order_notification_email.txt.twig', [
                        'subject' => $subject,
                        'url' => $url
                    ]
                ));

        $this->mailer->send($message);
    }
}
