<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Util\Helper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
	private $service;
	private $contactEvent;

	public function setUp()
	{
		parent::setUp();

		self::bootKernel();

		$this->service = self::$kernel->getContainer();
		$this->contactEvent = $this->service->get('alfadocs_hubspot_contacts');
	}

    public function testFetchContact()
    {
    	$contacts = json_decode($this->contactEvent->fetch(), true);

    	$this->assertNotEmpty($contacts);
    	$this->assertArrayHasKey('contacts', $contacts);
    }

    public function testFetchContactEngement()
    {
    	$contacts = $this->contactEvent->getContactEngagements();

    	$this->assertNotEmpty($contacts);

    	$engagement = $contacts[0];
   
    	$this->assertArrayHasKey('date', $engagement);
    	$this->assertArrayHasKey('label', $engagement);
    }
}
