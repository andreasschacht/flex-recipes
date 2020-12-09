<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\ConfigurationEmail;
use App\Utils\FrontendLoadUrlResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Webmozart\Assert\Assert;

/**
 * Class ConfigurationEmailEventSubscriber.
 */
class ConfigurationEmailEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FrontendLoadUrlResolver
     */
    private $frontendLoadUrlResolver;

    /**
     * @var string
     */
    private $mailFrom;

    /**
     * @var string
     */
    private $configurationMailTemplate;

    /**
     * @var string
     */
    private $configurationMailSubject;

    /**
     * ConfigurationEmailEventSubscriber constructor.
     *
     * @param Environment             $twig
     * @param TranslatorInterface     $translator
     * @param FrontendLoadUrlResolver $frontendLoadUrlResolver
     * @param string                  $configurationMailFrom
     * @param string                  $configurationMailTemplate
     * @param string                  $configurationMailSubject
     */
    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        FrontendLoadUrlResolver $frontendLoadUrlResolver,
        string $configurationMailFrom,
        string $configurationMailTemplate,
        string $configurationMailSubject
    ) {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->frontendLoadUrlResolver = $frontendLoadUrlResolver;
        $this->mailFrom = $configurationMailFrom;
        $this->configurationMailTemplate = $configurationMailTemplate;
        $this->configurationMailSubject = $configurationMailSubject;
    }

    /**
     * @return array|\array[][]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['sendConfigurationMail', EventPriorities::POST_WRITE],
            ],
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function sendConfigurationMail(ViewEvent $event): void
    {
        try {
            $configurationMail = $event->getControllerResult();
            if (!$configurationMail instanceof ConfigurationEmail || Request::METHOD_POST !== $event->getRequest(
                )->getMethod()) {
                return;
            }
            Assert::notEmpty(
                $configurationMail->getEmail(),
                sprintf('ConfigurationMail %s has no email address', $configurationMail->getId())
            );
            Assert::notNull(
                $configurationMail->getConfiguration(),
                sprintf('ConfigurationMail %s has no configuration', $configurationMail->getId())
            );
            $this->sendMail(
                $configurationMail->getEmail(),
                $configurationMail
            );
        } catch (\Exception $e) {
            // Handle Exception
        }
    }

    /**
     * @param string  $to
     * @param Inquiry $configurationEmail
     *
     * @return int
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function sendMail(
        string $to,
        ConfigurationEmail $configurationEmail
    ): void {
        $configLoadUrl = $this->frontendLoadUrlResolver->getLoadUrl($configurationEmail->getConfiguration());
        $body = $this->twig->render(
            $this->configurationMailTemplate,
            [
                'loadUrl' => $configLoadUrl,
                'summary' => $configurationEmail->getConfiguration()->getSummary(),
            ]
        );
        // @TODO Send mail with mailer implementation
    }
}
