<?php namespace Clean\PhpDocMd\Markdown;

use Clean\View\Phtml;
use Clean\PhpDocMd\ClassParser;
use ReflectionClass;

abstract class ClassInfo extends Phtml
{
    protected $reflectionClass;
    protected $sortMethods = false;

    /**
     * __construct 
     * 
     * @param ReflectionClass $class class 
     */
    public function __construct(ReflectionClass $class)
    {
        $this->setTemplate(__DIR__.'/../../tpl/' . $this->getFormatName() . '.class.phtml');
        $this->reflectionClass = $class;
    }

    abstract public function getFormatName();

    /**
     * When set to true methods will be sorted in markdown output
     * 
     * @param bool $value 
     * 
     * @return self
     */
    public function sortMethods($value)
    {
        assert(is_bool($value), 'Non bool parameter given for ' . __FUNCTION__);
        $this->sortMethods = $value;
        return $this;
    }

    /**
     * Renders markdown for class
     * 
     * @return string
     */
    public function render()
    {
        $parser = new ClassParser($this->reflectionClass);
        $methods = $parser->getMethodsDetails();

        if ($this->sortMethods) {
            ksort($methods);
        }

        $this->setData(
            [
                'className' => $this->reflectionClass->getName(),
                'classShortName' => $this->reflectionClass->getShortName(),
                'methods' => $methods,
                'classDescription' => $parser->getClassDescription(),
            ]
        );
        return parent::render();
    }
}
