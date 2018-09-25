<?php

namespace AppBundle\Contract;

interface ContactInterface 
{
    /**
     * This fetch connects to data provider source and get the raw data
     */
    public function fetch();

    /**
     * This gets the raw JSON data and convert it to an array
     */
    public function getContactEngagements();

    /**
     * This sorts the data according to the tag occurence
     */
    public function sortContactEngagements();

    /**
     * This displays the sorted contacts to the console
     */
    public function displayContactEngagements();
}