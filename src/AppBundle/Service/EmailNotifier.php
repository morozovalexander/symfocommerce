<?php

namespace AppBundle\Service;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Error\Error;

/**
 * Class EmailNotifier
 * @package AppBundle\Service
 */
class EmailNotifier
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var RouterInterface */
    private $router;

    /** @var EngineInterface */
    private $templating;

    /** @var string */
    public const FROM_EMAIL = 'server@website.com';

    /**
     * EmailNotifier constructor.
     * @param \Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param EngineInterface $templating
     */
    public function __construct(\Swift_Mailer $mailer, RouterInterface $router, EngineInterface $templating)
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
                    'mail/order_notification_email.txt.twig', [
                        'subject' => $subject,
                        'url' => $url
                    ]
                ));

        $this->mailer->send($message);
    }
}
