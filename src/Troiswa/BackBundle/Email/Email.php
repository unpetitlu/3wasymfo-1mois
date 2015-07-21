<?php
namespace Troiswa\BackBundle\Email;
/**
 * Email Service Class
 */
class Email
{

    /**
     * @var \Swift_Mailer Swift Mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment Twig Template Engine
     */
    protected $twig;

    /**
     * Constructeur de ma classe Email
     * J'ai besoin du service swift mailer et
     * du service Twig
     * En arguments: je récupère Swift Mailer,
     */
    public function __construct(\Swift_Mailer $mailer,
                                \Twig_Environment $twig){
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param $to
     * @param string $from
     * @param null $templating
     * @param string $subject
     * @param array $data
     */
    public function sendMyMail($to,
                              $from = 'contact@3wa.fr',
                              $templating = null,
                              $subject = "Bienvenue sur symfony",
                              $data = []) {
        // Sending Email
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom($from)
            ->setBody($this->twig->render('TroiswaBackBundle:Mail:'.$templating.'.html.twig', [
                'data' => $data,
            ]), 'text/html')
            ->addPart($this->twig->render('TroiswaBackBundle:Mails:'.$templating.'.txt.twig', [
                'data' => $data,
            ]), 'text/plain');
        $this->mailer->send($message);
    }
}