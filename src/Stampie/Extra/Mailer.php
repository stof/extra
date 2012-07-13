<?php

namespace Stampie\Extra;

use Stampie\Extra\Event\MessageEvent;
use Stampie\MailerInterface;
use Stampie\MessageInterface;
use Stampie\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * MailerInterface decorator dispatching events
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class Mailer implements MailerInterface
{
    private $delegate;
    private $dispatcher;

    public function __construct(MailerInterface $delegate, EventDispatcherInterface $dispatcher)
    {
        $this->delegate = $delegate;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function send(MessageInterface $message)
    {
        $event = new MessageEvent($message);
        $this->dispatcher->dispatch(StampieEvents::PRE_SEND, $event);

        return $this->delegate->send($event->getMessage());
    }

    /**
     * {@inheritDoc}
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->delegate->setAdapter($adapter);
    }

    /**
     * {@inheritDoc}
     */
    public function getAdapter()
    {
        return $this->delegate->getAdapter();
    }

    /**
     * {@inheritDoc}
     */
    public function setServerToken($serverToken)
    {
        $this->delegate->setServerToken($serverToken);
    }

    /**
     * {@inheritDoc}
     */
    public function getServerToken()
    {
        return $this->delegate->getServerToken();
    }
}