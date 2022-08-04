<?php
namespace kahlan\matcher;

class ToEcho
{
    /**
     * Description reference of the last `::match()` call.
     *
     * @var array
     */
    public static $_description = [];

    /**
     * Checks that `$actual` echo the `$expected` string.
     *
     * @param  Closure $actual   The closure to run.
     * @param  mixed   $expected The expected string.
     * @return boolean
     */
    public static function match($actual, $expected = null)
    {
        $a = static::actual($actual);
        static::_buildDescription($a, $expected);

        return $a === $expected;
    }

    /**
     * Returns the output generated by the closure.
     *
     * @param  Closure $actual The actual closure.
     * @return string          The string result.
     */
    public static function actual($actual)
    {
        ob_start();
        $actual();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Build the description of the runned `::match()` call.
     *
     * @param string $actual   The actual string.
     * @param string $expected The expected string.
     */
    public static function _buildDescription($actual, $expected)
    {
        $description = "echo the expected string.";
        $params['actual'] = $actual;
        $params['expected'] = $expected;
        static::$_description = compact('description', 'params');
    }

    /**
     * Returns the description report.
     */
    public static function description()
    {
        return static::$_description;
    }
}
