namespace {namespace};

use Kartavik\PHPMock\Contract\Functions\ProviderInterface;

/**
 * Function provider which delegates to a mockable MockDelegate.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @internal
 */
abstract class MockDelegateFunction implements ProviderInterface
{
    /**
     * A mocked function will redirect its call to this method.
     *
     * @return mixed Returns the function output.
     */
    abstract public function delegate({signatureParameters});

    public function getClosure(): callable
    {
        return [$this, "delegate"];
    }
}
