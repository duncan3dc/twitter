<?php

namespace duncan3dc\TwitterTests\Posts;

class AbstractPostTest extends \PHPUnit_Framework_TestCase
{
    protected $post;

    public function setUp()
    {
        $this->post = new AbstractPost([
            "id"    =>  999,
            "date"  =>  time(),
            "post"  =>  "Post",
            "type"  =>  "Type",
            "data"  =>  "{}",
        ]);
    }


    public function testGetUserLink()
    {
        $this->post->hostname = "http://localhost";
        $result = $this->post->getUserLink("username");
        $this->assertSame("http://localhost/username", $result);
    }
}
