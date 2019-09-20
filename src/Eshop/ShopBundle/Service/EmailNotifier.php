<?php
namespace Eshop\ShopBundle\Service;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Routing\Router;

class EmailNotifier
{
    /**
     * @var Router $router
     */
    private $mailer;
    private $router;
    private $templating;
    const FROM_EMAIL = 'server@website.com';

    public function __construct(\Swift_Mailer $mailer, Router $router, TwigEngine $templating)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;

    }

    /**
     * @param $parametersArray array
     */
    public function handleNotification($parametersArray)
    {
        $event = $parametersArray['event'];
        switch ($event) {
            case 'new_order':
                $this->sendNewOrderNotification($parametersArray);
                break;
        }
    }

    /**
     * @param $parametersArray array
     */
    private function sendNewOrderNotification($parametersArray)
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
                    'ShopBundle:Mail:orderNotificationEmail.txt.twig', [
                        'subject' => $subject,
                        'url' => $url
                    ]
                ));

        $this->mailer->send($message);
    }
}
