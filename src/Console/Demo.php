<?php

declare(strict_types=1);

namespace App\Console;

use App\Model\Article\Article;
use App\Model\Article\Status;
use App\Model\Author\Author;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function var_dump;

#[AsCommand(
    name: "app:demo",
    description: "Run command that will fill database with fake data"
)]
class Demo extends Command
{
    private const SIMPLE_PASSWORD = 'toto42';

    public function __construct(private EntityManagerInterface $em, private UserPasswordHasherInterface $encoder)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Run command that will fill database with fake data');

    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $faker = Factory::create();

        /* create a fixed user, so it's easier */
        $author = new Author('admin');
        $author->updatePassword($this->encoder->hashPassword($author, self::SIMPLE_PASSWORD));
        $this->em->persist($author);
        $this->em->persist(new Article(
            $faker->words(5, true),
            $faker->text(300),
            $author,
            DateTimeImmutable::createFromMutable(new DateTime('+1 day')),
            Status::Draft,
        ));

        for ($__AUTHOR_COUNT = 0; $__AUTHOR_COUNT < 10; ++$__AUTHOR_COUNT) {
            $author = new Author($faker->userName());
            $author->updatePassword($this->encoder->hashPassword($author, self::SIMPLE_PASSWORD));
            $this->em->persist($author);
            for ($__ARTICLE_COUNT = 0; $__ARTICLE_COUNT < 7; ++$__ARTICLE_COUNT) {
                $this->em->persist(new Article(
                    $faker->words(5, true),
                    $faker->text(300),
                    $author,
                    DateTimeImmutable::createFromMutable(new DateTime('+1 day')),
                    Status::Draft,
                ));
            }
        }

        try {
            $this->em->flush();
        } catch (Exception $exception) {
            var_dump($exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
