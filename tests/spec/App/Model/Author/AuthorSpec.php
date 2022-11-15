<?php

namespace spec\App\Model\Author;

use App\Model\Author\Author;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

class AuthorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('roger.rabbit');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Author::class);
    }

    function it_throws_InvalidArgumentException_when_empty_username_is_given()
    {
        $this->beConstructedWith('');

        $this->shouldThrow(InvalidArgumentException::class);
    }

    function it_return_the_right_username()
    {
        $this->username()->shouldBe('roger.rabbit');
    }
}
