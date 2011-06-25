<?php

namespace Ltc\CoreBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends ContainerAware
{
    /**
     * Converts an Exception to a Response.
     *
     * @param FlattenException     $exception A FlattenException instance
     * @param DebugLoggerInterface $logger    A DebugLoggerInterface instance
     * @param string               $format    The format to use for rendering (html, xml, ...)
     *
     * @throws \InvalidArgumentException When the exception template does not exist
     */
    public function showAction(FlattenException $exception, DebugLoggerInterface $logger = null, $format = 'html')
    {
        $this->container->get('request')->setRequestFormat($format);

        $code = $exception->getStatusCode();
        $params = array(
            'status_code'    => $code,
            'status_text'    => Response::$statusTexts[$code],
        );
        $templating = $this->container->get('templating');

        if(404 == $code) {
            if($this->container->get('request')->isXmlHttpRequest()) {
                $response = new Response('You should not do that.');
            } else {
                $response = $templating->renderResponse('LtcCoreBundle:Exception:notFound.html.twig', $params);
            }
        } else {
            if($this->container->get('request')->isXmlHttpRequest()) {
                $response = new Response('Something went terribly wrong.');
            } else {
                $params['url'] = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
                $response = $templating->renderResponse('LtcCoreBundle:Exception:error.html.twig', $params);
            }
        }
        $response->setStatusCode($code);
        $response->headers->replace($exception->getHeaders());

        return $response;
    }
}
