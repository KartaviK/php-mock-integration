<?php

namespace Kartavik\PHPMock\Integration;

use Kartavik\PHPMock\Generator\ParameterBuilder;

/**
 * Defines a MockDelegateFunction.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @internal
 */
class MockDelegateFunctionBuilder
{
    /** The delegation method name. */
    public const METHOD = "delegate";

    /** @var string The namespace of the build class. */
    protected $namespace;

    /** @var \Text_Template The MockDelegateFunction template. */
    protected $template;

    public function __construct(\Text_Template $template = null)
    {
        $this->template = $template ?? new \Text_Template(__DIR__ . "/MockDelegateFunction.tpl");
    }

    /**
     * Builds a MockDelegateFunction for a function.
     *
     * @param string|null $functionName The mocked function.
     *
     * @SuppressWarnings(PHPMD)
     */
    public function build(string $functionName = null, ParameterBuilder $builder = null)
    {
        $parameterBuilder = $builder ?? new ParameterBuilder();
        $parameterBuilder->build($functionName);
        $signatureParameters = $parameterBuilder->getSignatureParameters();

        /**
         * If a class with the same signature exists, it is considered equivalent
         * to the generated class.
         */
        $hash = md5($signatureParameters);
        $this->namespace = __NAMESPACE__ . $hash;
        if (class_exists($this->getFullyQualifiedClassName())) {
            return;
        }

        $data = [
            "namespace" => $this->namespace,
            "signatureParameters" => $signatureParameters,
        ];
        $this->template->setVar($data, false);
        $definition = $this->template->render();

        eval($definition);
    }

    /**
     * Returns the fully qualified class name
     *
     * @return string The class name.
     */
    public function getFullyQualifiedClassName(): string
    {
        return "{$this->namespace}\\MockDelegateFunction";
    }
}
