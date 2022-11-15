<?php

namespace spec\App\Model\Article;

use App\Model\Article\Article;
use App\Model\Article\Status;
use App\Model\Author\Author;
use DateTimeImmutable;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

class ArticleSpec extends ObjectBehavior
{
    private DateTimeImmutable $publishingDate;

    function let(Author $author)
    {
        $this->publishingDate = new DateTimeImmutable('+1 day');

        $this->beConstructedWith(
            'My article',
            'My article contents here!',
            $author,
            $this->publishingDate,
            Status::Draft,
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Article::class);
    }

    function it_throws_a_TypeError_when_a_wrong_status_is_given(Author $author)
    {
        $this->beConstructedWith(
            'My article',
            'My article contents here!',
            $author,
            $this->publishingDate,
            'UnknownStatus',
        );
    }

    function it_throws_InvalidArgumentException_when_title_is_empty(Author $author)
    {
        $this->beConstructedWith(
            '',
            'My article contents here!',
            $author,
            $this->publishingDate,
            Status::Draft,
        );
    }

    function it_throws_InvalidArgumentException_when_title_is_longer_than_128_characters(Author $author)
    {
        $this->beConstructedWith(
            'My article title is so looooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooong',
            'My article contents here!',
            $author,
            Status::Draft,
            $this->publishingDate,
        );

    }

    function it_return_the_right_title()
    {
        $this->title()->shouldBe('My article');
    }

    function it_return_the_right_contents()
    {
        $this->contents()->shouldBe('My article contents here!');
    }

    function it_return_the_right_publishingDate()
    {
        $this->publishingDate()->shouldBe($this->publishingDate);
    }
}
