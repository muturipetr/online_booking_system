<?php

namespace App\Test\Controller;

use App\Entity\Bookings;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/bookings/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Bookings::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Booking index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'booking[date]' => 'Testing',
            'booking[time]' => 'Testing',
            'booking[duration]' => 'Testing',
            'booking[TotalPrice]' => 'Testing',
            'booking[username]' => 'Testing',
            'booking[service]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Bookings();
        $fixture->setDate('My Title');
        $fixture->setTime('My Title');
        $fixture->setDuration('My Title');
        $fixture->setTotalPrice('My Title');
        $fixture->setUsername('My Title');
        $fixture->setService('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Booking');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Bookings();
        $fixture->setDate('Value');
        $fixture->setTime('Value');
        $fixture->setDuration('Value');
        $fixture->setTotalPrice('Value');
        $fixture->setUsername('Value');
        $fixture->setService('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'booking[date]' => 'Something New',
            'booking[time]' => 'Something New',
            'booking[duration]' => 'Something New',
            'booking[TotalPrice]' => 'Something New',
            'booking[username]' => 'Something New',
            'booking[service]' => 'Something New',
        ]);

        self::assertResponseRedirects('/bookings/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getTime());
        self::assertSame('Something New', $fixture[0]->getDuration());
        self::assertSame('Something New', $fixture[0]->getTotalPrice());
        self::assertSame('Something New', $fixture[0]->getUsername());
        self::assertSame('Something New', $fixture[0]->getService());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Bookings();
        $fixture->setDate('Value');
        $fixture->setTime('Value');
        $fixture->setDuration('Value');
        $fixture->setTotalPrice('Value');
        $fixture->setUsername('Value');
        $fixture->setService('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/bookings/');
        self::assertSame(0, $this->repository->count([]));
    }
}
