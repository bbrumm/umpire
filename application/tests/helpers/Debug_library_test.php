<?php
class Debug_library_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->CI->load->library('Debug_library');
        $this->obj = new Debug_library();
        $this->CI->config->set_item('debug_mode', true);
    }

    public function test_ArrayDebug() {
        $outputData = array('first element', 'second element');
        $beforeMessage = "The output is:";
        $actualOutput = $this->obj->debugOutput($beforeMessage, $outputData);
        $expectedOutput = "The output is:<pre>Array
(
    [0] => first element
    [1] => second element
)
</pre><BR />";
        $this->assertEquals($expectedOutput, $actualOutput);
    }

    public function test_TextDebug() {
        $outputData = "some output variable";
        $beforeMessage = "The output is:";
        $actualOutput = $this->obj->debugOutput($beforeMessage, $outputData);
        $expectedOutput = "The output is: some output variable<BR />";
        $this->assertEquals($expectedOutput, $actualOutput);
    }



}