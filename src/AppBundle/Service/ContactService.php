<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;
use AppBundle\Util\Helper;
use AppBundle\Entity\Contact;
use AppBundle\Contract\ContactInterface;
use Doctrine\Common\Collections\Collection;

class ContactService implements ContactInterface
{
    private $accessToken;
    private $contact;
    private $client;

    public function __construct(Contact $contact, Client $client, $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->contact = $contact;
        $this->client = $client;

        $this->headers = [
            'Authorization' => 'Bearer ' . $this->accessToken,        
            'Accept'        => 'application/json',
        ];
    }

    /**
     * This fetch connects to data provider source and get the raw data
     */
    public function fetch($url = Helper::BASE_URL): ?string
    {
        try {
            $response = $this->client
                ->request('GET', $url, [
                    'headers' => $this->headers
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response->getBody();
    }

    /**
     * This gets the raw JSON data and convert it to an array
     */
    public function getContactEngagements(): ?Collection
    {
        $contacts = json_decode($this->fetch(), true);

        foreach ($contacts as $contact) {
            if (is_array($contact)) {
                foreach ($contact as $cont) {
                    $contactVid = $cont['vid'];
                    // Get contact engagements
                    $engagements = json_decode(
                        $this->fetch(Helper::BASE_CONTACT_ENGAGEMENT_URL . $contactVid),
                        true
                    );
                    // Get the created At date and the metadata
                    $this->setContactEngagements($engagements);
                }
            }
        }

        return $this->contact->getActivities();
    }

    /**
     * This sorts the data according to the date and tags
     */
    public function sortContactEngagements()
    {
        $sortedContactEngagements = [];

        foreach ($this->getContactEngagements() as $contactEngagement) {
            if (!array_key_exists($contactEngagement['date'], $sortedContactEngagements)) {
                $dateIndex = $contactEngagement['date'];
                $sortedContactEngagements[$dateIndex] = [];
                array_push(
                    $sortedContactEngagements[$dateIndex],
                    $contactEngagement['label']
                );
            } else {
                $dateIndex = $contactEngagement['date'];
                array_push(
                    $sortedContactEngagements[$dateIndex],
                    $contactEngagement['label']
                );
            }
        }

        return $sortedContactEngagements;
    }

    /**
     * This displays the sorted contacts to the console
     */
    public function displayContactEngagements()
    {
        $demoLabel  = Helper::DEMO_LABEL; 
        $closeLabel = Helper::CLOSE_LABEL;

        foreach ($this->sortContactEngagements() as $date => $sortedContactEngagement) {
            $sortedContactEngagement = array_count_values($sortedContactEngagement);

            $this->contact->getActivities()->clear();

            $this->contact->setDate($date);

            $this->contact->setDemoTag($demoLabel);
            $this->contact->setCloseTag($closeLabel);
            
            if (array_key_exists($demoLabel, $sortedContactEngagement)) {
                $demoLabelCount = $sortedContactEngagement[$demoLabel];
                $this->contact->setDemoCount($demoLabelCount);
            } else {
                $this->contact->setDemoCount(0);
            }

            if (array_key_exists($closeLabel, $sortedContactEngagement)) {
                $closeLabelCount = $sortedContactEngagement[$closeLabel];
                $this->contact->setCloseCount($closeLabelCount);
            } else {
                 $this->contact->setCloseCount(0);
            }

            echo $this->contact->__toString();
        }
    }

    protected function setContactEngagements($engagements)
    {
        foreach ($engagements as $engagement) {
            if (is_array($engagement)) {
                foreach ($engagement as $note) {
                    //  Check if the engagement labels contains either DEMO or CLOSE
                    if (!is_null(Helper::getEngagementLabel($note['metadata']['body']))) {
                        $this->contact->addActivity([
                            'date'  => Helper::getEngagementDate($note['engagement']['timestamp']),
                            'label' => Helper::getEngagementLabel($note['metadata']['body']),
                        ]);
                    }
                }
            }
        }
    }
}