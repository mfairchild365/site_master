<?php
namespace SiteMaster\Core;

abstract class EmailInterface
{
    abstract public function getSubject();

    abstract public function getTo();

    public function getFrom()
    {
        return array(Config::get('EMAIL_FROM_ADDRESS'));
    }
    
    public function toHTML()
    {
        $savvy = new OutputController(array('format' => 'email'));
        return $savvy->render($this);
    }
    
    public function send()
    {
        // Create a message
        $message = \Swift_Message::newInstance('Wonderful Subject');
        $message->setFrom(array('john@doe.com' => 'John Doe'));
        $message->setTo(array('mfairchild365@gmail.com' => 'A name'));
        $message->setBody('Here is the message itself');
        var_dump(Config::get('ENV'));
        if (Config::get('ENV') == 'DEVELOP') {
            $message->setTo(array('mfairchild365@gmail.com'));
            $message->setBody('test test');
        }

        // Mail
        $transport = \Swift_MailTransport::newInstance();
        $transport = \Swift_SmtpTransport::newInstance('smtp.mandrillapp.com', 587);
        $transport->setUsername('mfairchild365@gmail.com');
        $transport->setPassword('uRL3MQW7ZIlJR9_Kxbwf6A');
        //$swift = Swift_Mailer::newInstance($transport);

        // Create the Mailer using your created Transport
        $mailer = \Swift_Mailer::newInstance($transport);

        // Send the message
        $result = $mailer->send($message);
        var_dump($result);
    }
}
