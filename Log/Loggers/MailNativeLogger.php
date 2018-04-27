<?php
/**
 * Created by PhpStorm.
 * User: Vieraw
 * Date: 27.01.2018
 * Time: 19:01
 */

namespace Log\Loggers;

class MailNativeLogger extends Base
{
    public $subject;
    public $maxColumn = 50;
    public $pattern = '{date} {level} {message} {context}';

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $this->send(trim(strtr($this->pattern,
            [
                '{date}' => $this->getDate(),
                '{level}' => $level,
                '{message}' => $this->interpolate($message, $context),
                '{context}' => $this->stringify($context)
            ])) . PHP_EOL);
    }

    /**
     * @param $content
     */
    protected function send($content)
    {
        $contentType = $this->getContentType() ?: $this->isHtml($content) ? 'text/html' : 'text/plain';

        if ($contentType !== 'text/html')
        {
            $content = wordwrap($content, $this->maxColumn);
        }

        $headers = ltrim(implode("\r\n", $this->headers) . "\r\n", "\r\n");
        $headers .= 'Content-type: ' . $contentType . '; charset=' . $this->getEncoding() . "\r\n";

        if ($contentType === 'text/html' && false === strpos($headers, 'MIME-Version:'))
        {
            $headers .= 'MIME-Version: 1.0' . "\r\n";
        }

        $subject = $this->subject;

        $parameters = implode(' ', $this->parameters);

        foreach ($this->to as $to)
        {
            \mail($to, $subject, $content, $headers, $parameters);
        }
    }

    protected $headers = [];

    /**
     * @param $headers
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setHeader($headers)
    {
        foreach ((array) $headers as $header)
        {
            if (strpos($header, "\n") !== false || strpos($header, "\r") !== false)
            {
                throw new \InvalidArgumentException('Headers can not contain newline characters for security reasons');
            }
            $this->headers[] = $header;
        }

        return $this;
    }

    protected $parameters = [];

    /**
     * @param $parameters
     * @return $this
     */
    public function setParameter($parameters)
    {
        $this->parameters = array_merge($this->parameters, (array) $parameters);

        return $this;
    }

    protected $contentType;

    /**
     * @param $contentType
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setContentType($contentType)
    {
        if (strpos($contentType, "\n") !== false || strpos($contentType, "\r") !== false)
        {
            throw new \InvalidArgumentException('The content type can not contain newline characters to prevent email header injection');
        }

        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    protected $encoding = 'utf-8';

    /**
     * @param $encoding
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setEncoding($encoding)
    {
        if (strpos($encoding, "\n") !== false || strpos($encoding, "\r") !== false)
        {
            throw new \InvalidArgumentException('The encoding can not contain newline characters to prevent email header injection');
        }

        $this->encoding = $encoding;

        return $this;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @var array
     */
    protected $to;

    /**
     * @param $value
     */
    public function setTo($value)
    {
        $this->to = (array)$value;
    }

    protected $from;

    /**
     * @param $value
     * @throws \InvalidArgumentException
     */
    public function setFrom($value)
    {
        $this->setHeader(sprintf('From: %s', $value));
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isHtml($data)
    {
        return $data[0] === '<';
    }
}